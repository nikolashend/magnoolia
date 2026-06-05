<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class MagnooliaPhase23RealDataIntegrationTest extends TestCase
{
    public function test_canonical_units_have_expected_count_and_distribution(): void
    {
        $units = require config_path('magnoolia_units.php');

        $this->assertCount(19, $units, 'Magnoolia must expose exactly 19 canonical units.');

        $byBuilding = [];
        foreach ($units as $unit) {
            $building = $unit['building'] ?? null;
            $byBuilding[$building] = ($byBuilding[$building] ?? 0) + 1;
        }

        $this->assertSame([
            'Magnoolia tee 1' => 3,
            'Magnoolia tee 3' => 4,
            'Magnoolia tee 5' => 3,
            'Magnoolia tee 7' => 3,
            'Magnoolia tee 9' => 3,
            'Magnoolia tee 11' => 3,
        ], $byBuilding);
    }

    public function test_stage_two_prices_are_not_public_in_config(): void
    {
        $units = require config_path('magnoolia_units.php');

        foreach ($units as $unit) {
            if ((int) ($unit['stage'] ?? 0) === 2) {
                $this->assertFalse((bool) ($unit['price_public'] ?? true), 'Stage II units must not be public-priced.');
            }
        }

        $this->assertFalse((bool) config('magnoolia.price_visibility.stage_2_public', true));
    }

    public function test_stage_two_prices_do_not_leak_in_homes_page_html(): void
    {
        $hinnad = file_get_contents(resource_path('views/sections/magnoolia/hinnad.blade.php')) ?: '';
        $modal = file_get_contents(resource_path('views/partials/unit-modal.blade.php')) ?: '';

        $this->assertStringContainsString('$publicPrice = ($unit[\'price_public\'] ?? false) ? ($unit[\'price\'] ?? null) : null;', $hinnad);
        $this->assertStringContainsString('$modalUnits = collect($mgPublic[\'units\'] ?? [])->map(function ($unit) {', $modal);
        $this->assertStringContainsString('if (!($unit[\'price_public\'] ?? false)) {', $modal);
        $this->assertStringContainsString('if (unit.price && unit.price_public)', $modal);
    }

    public function test_phase23_public_pages_do_not_reference_jaanika_or_jp_design(): void
    {
        foreach ([
            resource_path('views/pages/magnoolia/sisedisain.blade.php'),
            resource_path('views/pages/magnoolia/finantseerimine.blade.php'),
            resource_path('views/sections/magnoolia/contact.blade.php'),
            resource_path('views/pages/magnoolia/kodud-ja-hinnad.blade.php'),
        ] as $file) {
            $html = mb_strtolower((string) file_get_contents($file));

            $this->assertStringNotContainsString('jaanika', $html, $file . ' must not reference Jaanika publicly.');
            $this->assertStringNotContainsString('jp design', $html, $file . ' must not reference JP Design publicly.');
        }
    }

    public function test_floorplan_assets_exist_for_all_buildings(): void
    {
        foreach ([1, 3, 5, 7, 9, 11] as $building) {
            $floor1 = public_path("assets/magnoolia/floorplans/M{$building}_1korrus.pdf");
            $floor2 = public_path("assets/magnoolia/floorplans/M{$building}_2korrus.pdf");

            $this->assertFileExists($floor1);
            $this->assertFileExists($floor2);
        }
    }
}
