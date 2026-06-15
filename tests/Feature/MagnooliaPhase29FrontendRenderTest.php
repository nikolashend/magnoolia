<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesMagnooliaTestUnits;

/**
 * Phase 29 — Frontend render: selector on /asendiplaan, filter + cards on
 * /kodud-ja-hinnad, home-detail triggers.
 */
class MagnooliaPhase29FrontendRenderTest extends TestCase
{
    use RefreshDatabase;
    use CreatesMagnooliaTestUnits;

    protected function setUp(): void
    {
        parent::setUp();
        $this->create19TestUnits();
    }

    public function test_asendiplaan_renders_row_selector_and_all_rows(): void
    {
        // Phase 30: the primary selector is the perspective masterplan.
        $html = $this->get('/asendiplaan')->assertStatus(200)->getContent();
        $this->assertStringContainsString('id="mg-masterplan"', $html);
        foreach (['tee-1', 'tee-3', 'tee-5', 'tee-7', 'tee-9', 'tee-11'] as $pos) {
            $this->assertStringContainsString('data-mp-row="' . $pos . '"', $html, "Missing row control {$pos}");
            $this->assertStringContainsString('data-mp-zone="' . $pos . '"', $html, "Missing render hotspot {$pos}");
        }
    }

    public function test_asendiplaan_has_home_detail_section_and_homes(): void
    {
        // Phase 30: home cards are rendered client-side from embedded JSON; the
        // inline detail section + all 19 homes must be present in the payload.
        $html = $this->get('/asendiplaan')->assertStatus(200)->getContent();
        $this->assertStringContainsString('id="mg-mp-detail"', $html, 'Inline home-detail section must exist');
        foreach (['tee-1-1', 'tee-3-4', 'tee-11-3'] as $key) {
            $this->assertStringContainsString('"key":"' . $key . '"', $html, "Home {$key} missing from masterplan data");
        }
    }

    public function test_kodud_page_has_home_detail_modal(): void
    {
        $html = $this->get('/kodud-ja-hinnad')->assertStatus(200)->getContent();
        $this->assertStringContainsString('id="mg-hd-overlay"', $html, 'Home-detail modal must be present on homes page');
        // Rows/cards open the detail modal via mgOpenHome(...) (no separate button).
        $this->assertGreaterThanOrEqual(19, substr_count($html, 'mgOpenHome('));
    }

    public function test_homes_page_renders_row_filter_and_all_homes(): void
    {
        $html = $this->get('/kodud-ja-hinnad')->assertStatus(200)->getContent();
        foreach ([1, 3, 5, 7, 9, 11] as $b) {
            $this->assertStringContainsString('data-filter="tee-' . $b . '"', $html, "Missing tee filter {$b}");
        }
        // Rows + mobile cards open the detail modal via mgOpenHome(...)
        $this->assertGreaterThanOrEqual(19, substr_count($html, 'mgOpenHome('));
    }

    public function test_homes_page_shows_private_land_area_and_status(): void
    {
        $html = $this->get('/kodud-ja-hinnad')->assertStatus(200)->getContent();
        // canonical private-use land area (decimal comma) is visible
        $this->assertStringContainsString('959,7', $html, 'Private land area 959,7 must be visible');
        // status chips
        $this->assertStringContainsString('mg-status', $html);
    }
}
