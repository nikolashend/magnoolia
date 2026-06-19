<?php

namespace App\Http\Controllers\Admin\Magnoolia;

use App\Http\Controllers\Controller;
use App\Models\MagnooliaAuditLog;
use App\Models\MagnooliaContentBlock;
use App\Models\MagnooliaPublication;
use App\Models\MagnooliaSetting;
use App\Models\MagnooliaUnit;
use App\Services\Magnoolia\MagnooliaAuditService;
use App\Services\Magnoolia\MagnooliaDraftService;
use App\Services\Magnoolia\MagnooliaExportService;
use App\Services\Magnoolia\MagnooliaImportService;
use App\Services\Magnoolia\MagnooliaPublicationService;
use App\Services\Magnoolia\MagnooliaPublicDataRepository;
use App\Services\Magnoolia\MagnooliaValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MagnooliaAdminController extends Controller
{
    public function __construct(
        private readonly MagnooliaValidationService $validationService,
        private readonly MagnooliaDraftService $draftService,
        private readonly MagnooliaPublicationService $publicationService,
        private readonly MagnooliaAuditService $auditService,
        private readonly MagnooliaExportService $exportService,
        private readonly MagnooliaImportService $importService,
        private readonly MagnooliaPublicDataRepository $publicDataRepository,
    ) {
    }

    public function dashboard()
    {
        $active = MagnooliaPublication::query()->where('status', 'active')->orderByDesc('version')->first();
        $units = MagnooliaUnit::query()->get();
        $validation = $this->validationService->validateDraft();

        // Phase 32 honesty banner: the public site currently sources its 19 homes
        // from the canonical config fallback (config/magnoolia_units.php), NOT the
        // DB-managed units below. Surface that so "0 units" is never misread as a
        // broken public site. The DB-backed admin editing arrives in Phase 33.
        $canonicalConfigCount = count((array) config('magnoolia_units', []));
        $usingCanonicalFallback = $units->count() === 0 && !$active;

        // Explicit "what is live right now" indicator for the client.
        $liveSource = $active
            ? 'Active DB publication v' . $active->version
            : ($units->count() > 0 ? 'Canonical config fallback (no active publication yet — Publish to go live)' : 'Canonical config fallback');
        $lastEditedUnit = MagnooliaUnit::query()->orderByDesc('updated_at')->first();
        $recentAudit = MagnooliaAuditLog::query()->with('admin')->orderByDesc('id')->limit(10)->get();

        $stats = [
            'published_version' => $active?->version,
            'published_at' => $active?->published_at,
            'unpublished_changes' => $this->estimateUnpublishedChanges($active),
            'blockers' => count($validation['blockers']),
            'warnings' => count($validation['warnings']),
            'available' => $units->where('status', 'available')->count(),
            'reserved' => $units->where('status', 'reserved')->count(),
            'sold' => $units->where('status', 'sold')->count(),
            'stage_1' => $units->where('stage', 1)->count(),
            'stage_2' => $units->where('stage', 2)->count(),
            'public_prices' => $units->where('price_public', true)->count(),
            'hidden_prices' => $units->where('price_public', false)->count(),
        ];

        return view('admin.magnoolia.dashboard', compact('stats', 'validation', 'active', 'canonicalConfigCount', 'usingCanonicalFallback', 'liveSource', 'lastEditedUnit', 'recentAudit'));
    }

    public function units(Request $request)
    {
        $query = MagnooliaUnit::query();

        if ($search = trim((string) $request->query('q'))) {
            $query->where('address', 'like', '%' . $search . '%');
        }
        if ($stage = $request->query('stage')) {
            $query->where('stage', (int) $stage);
        }
        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }
        if ($priceVisibility = $request->query('price_public')) {
            $query->where('price_public', $priceVisibility === '1');
        }
        if ($building = $request->query('building')) {
            $query->where('building_number', (int) $building);
        }

        $units = $query->orderBy('sort_order')->paginate(50)->withQueryString();

        return view('admin.magnoolia.units-index', compact('units'));
    }

    public function editUnit(string $unitKey)
    {
        $unit = MagnooliaUnit::query()->where('unit_key', $unitKey)->firstOrFail();
        return view('admin.magnoolia.units-edit', compact('unit'));
    }

    public function updateUnit(Request $request, string $unitKey)
    {
        $unit = MagnooliaUnit::query()->where('unit_key', $unitKey)->firstOrFail();

        $validated = $request->validate([
            'status' => 'required|string|in:available,reserved,sold,coming_soon',
            'is_visible' => 'nullable|boolean',
            'price_cents' => 'nullable|integer|min:0',
            'price_public' => 'nullable|boolean',
            'rooms' => 'required|integer|min:1',
            'net_area' => 'required|numeric|min:0.1',
            'terrace_area' => 'nullable|numeric|min:0',
            'balcony_area' => 'nullable|numeric|min:0',
            'storage_area' => 'nullable|numeric|min:0',
            'private_yard_area' => 'nullable|numeric|min:0',
            'parking_spaces' => 'required|integer|min:0',
            'completion_key' => 'required|string|max:32',
            'floorplan_floor_1' => 'required|string|max:255',
            'floorplan_floor_2' => 'required|string|max:255',
            'asendiplaan_key' => 'nullable|string|max:128',
            'featured' => 'nullable|boolean',
            'sort_order' => 'required|integer|min:0',
            'internal_note' => 'nullable|string',
            'change_reason' => 'nullable|string|max:500',
        ]);

        $criticalTouched = $unit->status !== $validated['status']
            || ((bool) $unit->price_public) !== ((bool) ($validated['price_public'] ?? false))
            || (int) ($unit->price_cents ?? -1) !== (int) ($validated['price_cents'] ?? -1)
            || ((bool) $unit->is_visible) !== ((bool) ($validated['is_visible'] ?? false));

        if ($criticalTouched && empty($validated['change_reason'])) {
            return back()->withErrors(['change_reason' => 'Change reason is required for critical fields.'])->withInput();
        }

        $before = $unit->toArray();
        $changedFields = $this->draftService->changedFieldsForUnit($unit, $validated);
        $updated = $this->draftService->applyUnitDraft($unit, $validated, (int) $request->user()->id);

        $this->auditService->log(
            'unit_updated',
            (int) $request->user()->id,
            'unit',
            $unit->unit_key,
            $before,
            $updated->toArray(),
            $validated['change_reason'] ?? null,
            $request->ip(),
            $request->userAgent(),
        );

        return redirect()->route('admin.magnoolia.units.edit', ['unit' => $unit->unit_key])
            ->with('status', 'Draft saved. Changed fields: ' . implode(', ', array_keys($changedFields)));
    }

    /**
     * Inline quick edit from the units list — status and/or public-price visibility.
     * Comfortable daily-workflow action: marks a home Vaba/Broneeritud/Müüdud or
     * toggles price visibility without opening the full form. Still audited; still
     * draft-only (does not affect the public site until the next Publish).
     */
    public function quickUpdate(Request $request, string $unitKey)
    {
        $unit = MagnooliaUnit::query()->where('unit_key', $unitKey)->firstOrFail();

        $validated = $request->validate([
            'field' => 'required|in:status,price_public',
            'status' => 'required_if:field,status|in:available,reserved,sold,coming_soon',
            'price_public' => 'required_if:field,price_public|in:0,1',
            'change_reason' => 'nullable|string|max:500',
        ]);

        $before = $unit->toArray();
        $reason = ($validated['change_reason'] ?? null) ?: 'Quick edit (' . $validated['field'] . ')';

        $incoming = $unit->toArray();
        if ($validated['field'] === 'status') {
            $incoming['status'] = $validated['status'];
        } else {
            $incoming['price_public'] = (bool) $validated['price_public'];
        }

        $updated = $this->draftService->applyUnitDraft($unit, $incoming, (int) $request->user()->id);

        $this->auditService->log(
            'unit_updated',
            (int) $request->user()->id,
            'unit',
            $unit->unit_key,
            $before,
            $updated->toArray(),
            $reason,
            $request->ip(),
            $request->userAgent(),
        );

        return back()->with('status', "Draft updated for {$unit->address} ({$validated['field']}). Publish to make it live.");
    }

    // ── Page-Texts CMS (Phase 33.1) ──────────────────────────────────────────
    public function content()
    {
        $blocks = MagnooliaContentBlock::query()->orderBy('page')->orderBy('sort_order')->get()->groupBy('page');
        $pages = MagnooliaContentBlock::PAGES;
        return view('admin.magnoolia.content-index', compact('blocks', 'pages'));
    }

    public function contentUpdate(Request $request, MagnooliaContentBlock $block)
    {
        $validated = $request->validate([
            'et' => 'nullable|string',
            'ru' => 'nullable|string',
            'en' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $before = $block->toArray();
        $block->fill([
            'et' => $validated['et'] ?? null,
            'ru' => $validated['ru'] ?? null,
            'en' => $validated['en'] ?? null,
            'is_active' => (bool) ($validated['is_active'] ?? false),
            'updated_by' => (int) $request->user()->id,
        ])->save();

        $this->auditService->log('content_updated', (int) $request->user()->id, 'content', $block->key,
            $before, $block->toArray(), 'Page text edited (draft)', $request->ip(), $request->userAgent());

        return back()->with('status', "Saved \"{$block->label}\" — draft only; Publish to make it live.");
    }

    /**
     * Bulk action on selected homes (draft-only, audited, no destructive delete).
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'units' => 'required|array|min:1',
            'units.*' => 'string',
            'bulk_action' => 'required|in:status_available,status_reserved,status_sold,hide,show,price_public,price_hidden',
        ]);

        $apply = match ($validated['bulk_action']) {
            'status_available' => ['status' => 'available'],
            'status_reserved' => ['status' => 'reserved'],
            'status_sold' => ['status' => 'sold'],
            'hide' => ['is_visible' => false],
            'show' => ['is_visible' => true],
            'price_public' => ['price_public' => true],
            'price_hidden' => ['price_public' => false],
        };

        $count = 0;
        foreach (MagnooliaUnit::query()->whereIn('unit_key', $validated['units'])->get() as $unit) {
            $before = $unit->toArray();
            $incoming = array_merge($unit->toArray(), $apply);
            $updated = $this->draftService->applyUnitDraft($unit, $incoming, (int) $request->user()->id);
            $this->auditService->log('unit_updated', (int) $request->user()->id, 'unit', $unit->unit_key,
                $before, $updated->toArray(), 'Bulk: ' . $validated['bulk_action'], $request->ip(), $request->userAgent());
            $count++;
        }

        return back()->with('status', "Bulk action applied to {$count} home(s) (draft). Publish to make it live.");
    }

    public function validateDraft()
    {
        $validation = $this->validationService->validateDraft();
        return view('admin.magnoolia.validate', compact('validation'));
    }

    public function preview()
    {
        $units = MagnooliaUnit::query()->orderBy('sort_order')->get();
        $settings = MagnooliaSetting::query()->latest('id')->first();

        return view('admin.magnoolia.preview', [
            'units' => $units,
            'settings' => $settings,
            'banner' => config('magnoolia_phase24.admin_preview_banner'),
        ]);
    }

    // ── Leads / Inquiries (Phase 33.1) ───────────────────────────────────────
    public function leads(Request $request)
    {
        $query = \App\Models\MagnooliaLead::query()->latest('created_at');
        if ($status = $request->query('lead_status')) {
            $query->where('lead_status', $status);
        }
        if ($search = trim((string) $request->query('q'))) {
            $query->where(fn ($q) => $q->where('name', 'like', "%$search%")->orWhere('email', 'like', "%$search%")->orWhere('unit_address', 'like', "%$search%"));
        }
        $leads = $query->paginate(50)->withQueryString();
        $counts = \App\Models\MagnooliaLead::query()->selectRaw('lead_status, count(*) c')->groupBy('lead_status')->pluck('c', 'lead_status')->all();
        return view('admin.magnoolia.leads-index', compact('leads', 'counts'));
    }

    public function leadStatus(Request $request, \App\Models\MagnooliaLead $lead)
    {
        $validated = $request->validate(['lead_status' => 'required|in:new,contacted,archived']);
        $lead->update(['lead_status' => $validated['lead_status']]);
        $this->auditService->log('lead_status_changed', (int) $request->user()->id, 'lead', (string) $lead->id, null,
            ['lead_status' => $validated['lead_status']], null, $request->ip(), $request->userAgent());
        return back()->with('status', "Lead #{$lead->id} marked {$validated['lead_status']}.");
    }

    public function leadsExport()
    {
        $rows = \App\Models\MagnooliaLead::query()->latest('created_at')->get();
        $headers = ['id', 'created_at', 'name', 'email', 'phone', 'unit_address', 'locale', 'source_page', 'lead_status'];
        $csv = implode(',', $headers) . "\n";
        foreach ($rows as $l) {
            $csv .= implode(',', array_map(fn ($v) => '"' . str_replace('"', '""', (string) $v) . '"', [
                $l->id, $l->created_at, $l->name, $l->email, $l->phone, $l->unit_address, $l->locale, $l->source_page, $l->lead_status,
            ])) . "\n";
        }
        return response($csv, 200, ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="magnoolia-leads.csv"']);
    }

    /** Admin help / onboarding. */
    public function help()
    {
        return view('admin.magnoolia.help');
    }

    /** "Changes since last publish" — full draft↔live diff. */
    public function changes()
    {
        $diff = app(\App\Services\Magnoolia\MagnooliaDiffService::class)->diff();
        return view('admin.magnoolia.changes', compact('diff'));
    }

    public function publishForm()
    {
        $validation = $this->validationService->validateDraft();
        $active = MagnooliaPublication::query()->where('status', 'active')->orderByDesc('version')->first();
        $units = MagnooliaUnit::query()->orderBy('sort_order')->get();
        $diff = app(\App\Services\Magnoolia\MagnooliaDiffService::class)->diff();

        return view('admin.magnoolia.publish', compact('validation', 'active', 'units', 'diff'));
    }

    public function publish(Request $request)
    {
        $validated = $request->validate([
            'publication_note' => 'required|string|max:500',
            'confirm_warnings' => 'nullable|boolean',
            'confirm_publish' => 'accepted',
        ], [
            'confirm_publish.accepted' => 'Please confirm that these changes will update the public website.',
        ]);

        $validation = $this->validationService->validateDraft();
        if (!empty($validation['warnings']) && !((bool) ($validated['confirm_warnings'] ?? false))) {
            return back()->withErrors(['confirm_warnings' => 'Warnings must be explicitly confirmed.'])->withInput();
        }

        $result = $this->publicationService->publish((int) $request->user()->id, $validated['publication_note']);
        if (!($result['ok'] ?? false)) {
            return back()->withErrors(['publish' => $result['message'] ?? 'Publish failed.'])->withInput();
        }

        return redirect()->route('admin.magnoolia.publications.index')
            ->with('status', 'Published successfully.')
            ->with('published_version', $result['publication']->version ?? null);
    }

    public function publications()
    {
        $publications = MagnooliaPublication::query()->orderByDesc('version')->paginate(50);
        return view('admin.magnoolia.publications-index', compact('publications'));
    }

    public function rollbackForm(int $id)
    {
        $publication = MagnooliaPublication::query()->findOrFail($id);
        return view('admin.magnoolia.rollback', compact('publication'));
    }

    public function rollback(Request $request, int $id)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $result = $this->publicationService->rollback((int) $request->user()->id, $id, $validated['reason']);
        if (!($result['ok'] ?? false)) {
            return back()->withErrors(['rollback' => $result['message'] ?? 'Rollback failed.']);
        }

        return redirect()->route('admin.magnoolia.publications.index')->with('status', 'Rollback published.');
    }

    public function audit()
    {
        $logs = \App\Models\MagnooliaAuditLog::query()->with('admin')->orderByDesc('id')->paginate(100);
        return view('admin.magnoolia.audit', compact('logs'));
    }

    public function campaign()
    {
        $settings = MagnooliaSetting::query()->latest('id')->first();
        return view('admin.magnoolia.campaign', compact('settings'));
    }

    public function updateCampaign(Request $request)
    {
        $validated = $request->validate([
            'campaign_active' => 'nullable|boolean',
            'campaign_discount_eur' => 'nullable|numeric|min:0',   // human euros (stored as cents)
            'campaign_discount_type' => 'required|in:text,fixed,none',
            'campaign_deadline' => 'nullable|date',
            'campaign_note_et' => 'nullable|string',
            'campaign_note_ru' => 'nullable|string',
            'campaign_note_en' => 'nullable|string',
            'campaign_legal_note' => 'nullable|string|max:500',
            'campaign_cta_label' => 'nullable|string|max:120',
            'campaign_cta_target' => 'nullable|string|max:255',
        ]);

        $settings = MagnooliaSetting::query()->latest('id')->first();
        $before = $settings?->toArray();

        $payload = [
            'campaign_active' => (bool) ($validated['campaign_active'] ?? false),
            // Euros in the UI → cents in storage (only when a fixed amount is used).
            'campaign_discount_cents' => $validated['campaign_discount_type'] === 'fixed'
                ? (int) round(((float) ($validated['campaign_discount_eur'] ?? 0)) * 100)
                : null,
            'campaign_discount_type' => $validated['campaign_discount_type'],
            'campaign_deadline' => $validated['campaign_deadline'] ?? null,
            'campaign_note_et' => $validated['campaign_note_et'] ?? null,
            'campaign_note_ru' => $validated['campaign_note_ru'] ?? null,
            'campaign_note_en' => $validated['campaign_note_en'] ?? null,
            'campaign_legal_note' => $validated['campaign_legal_note'] ?? null,
            'campaign_cta_label' => $validated['campaign_cta_label'] ?? null,
            'campaign_cta_target' => $validated['campaign_cta_target'] ?? null,
            'updated_by' => (int) $request->user()->id,
        ];

        $settings = MagnooliaSetting::query()->updateOrCreate(
            ['id' => $settings?->id ?? 1],
            $payload
        );

        $this->auditService->log(
            'campaign_changed',
            (int) $request->user()->id,
            'settings',
            (string) $settings->id,
            $before,
            $settings->toArray(),
            'Campaign draft update',
            $request->ip(),
            $request->userAgent(),
        );

        return back()->with('status', 'Campaign draft saved.');
    }

    public function exportCsv()
    {
        $csv = $this->exportService->exportUnitsCsv();
        $name = 'magnoolia-units-' . now()->format('Ymd-His') . '.csv';

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $name . '"',
        ]);
    }

    public function importCsvPreview(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $csv = file_get_contents($request->file('csv_file')->getRealPath());
        $preview = $this->importService->previewCsv($csv ?: '');

        $this->auditService->log('csv_import_previewed', (int) $request->user()->id, 'import', null, null, ['errors' => $preview['errors'] ?? []], null, $request->ip(), $request->userAgent());

        $token = 'magnoolia_import_preview_' . now()->timestamp . '_' . bin2hex(random_bytes(4));
        Storage::disk('local')->put('magnoolia/import-previews/' . $token . '.json', json_encode($preview, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        return view('admin.magnoolia.import-preview', compact('preview', 'token'));
    }

    public function importCsvApply(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string',
            'confirm_apply' => 'required|accepted',
        ]);

        $path = 'magnoolia/import-previews/' . $validated['token'] . '.json';
        if (!Storage::disk('local')->exists($path)) {
            return back()->withErrors(['token' => 'Import preview token not found.']);
        }

        $preview = json_decode(Storage::disk('local')->get($path), true);
        $errors = $preview['errors'] ?? [];
        if (!empty($errors)) {
            return back()->withErrors(['csv' => 'Import has validation errors and cannot be applied.']);
        }

        $updated = $this->importService->applyPreviewRows($preview['rows'] ?? [], (int) $request->user()->id);
        $this->auditService->log('csv_import_applied', (int) $request->user()->id, 'import', null, null, ['updated' => $updated], null, $request->ip(), $request->userAgent());

        return redirect()->route('admin.magnoolia.units.index')->with('status', "CSV import applied to draft: {$updated} rows.");
    }

    private function estimateUnpublishedChanges(?MagnooliaPublication $active): int
    {
        if (!$active) {
            return MagnooliaUnit::query()->count();
        }

        $snapshotUnits = collect($active->private_snapshot['units'] ?? [])->keyBy('unit_key');
        $currentUnits = MagnooliaUnit::query()->get()->keyBy('unit_key');

        $changed = 0;
        foreach ($currentUnits as $key => $unit) {
            $old = $snapshotUnits->get($key);
            if (!$old || (int) ($old['lock_version'] ?? 0) !== (int) $unit->lock_version) {
                $changed++;
            }
        }

        return $changed;
    }
}
