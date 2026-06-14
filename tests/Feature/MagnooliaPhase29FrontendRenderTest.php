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
        $html = $this->get('/asendiplaan')->assertStatus(200)->getContent();
        $this->assertStringContainsString('id="mg-rowhouse"', $html);
        foreach ([1, 3, 5, 7, 9, 11] as $b) {
            $this->assertStringContainsString('data-mg-row-marker="' . $b . '"', $html, "Missing row marker {$b}");
            $this->assertStringContainsString('data-mg-row="' . $b . '"', $html, "Missing row card {$b}");
        }
    }

    public function test_asendiplaan_has_home_detail_triggers(): void
    {
        $html = $this->get('/asendiplaan')->assertStatus(200)->getContent();
        $count = substr_count($html, 'data-mg-home-open=');
        $this->assertGreaterThanOrEqual(19, $count, 'Expected at least 19 home-detail triggers');
        $this->assertStringContainsString('id="mg-hd-overlay"', $html, 'Home-detail modal must be present');
    }

    public function test_homes_page_renders_row_filter_and_all_homes(): void
    {
        $html = $this->get('/kodud-ja-hinnad')->assertStatus(200)->getContent();
        foreach ([1, 3, 5, 7, 9, 11] as $b) {
            $this->assertStringContainsString('data-filter="tee-' . $b . '"', $html, "Missing tee filter {$b}");
        }
        // 19 table triggers + 19 mobile-card triggers
        $this->assertGreaterThanOrEqual(19, substr_count($html, 'data-mg-home-open='));
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
