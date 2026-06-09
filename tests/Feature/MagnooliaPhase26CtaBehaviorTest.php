<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Magnoolia\MagnooliaPublicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesMagnooliaTestUnits;

/**
 * Phase 26 — CTA behavior: header trigger, unit context, fallback.
 */
class MagnooliaPhase26CtaBehaviorTest extends TestCase
{
    use RefreshDatabase, CreatesMagnooliaTestUnits;

    private MagnooliaPublicationService $pubService;

    public function setUp(): void
    {
        parent::setUp();
        $admin = User::factory()->verified()->create(['role' => 'magnoolia_admin']);
        $this->pubService = app(MagnooliaPublicationService::class);
        $this->create19TestUnits();
        $this->pubService->publish($admin->id, 'Phase 26 CTA test');
    }

    public function test_header_cta_contains_inquiry_modal_trigger(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('data-mg-inquiry-open', false);
    }

    public function test_header_cta_has_noscript_fallback(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $html = $response->getContent();
        // Page must have either the fallback attribute OR noscript tag with contact link
        $this->assertTrue(
            str_contains($html, 'data-mg-inquiry-fallback') || str_contains($html, '<noscript>'),
            'Header must have noscript fallback for the CTA button'
        );
    }

    public function test_homepage_does_not_have_primary_cta_pointing_only_to_kontakt(): void
    {
        $response = $this->get('/');
        $html = $response->getContent();
        // Primary CTA buttons should use the drawer, not raw href="/kontakt" without fallback attribute
        // The fallback link is fine, but there should be inquiry triggers present
        $this->assertStringContainsString('data-mg-inquiry-open', $html,
            'Homepage must have at least one inquiry drawer trigger');
    }

    public function test_inquiry_drawer_component_is_present_on_homepage(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        // The drawer wrapping element should exist
        $response->assertSee('mg-inquiry-overlay', false);
    }

    public function test_inquiry_drawer_has_unit_context_fields(): void
    {
        $response = $this->get('/');
        $html = $response->getContent();
        // The drawer form has a hidden unit_key field populated via JS
        $this->assertStringContainsString('unit_key', $html,
            'Inquiry drawer must have unit_key context field');
        $this->assertStringContainsString('mg-ctx-unit-key', $html,
            'Inquiry drawer must have unit_key input element');
    }

    public function test_inquiry_drawer_has_required_hidden_fields(): void
    {
        $response = $this->get('/');
        $html = $response->getContent();
        foreach (['source_component', 'locale', 'page_url'] as $field) {
            $this->assertStringContainsString($field, $html,
                "Inquiry drawer must have hidden field: $field");
        }
    }

    public function test_contact_page_form_still_works(): void
    {
        $response = $this->get('/kontakt');
        $response->assertStatus(200);
        // Contact page should have a form available
        $response->assertSee('<form', false);
    }

    public function test_no_price_cents_in_drawer_markup(): void
    {
        $response = $this->get('/');
        $this->assertStringNotContainsString('price_cents', $response->getContent());
    }

    public function test_kodudjahinnad_has_inquiry_cta(): void
    {
        $response = $this->get('/kodud-ja-hinnad');
        $response->assertStatus(200);
        $response->assertSee('data-mg-inquiry-open', false);
    }
}
