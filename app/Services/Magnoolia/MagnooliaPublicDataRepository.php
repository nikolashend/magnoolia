<?php

namespace App\Services\Magnoolia;

use App\Models\MagnooliaPublication;
use App\Models\MagnooliaUnit;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MagnooliaPublicDataRepository
{
    public function getPublicPayload(): array
    {
        return Cache::remember('magnoolia.public.payload', 60, function () {
            try {
                $active = MagnooliaPublication::query()
                    ->where('status', 'active')
                    ->orderByDesc('version')
                    ->first();

                if ($active) {
                    return $active->public_payload;
                }
            } catch (\Throwable $e) {
                // DB unavailable: fall through to the canonical-config-first fallback
                // below so the public site still serves the approved 19 homes with
                // correct statuses (the on-disk snapshot is a deeper fallback inside).
                Log::warning('Magnoolia public data: DB unavailable, using canonical fallback. ' . $e->getMessage());
            }

            return $this->getFallbackPayload();
        });
    }

    public function getUnits(): array
    {
        $payload = $this->getPublicPayload();
        return $payload['units'] ?? [];
    }

    public function getSettings(): array
    {
        $payload = $this->getPublicPayload();
        return $payload['settings'] ?? [];
    }

    public function writeCurrentSnapshot(int $version, array $publicPayload): void
    {
        $dir = storage_path('app/magnoolia/published');
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $versionPath = $dir . '/version-' . $version . '.json';
        file_put_contents($versionPath, json_encode($publicPayload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        $tmpPath = $dir . '/current.tmp.json';
        $currentPath = $dir . '/current.json';
        file_put_contents($tmpPath, json_encode($publicPayload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        rename($tmpPath, $currentPath);
    }

    private function getFallbackPayload(): array
    {
        // Before the first real admin publication the canonical source of truth is
        // config/magnoolia_units.php (19 homes with the approved statuses + areas).
        // This guarantees 19 homes are always shown and that reserved/sold statuses
        // stay consistent across every public surface. A real active publication
        // (checked in getPublicPayload) overrides this; the on-disk snapshot is only
        // a disaster-recovery fallback for when the DB is unavailable.
        $configPayload = $this->fromCanonicalConfig();
        if ($configPayload !== null) {
            return $configPayload;
        }

        $filePayload = $this->readCurrentSnapshotFile();
        if ($filePayload !== null) {
            return $filePayload;
        }

        // Last-resort fallback: derive sanitized payload from draft DB
        $units = MagnooliaUnit::query()->orderBy('sort_order')->get()->map(function (MagnooliaUnit $unit) {
            $priceCents = $unit->price_public ? $unit->price_cents : null;
            $status = $unit->status === 'coming_soon' ? 'tbc' : $unit->status;

            return [
                'id' => $unit->unit_key,
                'unit_key' => $unit->unit_key,
                'slug' => $unit->slug,
                'address' => $unit->address,
                'building' => 'Magnoolia tee ' . $unit->building_number,
                'section' => $unit->building_number . '/' . $unit->section_number,
                'stage' => $unit->stage,
                'completion' => $unit->completion_key,
                'rooms' => $unit->rooms,
                'net_area' => (float) $unit->net_area,
                'terrace_area' => $unit->terrace_area !== null ? (float) $unit->terrace_area : null,
                'balcony_area' => $unit->balcony_area !== null ? (float) $unit->balcony_area : null,
                'storage_area' => $unit->storage_area !== null ? (float) $unit->storage_area : null,
                'private_yard_area' => $unit->private_yard_area !== null ? (float) $unit->private_yard_area : null,
                'parking_spaces' => $unit->parking_spaces,
                'status' => $status,
                'is_visible' => $unit->is_visible,
                'price_public' => $unit->price_public,
                'price_cents' => $priceCents,
                'price' => $priceCents !== null ? (int) round($priceCents / 100) : null,
                'floorplan_1_pdf' => $unit->floorplan_floor_1,
                'floorplan_2_pdf' => $unit->floorplan_floor_2,
                'masterplan_key' => $unit->asendiplaan_key,
                'plan_type' => $unit->plan_type,
                'public_page_visible' => $unit->public_page_visible ?? true,
            ];
        })->values()->all();

        return [
            'meta' => [
                'version' => 0,
                'generated_at' => now()->toIso8601String(),
            ],
            'units' => $units,
            'settings' => [],
        ];
    }

    /**
     * Canonical pre-publication payload derived from config/magnoolia_units.php.
     *
     * Returns null only if the config is empty (so the caller can fall back).
     * Prices are intentionally withheld here (price_public=false, price_cents=null):
     * exact prices are unconfirmed for public display and surface as
     * "Hind täpsustamisel" until a real publication confirms them.
     */
    private function fromCanonicalConfig(): ?array
    {
        $config = (array) config('magnoolia_units', []);
        if (empty($config)) {
            return null;
        }

        $units = [];
        foreach ($config as $u) {
            $building = 0;
            $section = 0;
            if (preg_match('/tee-(\d+)-(\d+)/', (string) ($u['id'] ?? ''), $m)) {
                $building = (int) $m[1];
                $section = (int) $m[2];
            } elseif (preg_match('#(\d+)\s*/\s*(\d+)#', (string) ($u['section'] ?? ''), $m)) {
                $building = (int) $m[1];
                $section = (int) $m[2];
            }

            $unitKey = 'B' . $building . '-S' . $section;
            $status = ($u['status'] ?? 'available') === 'coming_soon' ? 'tbc' : ($u['status'] ?? 'available');

            $units[] = [
                'id' => $unitKey,
                'unit_key' => $unitKey,
                'slug' => strtolower($unitKey),
                'address' => $u['address'] ?? null,
                'building' => $u['building'] ?? ('Magnoolia tee ' . $building),
                'section' => $u['section'] ?? ($building . '/' . $section),
                'stage' => $u['stage'] ?? null,
                'completion' => $u['completion'] ?? null,
                'rooms' => $u['rooms'] ?? null,
                'net_area' => isset($u['net_area']) ? (float) $u['net_area'] : null,
                'terrace_area' => isset($u['terrace_area']) ? (float) $u['terrace_area'] : null,
                'balcony_area' => isset($u['balcony_area']) ? (float) $u['balcony_area'] : null,
                'storage_area' => isset($u['storage_area']) ? (float) $u['storage_area'] : null,
                'private_yard_area' => isset($u['private_yard_area']) ? (float) $u['private_yard_area'] : null,
                'parking_spaces' => $u['parking_spaces'] ?? ($u['parking'] ?? null),
                'status' => $status,
                'is_visible' => true,
                // Prices withheld until confirmed by a real publication (no price_cents leak).
                'price_public' => false,
                'price_cents' => null,
                'price' => null,
                'floorplan_1_pdf' => $u['floorplan_1_pdf'] ?? null,
                'floorplan_2_pdf' => $u['floorplan_2_pdf'] ?? null,
                'masterplan_key' => $u['masterplan_key'] ?? null,
                'plan_type' => $u['plan_type'] ?? null,
                'public_page_visible' => true,
            ];
        }

        return [
            'meta' => [
                'version' => 0,
                'source' => 'canonical_config',
                'generated_at' => now()->toIso8601String(),
            ],
            'units' => $units,
            'settings' => [],
        ];
    }

    private function readCurrentSnapshotFile(): ?array
    {
        $currentPath = storage_path('app/magnoolia/published/current.json');
        if (!is_file($currentPath)) {
            return null;
        }

        $decoded = json_decode((string) file_get_contents($currentPath), true);
        return is_array($decoded) ? $decoded : null;
    }
}
