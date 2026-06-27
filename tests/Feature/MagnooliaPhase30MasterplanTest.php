<?php

namespace Tests\Feature;

use App\Services\Magnoolia\RowhouseSelectionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesMagnooliaTestUnits;

/**
 * Phase 30 — Interactive perspective masterplan (/asendiplaan rebuild).
 */
class MagnooliaPhase30MasterplanTest extends TestCase
{
    use RefreshDatabase;
    use CreatesMagnooliaTestUnits;

    protected function setUp(): void
    {
        parent::setUp();
        $this->create19TestUnits();
    }

    private function manifest(): array
    {
        $p = public_path('assets/magnoolia/rowhouse-selection/manifest.json');
        $this->assertFileExists($p);
        return json_decode((string) file_get_contents($p), true);
    }

    public function test_manifest_has_perspective_floorplans_and_hotspots(): void
    {
        $m = $this->manifest();
        $this->assertSame(30, $m['meta']['phase'] ?? null);
        $this->assertNotEmpty($m['perspective']['image']['base'] ?? null);
        foreach (['type-a', 'type-b'] as $t) {
            $this->assertStringEndsWith('.webp', $m['floorplans'][$t]['floor_1']['base'] ?? '');
            $this->assertStringEndsWith('.webp', $m['floorplans'][$t]['floor_2']['base'] ?? '');
            $this->assertFileExists(public_path($m['floorplans'][$t]['floor_1']['base']));
        }
        // every row carries a perspective hotspot (marker + hull)
        foreach ($m['rows'] as $r) {
            $this->assertNotNull($r['perspective']['marker'] ?? null, "Row {$r['building']} missing marker");
            $this->assertGreaterThanOrEqual(3, count($r['perspective']['hull'] ?? []), "Row {$r['building']} hull too small");
        }
    }

    public function test_service_exposes_perspective_and_floorplans(): void
    {
        $s = app(RowhouseSelectionService::class);
        $this->assertNotEmpty($s->perspectiveImage()['base'] ?? null);
        $this->assertNotEmpty($s->floorplansForType('type-a')['floor_1']['base'] ?? null);
        $this->assertNotEmpty($s->floorplansForType('type-b')['floor_2']['base'] ?? null);

        // homes use per-UNIT floor plans (per-building remains a fallback method)
        $this->assertNotEmpty($s->floorplansForBuilding(3)['floor_1']['base'] ?? null);
        $h1 = $s->findHome('tee-1-2');
        $h3 = $s->findHome('tee-3-4');
        $this->assertStringContainsString('unit-tee-1-2-', $h1['floorplans']['floor_1']['base']);
        $this->assertStringContainsString('unit-tee-3-4-', $h3['floorplans']['floor_1']['base']);
        // every home has its own per-unit floor plan
        foreach ($s->allHomes() as $home) {
            $this->assertStringContainsString('unit-', $home['floorplans']['floor_1']['base'] ?? '', "{$home['asset_key']} missing per-unit floor plan");
        }
    }

    public function test_asendiplaan_renders_primary_perspective_masterplan(): void
    {
        $html = $this->get('/asendiplaan')->assertStatus(200)->getContent();
        $this->assertStringContainsString('id="mg-masterplan"', $html);
        $this->assertStringContainsString('mg-mp__img', $html, 'Perspective render image must be the primary visual');
        $this->assertStringContainsString('id="mg-mp-detail"', $html);
        // Phase 35: both floor plans are shown together (no tab toggle).
        $this->assertStringContainsString('id="mg-d-floor1-fig"', $html, 'Floor 1 plan figure must exist');
        $this->assertStringContainsString('id="mg-d-floor2-fig"', $html, 'Floor 2 plan figure must exist');
        // Row cards (no-JS / mobile fallback) remain for every row.
        foreach (['tee-1', 'tee-3', 'tee-5', 'tee-7', 'tee-9', 'tee-11'] as $pos) {
            $this->assertStringContainsString('data-mp-row="' . $pos . '"', $html);
        }
        // Phase 35: the main view is sliced into per-HOME boxes (boxes-only). Map
        // zones/markers are rendered client-side from the embedded box hotspots.
        $this->assertStringContainsString('"mode":"home"', $html, 'Main view must use per-home box hotspots');
        foreach (['tee-1-1', 'tee-3-4', 'tee-11-3'] as $homeKey) {
            $this->assertStringContainsString('"key":"' . $homeKey . '"', $html);
        }
        // all 19 homes embedded for client-side rendering (each appears in the rows
        // payload AND the per-home box hotspots → 38 occurrences of "key":"tee-").
        $this->assertSame(38, substr_count($html, '"key":"tee-'));
    }

