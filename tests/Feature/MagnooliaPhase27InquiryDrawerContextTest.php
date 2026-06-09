<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Magnoolia\MagnooliaPublicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesMagnooliaTestUnits;

/**
 * Phase 27 — Inquiry drawer required hidden fields and context.
 */
class MagnooliaPhase27InquiryDrawerContextTest extends TestCase
{
    use RefreshDatabase, CreatesMagnooliaTestUnits;

    protected function setUp(): void
    {
        parent::setUp();
        $admin = User::factory()->verified()->create(['role' => 'magnoolia_admin']);
        $this->create19TestUnits();
        app(MagnooliaPublicationService::class)->publish($admin->id, 'Phase 27 drawer test');
    }

    public function test_inquiry_drawer_present_on_homepage(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        $this->assertStringContainsString('mg-inquiry-overlay', $html, 'Inquiry drawer overlay must be present');
    }

    public function test_inquiry_drawer_has_unit_key_field(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        $this->assertStringContainsString('unit_key', $html, 'Drawer must have unit_key field');
        $this->assertStringContainsString('mg-ctx-unit-key', $html, 'Drawer must have mg-ctx-unit-key input');
    }

    public function test_inquiry_drawer_has_locale_field(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        $this->assertStringContainsString('name="locale"', $html, 'Drawer form must have locale hidden field');
    }

    public function test_inquiry_drawer_has_source_component_field(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        $this->assertStringContainsString('source_component', $html, 'Drawer must have source_component field');
    }

    public function test_inquiry_drawer_has_published_version_field(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        $this->assertStringContainsString('published_version', $html, 'Drawer must have published_version field');
    }

    public function test_inquiry_drawer_has_no_price_cents(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        // Drawer must never expose price_cents
        $drawerStart = strpos($html, 'mg-inquiry-overlay');
        $drawerEnd   = $drawerStart !== false ? strpos($html, '</form>', $drawerStart) : false;

        if ($drawerStart !== false && $drawerEnd !== false) {
            $drawerHtml = substr($html, $drawerStart, $drawerEnd - $drawerStart);
            $this->assertStringNotContainsString(
                'price_cents',
                $drawerHtml,
                'Inquiry drawer must not expose price_cents'
            );
        }

        // Also check globally
        $this->assertStringNotContainsString(
            'price_cents',
            $html,
            'Rendered HTML must not contain price_cents anywhere'
        );
    }

    public function test_inquiry_drawer_has_consent_checkbox(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        $this->assertStringContainsString(
            'type="checkbox"',
            $html,
            'Inquiry drawer must have consent checkbox'
        );
    }

    public function test_inquiry_drawer_available_on_all_locales(): void
    {
        foreach (['/', '/ru', '/en'] as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            $this->assertStringContainsString(
                'mg-inquiry-overlay',
                $html,
                "Inquiry drawer must be present on $url"
            );
        }
    }

    public function test_no_admin_ids_in_drawer(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        // Admin user IDs must not appear in the drawer context
        $this->assertStringNotContainsString('admin_id', $html);
        $this->assertStringNotContainsString('updated_by', $html);
        $this->assertStringNotContainsString('internal_note', $html);
    }
}
