<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Magnoolia\MagnooliaPublicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesMagnooliaTestUnits;

/**
 * Phase 27 — Rendered HTML audit: responsive meta, mobile CSS, structural checks.
 */
class MagnooliaPhase27MobileRenderedHtmlAuditTest extends TestCase
{
    use RefreshDatabase, CreatesMagnooliaTestUnits;

    protected function setUp(): void
    {
        parent::setUp();
        $admin = User::factory()->verified()->create(['role' => 'magnoolia_admin']);
        $this->create19TestUnits();
        app(MagnooliaPublicationService::class)->publish($admin->id, 'Phase 27 mobile html test');
    }

    /** @dataProvider allPagesProvider */
    public function test_viewport_meta_present(string $url): void
    {
        $html = $this->get($url)->assertStatus(200)->getContent();
        $this->assertStringContainsString(
            'name="viewport"',
            $html,
            "Page $url must have viewport meta tag"
        );
        $this->assertStringContainsString(
            'width=device-width',
            $html,
            "Page $url viewport must include width=device-width"
        );
    }

    /** @dataProvider allPagesProvider */
    public function test_magnoolia_css_loaded(string $url): void
    {
        $html = $this->get($url)->assertStatus(200)->getContent();
        $this->assertStringContainsString(
            'magnoolia.css',
            $html,
            "Page $url must load magnoolia.css"
        );
    }

    /** @dataProvider allPagesProvider */
    public function test_no_horizontal_overflow_indicator(string $url): void
    {
        $html = $this->get($url)->assertStatus(200)->getContent();
        $this->assertStringNotContainsString(
            'overflow-x: scroll',
            $html,
            "Page $url must not force horizontal scroll"
        );
    }

    public function test_homepage_has_hero_section(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        $this->assertTrue(
            str_contains($html, 'mg-hero') || str_contains($html, 'hero'),
            'Homepage must have a hero section'
        );
    }

    public function test_homepage_has_h1(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        $this->assertStringContainsString('<h1', $html, 'Homepage must have H1');
    }

    public function test_site_footer_present_on_all_pages(): void
    {
        $pages = ['/', '/kodud-ja-hinnad', '/asukoht', '/kontakt'];
        foreach ($pages as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            $this->assertStringContainsString(
                'site-footer',
                $html,
                "Page $url must have site-footer"
            );
        }
    }

    public function test_sticky_cta_css_class_present_on_homepage(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        // Mobile sticky CTA or pricing teaser CTA
        $this->assertTrue(
            str_contains($html, 'data-mg-inquiry-open') || str_contains($html, 'zoomvilla-btn'),
            'Homepage must have at least one primary CTA'
        );
    }

    public function test_no_broken_blade_sections(): void
    {
        // If a @yield has no @section for it, it outputs empty string, which is fine.
        // But @section without @endsection would cause parse error → 500.
        $pages = ['/', '/kodud-ja-hinnad', '/asendiplaan', '/sisedisain', '/galerii'];
        foreach ($pages as $url) {
            $this->get($url)->assertStatus(200);
        }
    }

    public function test_all_public_pages_return_200(): void
    {
        $pages = [
            '/', '/ru', '/en',
            '/kodud-ja-hinnad', '/asendiplaan', '/asukoht',
            '/ehitusinfo', '/sisedisain', '/arhitektuur-ja-valisdisain',
            '/galerii', '/finantseerimine', '/ostuprotsess', '/kkk', '/kontakt',
        ];
        foreach ($pages as $url) {
            $this->get($url)->assertStatus(200, "Page $url must return HTTP 200");
        }
    }

    public static function allPagesProvider(): array
    {
        return [
            'homepage'          => ['/'],
            'kodud-ja-hinnad'   => ['/kodud-ja-hinnad'],
            'asendiplaan'       => ['/asendiplaan'],
            'asukoht'           => ['/asukoht'],
            'galerii'           => ['/galerii'],
            'sisedisain'        => ['/sisedisain'],
            'ehitusinfo'        => ['/ehitusinfo'],
            'finantseerimine'   => ['/finantseerimine'],
            'ostuprotsess'      => ['/ostuprotsess'],
            'kkk'               => ['/kkk'],
            'kontakt'           => ['/kontakt'],
        ];
    }
}
