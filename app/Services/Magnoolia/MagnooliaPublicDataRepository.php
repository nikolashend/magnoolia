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
                $filePayload = $this->readCurrentSnapshotFile();
                if ($filePayload !== null) {
                    Log::warning('Magnoolia public data served from last published file snapshot because DB was unavailable.');
                    return $filePayload;
                }
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
        $filePayload = $this->readCurrentSnapshotFile();
        if ($filePayload !== null) {
            return $filePayload;
        }

        // Safety fallback before first publication: derive sanitized payload from draft DB
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
