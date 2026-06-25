<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Phase 34 — final launch gate for the public website: every page renders in
 * ET/RU/EN with canonical + hreflang, no unresolved i18n keys, security headers,
 * robots blocks admin, and the new legal pages are reachable.
 */
class MagnooliaPhase34FinalLaunchTest extends TestCase
{
    use RefreshDatabase;

    private const PATHS = [
        '/', '/kodud-ja-hinnad', '/asendiplaan', '/asukoht', '/ehitusinfo',
        '/sisedisain', '/arhitektuur-ja-valisdisain', '/galerii', '/ostuprotsess',
        '/finantseerimine', '/kkk', '/kontakt',
    ];

    private function localePath(string $loc, string $p): string
    {
        if ($loc === 'et') return $p;
        return '/' . $loc . ($p === '/' ? '' : $p);
    }

    public function test_all_public_pages_render_200_in_all_locales(): void
    {
        foreach (['et', 'ru', 'en'] as $loc) {
            foreach (self::PATHS as $p) {
                $this->get($this->localePath($loc, $p))->assertOk();
            }
        }
    }

    public function test_pages_have_canonical_and_hreflang_and_single_h1(): void
    {
        foreach (['/', '/kodud-ja-hinnad', '/galerii'] as $p) {
            $html = $this->get($p)->assertOk()->getContent();
            $this->assertStringContainsString('rel="canonical"', $html);
            $this->assertStringContainsString('hreflang="et"', $html);
            $this->assertStringContainsString('hreflang="ru"', $html);
            $this->assertStringContainsString('hreflang="en"', $html);
            $this->assertSame(1, substr_count($html, '<h1'), "exactly one <h1> on {$p}");
        }
    }

    public function test_no_unresolved_translation_keys_on_public_pages(): void
    {
        foreach (['et', 'ru', 'en'] as $loc) {
            foreach (['/', '/kodud-ja-hinnad', '/ehitusinfo', '/kontakt'] as $p) {
                $html = $this->get($this->localePath($loc, $p))->assertOk()->getContent();
                $this->assertDoesNotMatchRegularExpression(
                    '/magnoolia\.(page|nav|hero|footer|section|pricing|faq|forms|contact)\.[a-z0-9_]+/',
                    $html,
                    "unresolved lang key on {$loc}{$p}"
                );
            }
        }
    }

    public function test_legal_pages_render_in_all_locales(): void
    {
        foreach (['/privaatsus', '/tingimused', '/ru/privaatsus', '/en/tingimused'] as $p) {
            $this->get($p)->assertOk();
        }
    }

    public function test_security_headers_present(): void
    {
        $res = $this->get('/');
        $res->assertHeader('X-Content-Type-Options', 'nosniff');
        $res->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $res->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    public function test_robots_blocks_admin_and_sitemap_excludes_admin(): void
    {
        // Route-served robots (config noindex=false path) blocks /admin.
        config(['magnoolia.seo.noindex' => false]);
        $robots = $this->get('/robots.txt')->assertOk()->getContent();
        $this->assertStringContainsString('Disallow: /admin', $robots);

        $xml = $this->get('/sitemap.xml')->assertOk()->getContent();
        $this->assertStringNotContainsString('/admin', $xml);
    }
}
