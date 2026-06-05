<?php

namespace Tests\Traits;

use App\Models\MagnooliaUnit;

/**
 * Provides a canonical set of 19 test units matching the required
 * building distribution (1: 3, 3: 4, 5: 3, 7: 3, 9: 3, 11: 3)
 * with valid floorplan paths for MagnooliaValidationService.
 */
trait CreatesMagnooliaTestUnits
{
    /**
     * Create the canonical 19 Magnoolia test units with valid building distribution.
     * Returns array of created units indexed by unit_key.
     */
    protected function create19TestUnits(): array
    {
        // Building distribution: 1=>3, 3=>4, 5=>3, 7=>3, 9=>3, 11=>3 = 19 total
        $schema = [
            [1, 1], [1, 2], [1, 3],           // Building 1: 3 units
            [3, 1], [3, 2], [3, 3], [3, 4],   // Building 3: 4 units
            [5, 1], [5, 2], [5, 3],            // Building 5: 3 units
            [7, 1], [7, 2], [7, 3],            // Building 7: 3 units
            [9, 1], [9, 2], [9, 3],            // Building 9: 3 units
            [11, 1], [11, 2], [11, 3],         // Building 11: 3 units
        ];

        $units = [];
        $sortOrder = 1;

        foreach ($schema as [$building, $section]) {
            $key = "B{$building}-S{$section}";
            $stage = $building <= 3 ? 1 : 2;
            $price = 150000 * $building + 10000 * $section;

            $unit = MagnooliaUnit::create([
                'unit_key' => $key,
                'slug' => strtolower(str_replace(['B', 'S'], ['b', 's'], $key)),
                'address' => "Magnoolia tee {$building}/{$section}",
                'building_number' => $building,
                'section_number' => $section,
                'stage' => $stage,
                'status' => 'available',
                'price_cents' => $price,
                'price_public' => $stage === 1, // Stage 2 prices hidden
                'is_visible' => true,
                'rooms' => 2 + ($section % 2),
                'net_area' => 55.0 + ($building + $section),
                'terrace_area' => null,
                'balcony_area' => 5.0,
                'storage_area' => null,
                'private_yard_area' => null,
                'parking_spaces' => 1,
                'completion_key' => $stage === 1 ? 'Q3-2026' : 'Q4-2027',
                'floorplan_floor_1' => "assets/magnoolia/floorplans/b{$building}/s{$section}-floor1.jpg",
                'floorplan_floor_2' => "assets/magnoolia/floorplans/b{$building}/s{$section}-floor2.jpg",
                'asendiplaan_key' => null,
                'featured' => $building === 1 && $section === 1,
                'sort_order' => $sortOrder++,
                'internal_note' => null,
                'updated_by' => null,
                'lock_version' => 0,
            ]);

            $units[$key] = $unit;
        }

        // Create fake asset files so file-existence validation passes
        foreach ($units as $unit) {
            foreach (['floorplan_floor_1', 'floorplan_floor_2'] as $field) {
                $path = public_path($unit->{$field});
                if (!is_dir(dirname($path))) {
                    @mkdir(dirname($path), 0755, true);
                }
                if (!file_exists($path)) {
                    file_put_contents($path, 'test-asset');
                }
            }
        }

        return $units;
    }

    /**
     * Update a single unit from the test set by unit_key.
     */
    protected function updateTestUnit(string $key, array $attributes): MagnooliaUnit
    {
        $unit = MagnooliaUnit::where('unit_key', $key)->firstOrFail();
        $unit->update($attributes);
        return $unit->fresh();
    }
}
