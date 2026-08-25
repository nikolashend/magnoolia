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

            $mediaSlots = \App\Models\MagnooliaMediaSlot::query()->orderBy('slot_key')->get();
            $lists = \App\Models\MagnooliaList::query()->orderBy('list_key')->with('items.mediaItem')->get();

            // NB: everything the client can change must be in here. The checksum is
            // computed from this snapshot, so anything left out makes a publish that
            // only touched it look like "no changes" and be refused — and rollback
            // would silently skip it.
            $privateSnapshot = [
                'units' => $units->map(fn (MagnooliaUnit $u) => $u->toArray())->values()->all(),
                'settings' => $settings?->toArray(),
                'content_blocks' => $contentBlocks->map(fn (MagnooliaContentBlock $c) => $c->toArray())->values()->all(),
                'media_slots' => $mediaSlots->map(fn ($s) => [
                    'slot_key' => $s->slot_key, 'media_item_id' => $s->media_item_id,
                ])->values()->all(),
                'lists' => $lists->map(fn (\App\Models\MagnooliaList $l) => [
                    'list_key' => $l->list_key,
                    'type' => $l->type,
                    'page' => $l->page,
                    'is_active' => $l->is_active,
                    'items' => $l->items->map(fn (\App\Models\MagnooliaListItem $i) => [
                        'sort_order' => $i->sort_order,
                        'is_active' => $i->is_active,
                        'media_item_id' => $i->media_item_id,
                        'payload_et' => $i->payload_et,
                        'payload_ru' => $i->payload_ru,
                        'payload_en' => $i->payload_en,
                        'meta' => $i->meta,
                    ])->values()->all(),
                ])->values()->all(),
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
                    // The one-line version for the home-page ribbon — a different
                    // text, not a truncation of the sentence above.
                    'note_short_et' => $settings->campaign_note_short_et,
                    'note_short_ru' => $settings->campaign_note_short_ru,
                    'note_short_en' => $settings->campaign_note_short_en,
                    'legal_note' => $settings->campaign_legal_note,
                    'legal_note_ru' => $settings->campaign_legal_note_ru,
                    'legal_note_en' => $settings->campaign_legal_note_en,
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

            // Phase 36 Module C — editable repeating blocks. A list with no active
            // entries is omitted entirely, so mg_list() returns [] and the template
            // keeps the array it ships with.
            $publicLists = [];
            foreach ($lists->where('is_active', true) as $list) {
                $entries = [];
                foreach ($list->items->where('is_active', true) as $item) {
                    $media = $item->mediaItem;
                    $entries[] = array_filter([
                        'image'        => $media?->public_path,
                        'image_alt_et' => $media?->alt_et,
                        'image_alt_ru' => $media?->alt_ru,
                        'image_alt_en' => $media?->alt_en,
                        'meta'         => $item->meta ?: [],
                        'et'           => $item->payload_et ?: [],
                        'ru'           => $item->payload_ru ?: [],
                        'en'           => $item->payload_en ?: [],
                    ], fn ($v) => $v !== null && $v !== []);
                }
                if ($entries !== []) {
                    $publicLists[$list->list_key] = $entries;
                }
            }

            $publicPayload = [
                'meta' => [
                    'generated_at' => now()->toIso8601String(),
                ],
                'lists' => $publicLists,
                'units' => $publicUnits,
                'settings' => $publicSettings,
                'content' => $publicContent,
                'gallery' => $gallery,
                // Phase 36 Module B — image-slot bindings travel with the publication
                // (decision 2), so preview and rollback cover pictures like they do texts.
                'slots' => \App\Models\MagnooliaMediaSlot::query()->with('mediaItem')->get()
                    ->filter(fn ($slot) => $slot->mediaItem && $slot->mediaItem->public_path)
                    ->mapWithKeys(fn ($slot) => [$slot->slot_key => [
                        'src'    => $slot->mediaItem->public_path,
                        'alt_et' => $slot->mediaItem->alt_et,
                        'alt_ru' => $slot->mediaItem->alt_ru,
                        'alt_en' => $slot->mediaItem->alt_en,
                        'width'  => $slot->mediaItem->width,
                        'height' => $slot->mediaItem->height,
                    ]])->all(),
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

            // Phase 36 Module B — picture assignments go back too, otherwise a rollback
            // restores the old text next to the new photo.
            if (array_key_exists('media_slots', $snapshot)) {
                foreach (($snapshot['media_slots'] ?? []) as $slot) {
                    \App\Models\MagnooliaMediaSlot::query()->updateOrCreate(
                        ['slot_key' => $slot['slot_key']],
                        ['media_item_id' => $slot['media_item_id'], 'updated_by' => $adminUserId]
                    );
                }
            }

            // Phase 36 Module C — lists. Entries are replaced wholesale rather than
            // matched one by one: they have no stable key of their own (the client
            // adds, deletes and reorders them), so "restore exactly this sequence"
            // is the only meaning rollback can honestly have.
            foreach (($snapshot['lists'] ?? []) as $listRow) {
                if (empty($listRow['list_key'])) {
                    continue;
                }
                $list = \App\Models\MagnooliaList::query()->updateOrCreate(
                    ['list_key' => $listRow['list_key']],
                    [
                        'type' => $listRow['type'],
                        'page' => $listRow['page'] ?? null,
                        'is_active' => $listRow['is_active'] ?? true,
                        'updated_by' => $adminUserId,
                    ]
                );
                $list->items()->delete();
                foreach (($listRow['items'] ?? []) as $i => $item) {
                    $list->items()->create([
                        'sort_order' => $item['sort_order'] ?? $i,
                        'is_active' => $item['is_active'] ?? true,
                        'media_item_id' => $item['media_item_id'] ?? null,
                        'payload_et' => $item['payload_et'] ?? null,
                        'payload_ru' => $item['payload_ru'] ?? null,
                        'payload_en' => $item['payload_en'] ?? null,
                        'meta' => $item['meta'] ?? null,
                        'updated_by' => $adminUserId,
                    ]);
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
