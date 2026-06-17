<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Phase 30.1 — Regression guard: the homepage + homes table must list all 19
 * homes from canonical data even when NO publication/units exist (the
 * "Näitan 0 kodu" bug). Intentionally does NOT seed any units.
 */
class MagnooliaPhase30NoZeroHomesTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_lists_19_homes_without_publication(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        $this->assertStringContainsString('data-total="19"', $html, 'Homepage table must show 19 homes, not 0');
        $this->assertStringContainsString('129.6 m²', $html, 'Canonical facts must render without a publication');
        $this->assertStringNotContainsString('Näitan 0 kodu', $html);
    }

    public function test_homes_page_lists_19_homes_without_publication(): void
    {
        $html = $this->get('/kodud-ja-hinnad')->assertStatus(200)->getContent();
        $this->assertStringContainsString('data-total="19"', $html, 'Homes table must show 19 homes, not 0');
        $this->assertStringContainsString('Magnoolia tee 11/3', $html);
    }

    public function test_availability_board_lists_19_without_publication(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        $this->assertStringContainsString('id="saadavus"', $html);
        $this->assertStringContainsString('Magnoolia tee 1/1', $html);
        $this->assertStringContainsString('Magnoolia tee 11/3', $html);
    }

    public function test_config_fallback_shows_varied_statuses(): void
    {
        // With no publication, statuses come from the canonical config (4 reserved,
        // 1 sold, rest available) so localhost mirrors the approved status list.
        // Assert ACTUAL home rows carry the statuses (data-status), not merely that
        // the filter-chip labels appear in the markup.
        $html = $this->get('/kodud-ja-hinnad')->assertStatus(200)->getContent();
        $this->assertStringContainsString('data-status="available"', $html, 'Available home rows must render');
        $this->assertStringContainsString('data-status="reserved"', $html, 'Reserved home rows must render');
        $this->assertStringContainsString('data-status="sold"', $html, 'Sold home row must render');
    }
}
