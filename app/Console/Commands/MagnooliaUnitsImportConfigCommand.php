<?php

namespace App\Console\Commands;

use App\Models\MagnooliaAuditLog;
use App\Models\MagnooliaUnit;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MagnooliaUnitsImportConfigCommand extends Command
{
    protected $signature = 'magnoolia:units:import-config {--dry-run} {--apply}';

    protected $description = 'Import initial Magnoolia units from config/magnoolia_units.php into DB draft table';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $apply = (bool) $this->option('apply');

        if (!$dryRun && !$apply) {
            $this->error('Use either --dry-run or --apply.');
            return self::FAILURE;
        }

        if ($dryRun && $apply) {
            $this->error('Use only one mode: --dry-run or --apply.');
            return self::FAILURE;
        }

        $configUnits = config('magnoolia.units', []);
        $this->line('Config units: ' . count($configUnits));

        $distribution = [];
        foreach ($configUnits as $unit) {
            $distribution[$unit['building']] = ($distribution[$unit['building']] ?? 0) + 1;
        }
        foreach ($distribution as $building => $count) {
            $this->line(" - {$building}: {$count}");
        }

        $expected = [
            'Magnoolia tee 1' => 3,
            'Magnoolia tee 3' => 4,
            'Magnoolia tee 5' => 3,
            'Magnoolia tee 7' => 3,
            'Magnoolia tee 9' => 3,
            'Magnoolia tee 11' => 3,
        ];
        if ($distribution !== $expected) {
            $this->warn('Building distribution mismatch from expected 3/4/3/3/3/3.');
        }

        $uniqueUnitKeys = collect($configUnits)->pluck('id')->unique()->count() === count($configUnits);
        $uniqueAddresses = collect($configUnits)->pluck('address')->unique()->count() === count($configUnits);

        if (!$uniqueUnitKeys) {
            $this->error('Duplicate unit IDs in config source.');
            return self::FAILURE;
        }

        if (!$uniqueAddresses) {
            $this->error('Duplicate addresses in config source.');
            return self::FAILURE;
        }

        if ($dryRun) {
            $existing = MagnooliaUnit::query()->count();
            $this->info("Dry-run complete. Existing DB units: {$existing}. No changes applied.");
            return self::SUCCESS;
        }

        $created = 0;
        $updated = 0;

        foreach ($configUnits as $index => $unit) {
            $buildingNumber = (int) Str::after($unit['building'], 'Magnoolia tee ');
            $sectionNumber = (int) Str::after((string) ($unit['section'] ?? ''), '/');
            if ($sectionNumber <= 0) {
                $sectionNumber = $index + 1;
            }

            $row = [
                'slug' => Str::slug($unit['address']),
                'address' => $unit['address'],
                'building_number' => $buildingNumber,
                'section_number' => $sectionNumber,
                'stage' => (int) ($unit['stage'] ?? 1),
                'status' => $this->mapStatus((string) ($unit['status'] ?? 'available')),
                'is_visible' => true,
                'price_cents' => isset($unit['price']) && $unit['price'] !== null ? (int) round(((float) $unit['price']) * 100) : null,
                'price_public' => (bool) ($unit['price_public'] ?? false),
                'rooms' => (int) ($unit['rooms'] ?? 0),
                'net_area' => (float) ($unit['net_area'] ?? 0),
                'terrace_area' => isset($unit['terrace_area']) ? (float) $unit['terrace_area'] : null,
                'balcony_area' => isset($unit['balcony_area']) ? (float) $unit['balcony_area'] : null,
                'storage_area' => isset($unit['storage_area']) ? (float) $unit['storage_area'] : null,
                'private_yard_area' => isset($unit['private_yard_area']) ? (float) $unit['private_yard_area'] : null,
                'parking_spaces' => (int) ($unit['parking_spaces'] ?? $unit['parking'] ?? 2),
                'completion_key' => (string) ($unit['completion'] ?? 'kevad 2027'),
                'floorplan_floor_1' => (string) ($unit['floorplan_1_pdf'] ?? ''),
                'floorplan_floor_2' => (string) ($unit['floorplan_2_pdf'] ?? ''),
                'asendiplaan_key' => $unit['masterplan_key'] ?? null,
                'featured' => false,
                'sort_order' => $index + 1,
                'internal_note' => null,
                'updated_by' => null,
            ];

            if ((int) $row['stage'] === 2 && $row['price_public'] === false) {
                // default preserved
            }

            $record = MagnooliaUnit::query()->where('unit_key', $unit['id'])->first();
            if (!$record) {
                MagnooliaUnit::query()->create(array_merge(['unit_key' => $unit['id']], $row));
                $created++;
                MagnooliaAuditLog::query()->create([
                    'admin_user_id' => null,
                    'action' => 'unit_created_from_seed',
                    'entity_type' => 'unit',
                    'entity_id' => $unit['id'],
                    'after_json' => json_encode($row, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    'created_at' => now(),
                ]);
            } else {
                $record->fill($row);
                $record->lock_version = ((int) $record->lock_version) + 1;
                $record->save();
                $updated++;
            }
        }

        $this->info("Import applied. Created: {$created}, updated: {$updated}.");
        $this->line('Total units in DB: ' . MagnooliaUnit::query()->count());

        return self::SUCCESS;
    }

    private function mapStatus(string $status): string
    {
        return match ($status) {
            'tbc' => 'coming_soon',
            'available', 'reserved', 'sold', 'coming_soon' => $status,
            default => 'coming_soon',
        };
    }
}
