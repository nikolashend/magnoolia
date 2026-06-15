<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesMagnooliaTestUnits;

/**
 * Phase 29 — Home-detail CTA carries selected-home context, analytics
 * preserved, and no hidden price leaks.
 */
class MagnooliaPhase29CtaContextTest extends TestCase
{
    use RefreshDatabase;
    use CreatesMagnooliaTestUnits;

    protected function setUp(): void
    {
        parent::setUp();
        $this->create19TestUnits();
    }

    public function test_modal_cta_reuses_inquiry_drawer_with_context(): void
    {
        // Modal now lives on the homes page (Phase 30 moved selection to the masterplan).
        $html = $this->get('/kodud-ja-hinnad')->assertStatus(200)->getContent();
        $this->assertStringContainsString('data-source-component="home_detail_modal"', $html);
        $this->assertStringContainsString('id="mg-hd-cta"', $html);
        $this->assertStringContainsString('data-mg-inquiry-open', $html);
    }

    public function test_asendiplaan_inline_cta_reuses_inquiry_drawer(): void
    {
        $html = $this->get('/asendiplaan')->assertStatus(200)->getContent();
        $this->assertStringContainsString('data-source-component="asendiplaan_home_detail"', $html);
        $this->assertStringContainsString('id="mg-d-cta"', $html);
        $this->assertStringContainsString('data-mg-inquiry-open', $html);
    }

    public function test_analytics_attributes_preserved(): void
    {
        $html = $this->get('/asendiplaan')->assertStatus(200)->getContent();
        $this->assertStringContainsString('data-mg-analytics', $html, 'CTA analytics attributes must be preserved');
    }

    public function test_no_price_cents_on_selection_surfaces(): void
    {
        foreach (['/asendiplaan', '/kodud-ja-hinnad', '/'] as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            $this->assertStringNotContainsString('price_cents', $html, "price_cents leaked on {$url}");
        }
    }
}
