<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesMagnooliaTestUnits;

/**
 * Phase 29 — Mobile UX guards and no source-path leaks on rendered pages.
 */
class MagnooliaPhase29MobileUxTest extends TestCase
{
    use RefreshDatabase;
    use CreatesMagnooliaTestUnits;

    protected function setUp(): void
    {
        parent::setUp();
        $this->create19TestUnits();
    }

    public function test_homes_page_has_mobile_card_fallback(): void
    {
        $html = $this->get('/kodud-ja-hinnad')->assertStatus(200)->getContent();
        $this->assertStringContainsString('d-lg-none', $html, 'Mobile card container must exist');
        $this->assertStringContainsString('mg-unit-card', $html);
    }

    public function test_modal_is_accessible_dialog(): void
    {
        // Modal lives on the homes page after Phase 30.
        $html = $this->get('/kodud-ja-hinnad')->assertStatus(200)->getContent();
        $this->assertStringContainsString('role="dialog"', $html);
        $this->assertStringContainsString('aria-modal="true"', $html);
        $this->assertStringContainsString('id="mg-hd-close"', $html);
    }

    public function test_no_source_or_onedrive_paths_leak(): void
    {
        foreach (['/asendiplaan', '/kodud-ja-hinnad', '/ehitusinfo', '/'] as $url) {
            $html = strtolower($this->get($url)->assertStatus(200)->getContent());
            $this->assertStringNotContainsString('materials/phase29', $html, "source path leaked on {$url}");
            $this->assertStringNotContainsString('onedrive', $html, "onedrive leaked on {$url}");
            $this->assertStringNotContainsString('2a.png', $html, "colour mask leaked on {$url}");
        }
    }
}
