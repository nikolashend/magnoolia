<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

/**
 * Phase 35.0 — Google visibility + canonical domain recovery.
 *
 * Two production faults motivated this suite:
 *   1. every live page shipped `noindex,nofollow` because the indexing switch
 *      defaulted to "off" and nobody flipped it in the production .env;
 *   2. four hosts served the same site, so Google had no single canonical.
 *
 * Fault 1 is guarded here. Fault 2 is fixed at the Apache layer (public/.htaccess
 * + the hosting vhost), so it has no PHP to test; what these tests do assert is
 * that nothing the application emits — canonical, sitemap, public HTML — ever
 * names a non-canonical host.
 */
class MagnooliaPhase35CanonicalDomainTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The canonical base is configuration, not a constant: production may run with
     * magnoolia.ee canonical, or with magnoolia.estlanda.ee canonical while the
     * brand domain merely redirects. These tests assert the *invariant* — every
     * URL the site emits matches whatever is configured — so they hold either way.
     */
    private function canonical(): string
    {
        return rtrim((string) config('magnoolia.canonical_domain', config('magnoolia.seo.canonical_base')), '/');
    }

    // ── Indexability ────────────────────────────────────────────────────

    public function test_shipped_defaults_are_indexable(): void
    {
        // No config overrides: this is what a bare `git pull` + `config:cache`
        // produces on production. It must be index,follow.
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('index,follow', $html);
        $this->assertStringNotContainsString('noindex,nofollow', $html);
    }

    public function test_stale_legacy_noindex_cannot_deindex_production(): void
    {
        // A MAGNOOLIA_NOINDEX=true left behind from staging used to win outright.
        // The modern switch must now override it.
        Config::set('magnoolia.seo.noindex', true);

        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('index,follow', $html);
        $this->assertStringNotContainsString('noindex,nofollow', $html);
    }

    public function test_staging_can_still_opt_out_with_both_switches(): void
    {
        Config::set('magnoolia.seo.indexable', false);
        Config::set('magnoolia.seo.noindex', true);

        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('noindex,nofollow', $html);
    }

    public function test_thank_you_page_stays_noindex_while_site_is_indexable(): void
    {
        foreach (['/aitah', '/ru/aitah', '/en/aitah'] as $url) {
            $html = $this->get($url)->assertOk()->getContent();
            $this->assertStringContainsString('noindex', $html, $url . ' must stay out of the index.');
        }
    }

    public function test_public_pages_carry_no_noindex(): void
    {
        foreach (['/', '/kodud-ja-hinnad', '/asukoht', '/kontakt', '/kkk', '/galerii'] as $url) {
            $html = $this->get($url)->assertOk()->getContent();
            $this->assertStringNotContainsString('noindex', $html, $url . ' must be indexable.');
        }
    }

    // ── robots.txt (route) ──────────────────────────────────────────────

    public function test_robots_route_allows_crawling_and_blocks_admin(): void
    {
        $robots = $this->get('/robots.txt')->assertOk()->getContent();

        $this->assertStringContainsString('Allow: /', $robots);
        $this->assertDoesNotMatchRegularExpression('/^Disallow: \/$/m', $robots);
        $this->assertStringContainsString('Disallow: /admin', $robots);
        $this->assertStringContainsString($this->canonical() . '/sitemap.xml', $robots);
    }

    public function test_robots_route_follows_the_same_switch_as_the_meta_tag(): void
    {
        // robots.txt and <meta name="robots"> must never disagree.
        Config::set('magnoolia.seo.indexable', false);
        Config::set('magnoolia.seo.noindex', true);

        $robots = $this->get('/robots.txt')->assertOk()->getContent();

        $this->assertMatchesRegularExpression('/^Disallow: \/$/m', $robots);
    }

    // ── Canonical / hreflang / OG ───────────────────────────────────────

    public function test_key_pages_canonicalise_to_the_production_domain(): void
    {
        $base = $this->canonical();
        $pages = ['/' => $base, '/kodud-ja-hinnad' => $base . '/kodud-ja-hinnad',
                  '/asukoht' => $base . '/asukoht', '/kontakt' => $base . '/kontakt'];

        foreach ($pages as $url => $expected) {
            $html = $this->get($url)->assertOk()->getContent();
            $this->assertStringContainsString('<link rel="canonical" href="' . $expected . '"', $html, $url);
        }
    }

    public function test_no_non_canonical_host_appears_in_public_html(): void
    {
        // The forbidden set is derived from config, so this keeps working whichever
        // host is canonical.
        $forbiddenHosts = \App\Support\MagnooliaRenderedHtmlAudit::forbiddenStrings('et');
        $this->assertNotEmpty($forbiddenHosts);

        foreach (['/', '/kodud-ja-hinnad', '/kontakt', '/ru', '/en'] as $url) {
            $html = $this->get($url)->assertOk()->getContent();

            foreach ($forbiddenHosts as $forbidden) {
                $this->assertStringNotContainsString($forbidden, $html, "$url leaks $forbidden");
            }
        }
    }

    // ── Canonical follows the serving host, automatically ───────────────

    public function test_canonical_adopts_whichever_public_host_serves_the_request(): void
    {
        // Today magnoolia.ee 302s to magnoolia.estlanda.ee, so the app is reached
        // on the estlanda host and must canonicalise to it…
        $html = $this->get('http://magnoolia.estlanda.ee/kodud-ja-hinnad')->assertOk()->getContent();
        $this->assertStringContainsString(
            '<link rel="canonical" href="https://magnoolia.estlanda.ee/kodud-ja-hinnad"', $html
        );

        // …and if the redirect is reversed later, the app is reached on
        // magnoolia.ee and canonicalises to that instead — no code or env change.
        $html = $this->get('http://magnoolia.ee/kodud-ja-hinnad')->assertOk()->getContent();
        $this->assertStringContainsString(
            '<link rel="canonical" href="https://magnoolia.ee/kodud-ja-hinnad"', $html
        );
    }

    public function test_www_shares_the_canonical_of_its_apex(): void
    {
        $html = $this->get('http://www.magnoolia.ee/kontakt')->assertOk()->getContent();

        $this->assertStringContainsString('<link rel="canonical" href="https://magnoolia.ee/kontakt"', $html);
        $this->assertStringNotContainsString('href="https://www.magnoolia.ee', $html);
    }

    public function test_canonical_is_always_https_even_on_a_plain_http_request(): void
    {
        $html = $this->get('http://magnoolia.estlanda.ee/')->assertOk()->getContent();

        $this->assertStringContainsString('<link rel="canonical" href="https://magnoolia.estlanda.ee"', $html);
    }

    public function test_unknown_host_cannot_poison_the_seo_signals(): void
    {
        // A host outside magnoolia.seo.public_hosts must never be adopted as the
        // canonical base — otherwise a forged `Host:` header could rewrite the
        // canonical, OG and sitemap URLs of a cached page.
        //
        // Note: asset()/url() still echo the request host into <script>/<link>
        // src attributes. That is stock Laravel behaviour, unrelated to canonical
        // resolution, and is mitigated (if wanted) by Laravel's TrustedHosts
        // middleware. The SEO signals below are the ones that must not move.
        $html = $this->get('http://evil.example.com/kodud-ja-hinnad')->assertOk()->getContent();
        $base = $this->canonical();

        $this->assertStringContainsString('<link rel="canonical" href="' . $base . '/kodud-ja-hinnad"', $html);
        $this->assertStringContainsString('hreflang="et" href="' . $base . '/kodud-ja-hinnad"', $html);
        $this->assertStringNotContainsString('rel="canonical" href="http://evil', $html);
        $this->assertStringNotContainsString('og:url"         content="http://evil', $html);
        $this->assertStringNotContainsString('"@id": "http://evil', $html);

        $xml = $this->get('http://evil.example.com/sitemap.xml')->assertOk()->getContent();
        $this->assertStringNotContainsString('evil.example.com', $xml);
    }

    public function test_sitemap_and_robots_follow_the_serving_host_too(): void
    {
        $xml = $this->get('http://magnoolia.estlanda.ee/sitemap.xml')->assertOk()->getContent();
        preg_match_all('#<loc>(.*?)</loc>#', $xml, $m);
        $this->assertNotEmpty($m[1]);
        foreach ($m[1] as $loc) {
            $this->assertStringStartsWith('https://magnoolia.estlanda.ee', $loc, "Sitemap URL ignored the serving host: $loc");
        }

        $robots = $this->get('http://magnoolia.estlanda.ee/robots.txt')->assertOk()->getContent();
        $this->assertStringContainsString('https://magnoolia.estlanda.ee/sitemap.xml', $robots);
    }

    public function test_switching_the_canonical_domain_moves_every_seo_signal(): void
    {
        // Production currently runs magnoolia.estlanda.ee as the live host with
        // magnoolia.ee redirecting to it; that may later be reversed. Both states
        // must be reachable by changing ONE value — MAGNOOLIA_CANONICAL_DOMAIN —
        // and nothing may keep pointing at the old host.
        $alternative = 'https://magnoolia.estlanda.ee';
        Config::set('magnoolia.canonical_domain', $alternative);
        Config::set('magnoolia.seo.canonical_base', $alternative);

        $html = $this->get('/kodud-ja-hinnad')->assertOk()->getContent();

        $this->assertStringContainsString('<link rel="canonical" href="' . $alternative . '/kodud-ja-hinnad"', $html);
        $this->assertStringContainsString('hreflang="et" href="' . $alternative . '/kodud-ja-hinnad"', $html);
        $this->assertStringContainsString('hreflang="ru" href="' . $alternative . '/ru/kodud-ja-hinnad"', $html);
        $this->assertMatchesRegularExpression(
            '#<meta property="og:url"\s+content="' . preg_quote($alternative, '#') . '#',
            $html
        );
        // …and nothing may still advertise the old canonical host.
        $this->assertStringNotContainsString('href="https://magnoolia.ee', $html);

        $xml = $this->get('/sitemap.xml')->assertOk()->getContent();
        preg_match_all('#<loc>(.*?)</loc>#', $xml, $m);
        foreach ($m[1] as $loc) {
            $this->assertStringStartsWith($alternative, $loc, "Sitemap URL did not follow the canonical switch: $loc");
        }

        $robots = $this->get('/robots.txt')->assertOk()->getContent();
        $this->assertStringContainsString($alternative . '/sitemap.xml', $robots);
    }

    // ── Sitemap ─────────────────────────────────────────────────────────

    public function test_sitemap_contains_only_canonical_domain_urls(): void
    {
        $xml = $this->get('/sitemap.xml')->assertOk()->getContent();

        preg_match_all('#<loc>(.*?)</loc>#', $xml, $m);
        $this->assertNotEmpty($m[1], 'Sitemap has no <loc> entries.');

        foreach ($m[1] as $loc) {
            $this->assertStringStartsWith($this->canonical(), $loc, "Non-canonical sitemap URL: $loc");
        }
    }

    public function test_sitemap_excludes_admin_and_redirecting_urls(): void
    {
        $xml = $this->get('/sitemap.xml')->assertOk()->getContent();

        $this->assertStringNotContainsString('/admin', $xml);
        // /asendiplaan 301s into /kodud-ja-hinnad#mg-masterplan — a sitemap must
        // not advertise URLs that redirect.
        $this->assertStringNotContainsString('<loc>' . $this->canonical() . '/asendiplaan</loc>', $xml);
    }

    public function test_sitemap_lists_core_pages_and_seo_landings(): void
    {
        $xml = $this->get('/sitemap.xml')->assertOk()->getContent();

        foreach ([
            '/kodud-ja-hinnad', '/asukoht', '/ehitusinfo', '/kontakt', '/sisedisain',
            '/galerii', '/kkk', '/ridaelamud-harjumaa', '/uusarendus-kiili',
            '/ridaelamu-vaela-kula', '/a-energiaklassi-ridaelamud',
            '/en/new-townhouses-near-tallinn', '/ru/taunhaus-rjadom-s-tallinnom',
        ] as $path) {
            $this->assertStringContainsString('<loc>' . $this->canonical() . $path . '</loc>', $xml, "Missing from sitemap: $path");
        }
    }

}
