<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Magnoolia\MagnooliaPublicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesMagnooliaTestUnits;

/**
 * Phase 27 — Mobile navigation drawer: presence, links, CTA, language switch.
 */
class MagnooliaPhase27MobileNavigationTest extends TestCase
{
    use RefreshDatabase, CreatesMagnooliaTestUnits;

    protected function setUp(): void
    {
        parent::setUp();
        $admin = User::factory()->verified()->create(['role' => 'magnoolia_admin']);
        $this->create19TestUnits();
        app(MagnooliaPublicationService::class)->publish($admin->id, 'Phase 27 mobile nav test');
    }

    public function test_mobile_nav_drawer_present_in_html(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        $this->assertStringContainsString('mg-mobile-nav', $html, 'Mobile nav drawer must be present');
    }

    public function test_mobile_nav_has_burger_toggle(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        $this->assertStringContainsString('data-nav-toggle', $html, 'Must have mobile nav toggle');
    }

    public function test_mobile_nav_includes_all_main_pages(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();

        $requiredLinks = [
            '/kodud-ja-hinnad',
            '/asendiplaan',
            '/asukoht',
            '/kontakt',
        ];

        foreach ($requiredLinks as $link) {
            $this->assertStringContainsString(
                $link,
                $html,
                "Mobile nav must include link to $link"
            );
        }
    }

    public function test_mobile_nav_has_inquiry_cta(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        $this->assertStringContainsString(
            'mobile_nav_cta',
            $html,
            'Mobile nav must include inquiry CTA with mobile_nav_cta source'
        );
    }

    public function test_mobile_nav_has_diana_phone(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        $this->assertTrue(
            str_contains($html, '58164078') || str_contains($html, '58 16 40 78'),
            'Mobile nav must include Diana phone number'
        );
    }

    public function test_mobile_nav_close_button_present(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        $this->assertStringContainsString(
            'mg-mobile-nav__close',
            $html,
            'Mobile nav must have close button'
        );
    }

    public function test_mobile_nav_has_no_placeholder_social_links(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();

        // The old mobile menu had dummy social links - these must not appear
        $this->assertStringNotContainsString(
            'href="https://facebook.com"',
            $html,
            'Must not have generic facebook.com placeholder link'
        );
        $this->assertStringNotContainsString(
            'href="https://x.com"',
            $html,
            'Must not have generic x.com placeholder link'
        );
        $this->assertStringNotContainsString(
            'href="https://google.com"',
            $html,
            'Must not have generic google.com placeholder link'
        );
    }

    public function test_mobile_nav_present_on_all_locales(): void
    {
        foreach (['/', '/ru', '/en'] as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            $this->assertStringContainsString(
                'mg-mobile-nav',
                $html,
                "Mobile nav must be present on $url"
            );
        }
    }
}
