<?php

namespace App\Services\Magnoolia;

use App\Models\MagnooliaContentBlock;
use App\Models\MagnooliaPublication;
use App\Models\MagnooliaSetting;
use App\Models\MagnooliaUnit;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MagnooliaPublicationService
{
    public function __construct(
        private readonly MagnooliaValidationService $validationService,
        private readonly MagnooliaPublicDataRepository $publicDataRepository,
        private readonly MagnooliaAuditService $auditService,
    ) {
    }

    public function publish(int $adminUserId, string $note): array
    {
        return DB::transaction(function () use ($adminUserId, $note) {
            $validation = $this->validationService->validateDraft();
            if (!empty($validation['blockers'])) {
                $this->auditService->log('publication_failed', $adminUserId, reason: 'Validation blockers');
                return ['ok' => false, 'validation' => $validation, 'message' => 'Publish blocked by validation errors.'];
            }

            $units = MagnooliaUnit::query()->orderBy('sort_order')->get();
            $settings = MagnooliaSetting::query()->latest('id')->first();
            $contentBlocks = MagnooliaContentBlock::query()->orderBy('page')->orderBy('sort_order')->get();

            $privateSnapshot = [
                'units' => $units->map(fn (MagnooliaUnit $u) => $u->toArray())->values()->all(),
                'settings' => $settings?->toArray(),
                'content_blocks' => $contentBlocks->map(fn (MagnooliaContentBlock $c) => $c->toArray())->values()->all(),
            ];

            // Page-Texts CMS overrides, grouped by locale → key (only active blocks
            // with a value). Public read via mg_text() prefers these over lang files.
            $publicContent = ['et' => [], 'ru' => [], 'en' => []];
            foreach ($contentBlocks->where('is_active', true) as $cb) {
                foreach (['et', 'ru', 'en'] as $loc) {
                    if (filled($cb->{$loc})) {
                        $publicContent[$loc][$cb->key] = $cb->{$loc};
                    }
                }
            }

            $publicUnits = $units
                ->where('is_visible', true)
                ->map(function (MagnooliaUnit $u) {
                    $priceCents = $u->price_public ? $u->price_cents : null;
                    $status = $u->status === 'coming_soon' ? 'tbc' : $u->status;
                    return [
                        'id' => $u->unit_key,
                        'unit_key' => $u->unit_key,
                        'slug' => $u->slug,
                        'address' => $u->address,
                        'building' => 'Magnoolia tee ' . $u->building_number,
                        'section' => $u->building_number . '/' . $u->section_number,
                        'stage' => $u->stage,
                        'completion' => $u->completion_key,
                        'rooms' => $u->rooms,
                        'net_area' => (float) $u->net_area,
                        'terrace_area' => $u->terrace_area !== null ? (float) $u->terrace_area : null,
                        'balcony_area' => $u->balcony_area !== null ? (float) $u->balcony_area : null,
                        'storage_area' => $u->storage_area !== null ? (float) $u->storage_area : null,
                        'private_yard_area' => $u->private_yard_area !== null ? (float) $u->private_yard_area : null,
                        'parking_spaces' => $u->parking_spaces,
                        'status' => $status,
                        'price_public' => $u->price_public,
                        'price_cents' => $priceCents,
                        'price' => $priceCents !== null ? (int) round($priceCents / 100) : null,
                        'unit_price' => null,
                        'floorplan_1_pdf' => $u->floorplan_floor_1,
                        'floorplan_2_pdf' => $u->floorplan_floor_2,
                        'masterplan_key' => $u->asendiplaan_key,
                        'plan_type' => $u->plan_type,
                        'public_page_visible' => $u->public_page_visible,
                    ];
                })
                ->values()
                ->all();

            $publicSettings = [
                'campaign' => $settings && $settings->campaign_active ? [
                    'active' => true,
                    'discount_type' => $settings->campaign_discount_type ?? 'text',
                    'discount_cents' => $settings->campaign_discount_cents,
                    'deadline' => optional($settings->campaign_deadline)->toDateString(),
                    'note_et' => $settings->campaign_note_et,
                    'note_ru' => $settings->campaign_note_ru,
                    'note_en' => $settings->campaign_note_en,
                    'legal_note' => $settings->campaign_legal_note,
                    'cta_label' => $settings->campaign_cta_label,
                    'cta_target' => $settings->campaign_cta_target,
                ] : ['active' => false],
                'stage_1_completion' => $settings?->stage_1_completion,
                'stage_2_completion' => $settings?->stage_2_completion,
                'sales_contact_name' => $settings?->sales_contact_name,
                'sales_contact_phone' => $settings?->sales_contact_phone,
                'sales_contact_email' => $settings?->sales_contact_email,
                'commercial' => config('magnoolia.commercial', []),
            ];

            // Published gallery (managed media, category=gallery). Public /galerii
            // prefers this and falls back to its built-in list when empty.
            $gallery = \App\Models\MagnooliaMediaItem::query()->where('category', 'gallery')->orderBy('id')->get()
                ->map(function (\App\Models\MagnooliaMediaItem $m) {
                    $cat = 'valised';
                    if (preg_match('#/gallery/(exterior|interior|environment)/#', (string) $m->public_path, $mm)) {
                        $cat = ['exterior' => 'valised', 'interior' => 'interjer', 'environment' => 'keskkond'][$mm[1]];
                    }
                    return [
                        'src' => $m->public_path,
                        'alt_et' => $m->alt_et, 'alt_ru' => $m->alt_ru, 'alt_en' => $m->alt_en,
                        'title' => $m->title, 'cat' => $cat,
                    ];
                })->values()->all();

            $publicPayload = [
                'meta' => [
                    'generated_at' => now()->toIso8601String(),
                ],
                'units' => $publicUnits,
                'settings' => $publicSettings,
                'content' => $publicContent,
                'gallery' => $gallery,
            ];

            $checksum = hash('sha256', json_encode($privateSnapshot, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

            $currentActive = MagnooliaPublication::query()->where('status', 'active')->orderByDesc('version')->first();
            if ($currentActive && $currentActive->draft_checksum === $checksum) {
                return [
                    'ok' => false,
                    'validation' => $validation,
                    'message' => 'Avaldatud andmed ei erine praegusest versioonist.',
                    'duplicate' => true,
                ];
            }

            $nextVersion = (int) MagnooliaPublication::query()->max('version') + 1;
            $publicPayload['meta']['version'] = $nextVersion;

            MagnooliaPublication::query()->where('status', 'active')->update(['status' => 'inactive']);

            $publication = MagnooliaPublication::create([
                'version' => $nextVersion,
                'status' => 'active',
                'publication_note' => $note,
                'draft_checksum' => $checksum,
                'public_payload_json' => json_encode($publicPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'private_snapshot_json' => json_encode($privateSnapshot, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'published_by' => $adminUserId,
                'published_at' => now(),
            ]);

            $this->publicDataRepository->writeCurrentSnapshot($nextVersion, $publicPayload);
            Cache::forget('magnoolia.public.payload');
            $this->auditService->log('publication_created', $adminUserId, 'publication', (string) $publication->id, null, ['version' => $nextVersion], $note);

            return ['ok' => true, 'validation' => $validation, 'publication' => $publication];
        });
    }

    public function rollback(int $adminUserId, int $sourcePublicationId, string $reason): array
    {
        return DB::transaction(function () use ($adminUserId, $sourcePublicationId, $reason) {
            $source = MagnooliaPublication::query()->findOrFail($sourcePublicationId);
            $snapshot = $source->private_snapshot;

            foreach (($snapshot['units'] ?? []) as $row) {
                MagnooliaUnit::query()->where('unit_key', $row['unit_key'])->update([
                    'slug' => $row['slug'],
                    'address' => $row['address'],
                    'building_number' => $row['building_number'],
                    'section_number' => $row['section_number'],
                    'stage' => $row['stage'],
                    'status' => $row['status'],
                    'is_visible' => $row['is_visible'],
                    'price_cents' => $row['price_cents'],
                    'price_public' => $row['price_public'],
                    'rooms' => $row['rooms'],
                    'net_area' => $row['net_area'],
                    'terrace_area' => $row['terrace_area'],
                    'balcony_area' => $row['balcony_area'],
                    'storage_area' => $row['storage_area'],
                    'private_yard_area' => $row['private_yard_area'],
                    'parking_spaces' => $row['parking_spaces'],
                    'completion_key' => $row['completion_key'],
                    'floorplan_floor_1' => $row['floorplan_floor_1'],
                    'floorplan_floor_2' => $row['floorplan_floor_2'],
                    'asendiplaan_key' => $row['asendiplaan_key'],
                    'featured' => $row['featured'],
                    'sort_order' => $row['sort_order'],
                    'internal_note' => $row['internal_note'],
                    'lock_version' => ((int) $row['lock_version']) + 1,
                    'updated_by' => $adminUserId,
                    'updated_at' => now(),
                ]);
            }

            if (!empty($snapshot['settings'])) {
                MagnooliaSetting::query()->updateOrCreate(
                    ['id' => $snapshot['settings']['id'] ?? 1],
                    $snapshot['settings']
                );
            }

            // Restore Page-Texts content blocks so rollback returns prior copy too.
            foreach (($snapshot['content_blocks'] ?? []) as $cb) {
                if (!empty($cb['key'])) {
                    MagnooliaContentBlock::query()->updateOrCreate(
                        ['key' => $cb['key']],
                        collect($cb)->only(['page', 'label', 'group', 'et', 'ru', 'en', 'is_active', 'sort_order', 'updated_by'])->all()
                    );
                }
            }

            $publishResult = $this->publish($adminUserId, 'Rollback: ' . $reason);
            if (!($publishResult['ok'] ?? false)) {
                return $publishResult;
            }

            $publication = $publishResult['publication'];
            $publication->rolled_back_from_id = $sourcePublicationId;
            $publication->save();

            $this->auditService->log('publication_rolled_back', $adminUserId, 'publication', (string) $publication->id, ['from' => $sourcePublicationId], ['to' => $publication->version], $reason);

            return ['ok' => true, 'publication' => $publication];
        });
    }
}
