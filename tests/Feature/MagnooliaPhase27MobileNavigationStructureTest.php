<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Magnoolia\MagnooliaPublicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesMagnooliaTestUnits;

/**
 * Phase 27 — Navigation structure: routes, language switching, breadcrumbs.
 */
class MagnooliaPhase27MobileNavigationStructureTest extends TestCase
{
    use RefreshDatabase, CreatesMagnooliaTestUnits;

    protected function setUp(): void
    {
        parent::setUp();
        $admin = User::factory()->verified()->create(['role' => 'magnoolia_admin']);
        $this->create19TestUnits();
        app(MagnooliaPublicationService::class)->publish($admin->id, 'Phase 27 nav structure test');
    }

    public function test_all_et_routes_accessible(): void
    {
        $routes = [
            '/', '/kodud-ja-hinnad', '/asendiplaan', '/asukoht',
            '/ehitusinfo', '/sisedisain', '/arhitektuur-ja-valisdisain',
            '/galerii', '/finantseerimine', '/ostuprotsess', '/kkk', '/kontakt',
        ];
        foreach ($routes as $url) {
            $this->get($url)->assertStatus(200, "ET route $url must return 200");
        }
    }

    public function test_all_ru_routes_accessible(): void
    {
        $routes = [
            '/ru', '/ru/kodud-ja-hinnad', '/ru/asendiplaan', '/ru/asukoht',
            '/ru/ehitusinfo', '/ru/sisedisain', '/ru/arhitektuur-ja-valisdisain',
            '/ru/galerii', '/ru/finantseerimine', '/ru/ostuprotsess', '/ru/kkk', '/ru/kontakt',
        ];
        foreach ($routes as $url) {
            $this->get($url)->assertStatus(200, "RU route $url must return 200");
        }
    }

    public function test_all_en_routes_accessible(): void
    {
        $routes = [
            '/en', '/en/kodud-ja-hinnad', '/en/asendiplaan', '/en/asukoht',
            '/en/ehitusinfo', '/en/sisedisain', '/en/arhitektuur-ja-valisdisain',
            '/en/galerii', '/en/finantseerimine', '/en/ostuprotsess', '/en/kkk', '/en/kontakt',
        ];
        foreach ($routes as $url) {
            $this->get($url)->assertStatus(200, "EN route $url must return 200");
        }
    }

    public function test_homepage_contains_nav_links(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        // Must have links to main sections
        $this->assertStringContainsString('/kodud-ja-hinnad', $html);
        $this->assertStringContainsString('/kontakt', $html);
    }

    public function test_header_logo_links_to_home(): void
    {
        $html = $this->get('/kodud-ja-hinnad')->assertStatus(200)->getContent();
        // Either the new site-header__logo or the legacy main-header__logo
        $this->assertTrue(
            str_contains($html, 'site-header__logo') || str_contains($html, 'main-header__logo'),
            'Header must have a logo link element'
        );
    }

    public function test_language_switcher_present(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        $this->assertTrue(
            str_contains($html, '/ru') && str_contains($html, '/en'),
            'Homepage must include language switcher links (ru and en)'
        );
    }
}