    public function test_deeplink_row_and_home_render(): void
    {
        $html = $this->get('/asendiplaan?row=tee-3&home=tee-3-2')->assertStatus(200)->getContent();
        $this->assertStringContainsString('"key":"tee-3-2"', $html);
        $this->assertStringContainsString('id="mg-masterplan"', $html);
    }

    public function test_no_price_cents_on_masterplan(): void
    {
        $html = $this->get('/asendiplaan')->assertStatus(200)->getContent();
        $this->assertStringNotContainsString('price_cents', $html);
    }

    public function test_masterplan_title_in_three_locales(): void
    {
        $this->assertStringContainsString('Kõik 19 kodu ühel vaatel', $this->get('/asendiplaan')->getContent());
        $this->assertStringContainsString('Все 19 домов на одном виде', $this->get('/ru/asendiplaan')->getContent());
        $this->assertStringContainsString('All 19 homes in one view', $this->get('/en/asendiplaan')->getContent());
    }

    public function test_tee_3_4_land_area_conflict_resolved(): void
    {
        // Canonical source (asendiplaan / Phase 30 TZ) = 756.4 m², not 841.5.
        $home = app(RowhouseSelectionService::class)->findHome('tee-3-4');
        $this->assertEqualsWithDelta(756.4, (float) $home['private_yard_area'], 0.01);
    }

    public function test_homepage_board_lists_homes_with_canonical_facts(): void
    {
        // Homepage must keep the per-home availability list with statuses, and show
        // canonical facts (129.6 m² / Plaan A·B), not the raw live placeholder areas.
        $html = $this->get('/')->assertStatus(200)->getContent();
        $this->assertStringContainsString('id="saadavus"', $html, 'Homepage availability list must be present');
        $this->assertStringContainsString('129.6 m²', $html, 'Board must show canonical net area');
        $this->assertStringContainsString('Plaan B', $html, 'Board must show canonical plan label');
        $this->assertStringContainsString('data-mg-inquiry-open', $html, 'Board CTAs must open the inquiry drawer');
        $this->assertStringContainsString('Magnoolia tee 11/3', $html, 'All homes must be listed');
        $this->assertStringNotContainsString('price_cents', $html);
    }

    public function test_view_switcher_and_polish_present(): void
    {
        // Phase 30.1: 3-view switcher, perspective note, "your choice" map label.
        $html = $this->get('/asendiplaan')->assertStatus(200)->getContent();
        $this->assertStringContainsString('data-mp-view="0"', $html);
        $this->assertStringContainsString('data-mp-view="1"', $html);
        $this->assertStringContainsString('data-mp-view="2"', $html, 'Expected 3 perspective views');
        $this->assertStringContainsString('data-mp-view-prev', $html, 'View arrows must exist');
        $this->assertStringContainsString('id="mg-mp-lightbox"', $html, 'Floor-plan lightbox must exist');
        $this->assertStringContainsString('Üldvaade', $html);
        $this->assertStringContainsString('Sinu valik', $html);
    }

    public function test_perspective_views_in_manifest_only_primary_calibrated(): void
    {
        $views = app(RowhouseSelectionService::class)->perspectiveViews();
        $this->assertGreaterThanOrEqual(2, count($views));
        // Phase 35: the daylight secondary render (3.jpg) is the main/default view
        // ("Üldvaade", config-driven hotspots); the mask-calibrated 1.jpg render is
        // the second view ("Teine vaade"). Exactly the 'primary'-key view is the
        // mask-calibrated one.
        $this->assertSame('secondary', $views[0]['key'] ?? null, 'Main view must be the secondary daylight render');
        $this->assertSame('view_primary', $views[0]['label'] ?? null, 'Main view must be labelled Üldvaade');
        $calibrated = array_values(array_filter($views, fn ($v) => ($v['hotspots'] ?? false)));
        $this->assertCount(1, $calibrated, 'Exactly one view is mask-calibrated');
        $this->assertSame('primary', $calibrated[0]['key'] ?? null);
    }

    public function test_no_source_or_mask_leak_on_masterplan(): void
    {
        $html = strtolower($this->get('/asendiplaan')->getContent());
        $this->assertStringNotContainsString('materials/phase29', $html);
        $this->assertStringNotContainsString('2a.png', $html);
        $this->assertStringNotContainsString('onedrive', $html);
    }
}
