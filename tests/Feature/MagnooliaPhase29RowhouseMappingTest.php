<?php

namespace Tests\Feature;

use App\Services\Magnoolia\RowhouseSelectionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesMagnooliaTestUnits;

/**
 * Phase 29 — Row → home mapping correctness (service level).
 */
class MagnooliaPhase29RowhouseMappingTest extends TestCase
{
    use RefreshDatabase;
    use CreatesMagnooliaTestUnits;

    protected function setUp(): void
    {
        parent::setUp();
        $this->create19TestUnits();
    }

    private function service(): RowhouseSelectionService
    {
        return app(RowhouseSelectionService::class);
    }

    public function test_six_rows_and_nineteen_homes(): void
    {
        $rows = $this->service()->rows();
        $this->assertCount(6, $rows);
        $this->assertSame(19, array_sum(array_map(fn ($r) => $r['home_count'], $rows)));
    }

    public function test_pos_mapping_and_counts(): void
    {
        $rows = collect($this->service()->rows())->keyBy('building');
        $this->assertEqualsCanonicalizing([1, 3, 5, 7, 9, 11], $rows->keys()->all());
        $this->assertSame(4, $rows[3]['home_count'], 'Magnoolia tee 3 must have 4 homes');
        foreach ([1, 5, 7, 9, 11] as $b) {
            $this->assertSame(3, $rows[$b]['home_count'], "Magnoolia tee {$b} must have 3 homes");
        }
    }

    public function test_no_zero_homes_regression(): void
    {
        foreach ($this->service()->rows() as $row) {
            $this->assertGreaterThan(0, $row['home_count'], "Row tee {$row['building']} has 0 homes");
        }
    }

    public function test_stage_distribution_seven_and_twelve(): void
    {
        $homes = $this->service()->allHomes();
        $this->assertSame(7, count(array_filter($homes, fn ($h) => $h['stage'] === 1)));
        $this->assertSame(12, count(array_filter($homes, fn ($h) => $h['stage'] === 2)));
    }

    public function test_private_land_areas_match_canonical(): void
    {
        $svc = $this->service();
        $cases = [
            'tee-1-1'  => 959.7,
            'tee-3-1'  => 509.5,
            'tee-3-4'  => 756.4,
            'tee-11-3' => 872.1,
        ];
        foreach ($cases as $key => $expected) {
            $home = $svc->findHome($key);
            $this->assertNotNull($home, "Home {$key} not found");
            $this->assertEqualsWithDelta($expected, (float) $home['private_yard_area'], 0.01,
                "Private land area mismatch for {$key}");
        }
    }

    public function test_homes_never_expose_price_cents(): void
    {
        foreach ($this->service()->allHomes() as $home) {
            $this->assertArrayNotHasKey('price_cents', $home);
            $this->assertArrayNotHasKey('price_cents', $home['cta_context']);
        }
    }
}
