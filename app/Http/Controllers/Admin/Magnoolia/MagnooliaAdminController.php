<?php

namespace App\Http\Controllers\Admin\Magnoolia;

use App\Http\Controllers\Controller;
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

        return view('admin.magnoolia.dashboard', compact('stats', 'validation', 'active', 'canonicalConfigCount', 'usingCanonicalFallback'));
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

    public function publishForm()
    {
        $validation = $this->validationService->validateDraft();
        $active = MagnooliaPublication::query()->where('status', 'active')->orderByDesc('version')->first();
        $units = MagnooliaUnit::query()->orderBy('sort_order')->get();

        return view('admin.magnoolia.publish', compact('validation', 'active', 'units'));
    }

    public function publish(Request $request)
    {
        $validated = $request->validate([
            'publication_note' => 'required|string|max:500',
            'confirm_warnings' => 'nullable|boolean',
        ]);

        $validation = $this->validationService->validateDraft();
        if (!empty($validation['warnings']) && !((bool) ($validated['confirm_warnings'] ?? false))) {
            return back()->withErrors(['confirm_warnings' => 'Warnings must be explicitly confirmed.'])->withInput();
        }

        $result = $this->publicationService->publish((int) $request->user()->id, $validated['publication_note']);
        if (!($result['ok'] ?? false)) {
            return back()->withErrors(['publish' => $result['message'] ?? 'Publish failed.'])->withInput();
        }

        return redirect()->route('admin.magnoolia.publications.index')->with('status', 'Published successfully.');
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
            'campaign_discount_cents' => 'nullable|integer|min:0',
            'campaign_deadline' => 'nullable|date',
            'campaign_note_et' => 'nullable|string',
            'campaign_note_ru' => 'nullable|string',
            'campaign_note_en' => 'nullable|string',
            'campaign_legal_note' => 'nullable|string|max:500',
        ]);

        $settings = MagnooliaSetting::query()->latest('id')->first();
        $before = $settings?->toArray();

        $settings = MagnooliaSetting::query()->updateOrCreate(
            ['id' => $settings?->id ?? 1],
            array_merge($validated, ['updated_by' => (int) $request->user()->id])
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
