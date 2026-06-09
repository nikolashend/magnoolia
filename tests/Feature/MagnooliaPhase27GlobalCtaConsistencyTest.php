<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Magnoolia\MagnooliaPublicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesMagnooliaTestUnits;

/**
 * Phase 27 P0 — CTA consistency: data-mg-inquiry-open on all pages, noscript fallback.
 */
class MagnooliaPhase27GlobalCtaConsistencyTest extends TestCase
{
    use RefreshDatabase, CreatesMagnooliaTestUnits;

    private array $ctaPages;

    protected function setUp(): void
    {
        parent::setUp();
        $admin = User::factory()->verified()->create(['role' => 'magnoolia_admin']);
        $this->create19TestUnits();
        app(MagnooliaPublicationService::class)->publish($admin->id, 'Phase 27 CTA test');

        $this->ctaPages = [
            '/', '/kodud-ja-hinnad', '/asendiplaan',
            '/asukoht', '/galerii', '/kontakt',
        ];
    }

    public function test_all_pages_have_inquiry_open_trigger(): void
    {
        foreach ($this->ctaPages as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            $this->assertStringContainsString(
                'data-mg-inquiry-open',
                $html,
                "Page $url must have at least one data-mg-inquiry-open trigger"
            );
        }
    }

    public function test_all_pages_have_noscript_fallback(): void
    {
        foreach ($this->ctaPages as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            $this->assertTrue(
                str_contains($html, 'data-mg-inquiry-fallback') || str_contains($html, '<noscript>'),
                "Page $url must have noscript fallback for CTA"
            );
        }
    }

    public function test_header_cta_uses_drawer_open_attribute(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        $this->assertStringContainsString('data-mg-inquiry-open', $html);
        $this->assertStringContainsString('data-source-component="header_cta"', $html);
    }

    public function test_header_cta_is_a_button_element(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        $this->assertStringContainsString('<button', $html, 'Header must use a button for CTA');
    }

    public function test_mobile_nav_includes_inquiry_cta(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        $this->assertStringContainsString('mg-mobile-nav', $html, 'Mobile nav must be present');
        $this->assertStringContainsString('data-source-component="mobile_nav_cta"', $html);
    }

    public function test_inquiry_drawer_overlay_present(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        $this->assertStringContainsString('mg-inquiry-overlay', $html);
    }

    public function test_inquiry_drawer_has_unit_key_field(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        $this->assertStringContainsString('unit_key', $html);
        $this->assertStringContainsString('mg-ctx-unit-key', $html);
    }

    public function test_cta_noscript_points_to_kontakt(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        $this->assertTrue(
            str_contains($html, '/kontakt#kontaktivorm') || str_contains($html, '/kontakt"'),
            'Noscript CTA fallback must link to /kontakt page'
        );
    }
}
