<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Magnoolia\MagnooliaPublicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesMagnooliaTestUnits;

/**
 * Phase 27 — Hreflang and canonical URL consistency.
 */
class MagnooliaPhase27HreflangCanonicalTest extends TestCase
{
    use RefreshDatabase, CreatesMagnooliaTestUnits;

    private array $pages;

    protected function setUp(): void
    {
        parent::setUp();
        $admin = User::factory()->verified()->create(['role' => 'magnoolia_admin']);
        $this->create19TestUnits();
        app(MagnooliaPublicationService::class)->publish($admin->id, 'Phase 27 hreflang test');

        $this->pages = ['/', '/kodud-ja-hinnad', '/asendiplaan', '/asukoht', '/kontakt'];
    }

    public function test_all_pages_have_full_hreflang_set(): void
    {
        foreach ($this->pages as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            $this->assertStringContainsString('hreflang="et"', $html, "Page $url must have hreflang=et");
            $this->assertStringContainsString('hreflang="ru"', $html, "Page $url must have hreflang=ru");
            $this->assertStringContainsString('hreflang="en"', $html, "Page $url must have hreflang=en");
            $this->assertStringContainsString('hreflang="x-default"', $html, "Page $url must have hreflang=x-default");
        }
    }

    public function test_canonical_present_on_all_pages(): void
    {
        foreach ($this->pages as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            $this->assertStringContainsString('<link rel="canonical"', $html, "Page $url must have canonical link");
        }
    }

    public function test_canonical_uses_configured_domain(): void
    {
        $canonicalBase = rtrim(config('magnoolia.seo.canonical_base', 'https://magnoolia.ee'), '/');
        $configHost    = parse_url($canonicalBase, PHP_URL_HOST);

        $html = $this->get('/')->assertStatus(200)->getContent();
        preg_match('/<link rel="canonical" href="([^"]+)"/', $html, $matches);
        $canonical = $matches[1] ?? '';
        $canonicalHost = parse_url($canonical, PHP_URL_HOST);

        $this->assertEquals(
            $configHost,
            $canonicalHost,
            "Canonical URL host must match configured canonical base"
        );
    }

    public function test_hreflang_urls_use_single_domain(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        preg_match_all('/hreflang="[^"]+"\s+href="([^"]+)"/', $html, $matches);

        $domains = [];
        foreach ($matches[1] ?? [] as $href) {
            $host = parse_url($href, PHP_URL_HOST);
            if ($host) $domains[$host] = true;
        }

        $this->assertCount(1, $domains, "All hreflang URLs must use same domain. Found: " . implode(', ', array_keys($domains)));
    }

    public function test_og_tags_present_on_all_pages(): void
    {
        foreach ($this->pages as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            $this->assertStringContainsString('og:title', $html, "Page $url must have og:title");
            $this->assertStringContainsString('og:description', $html, "Page $url must have og:description");
        }
    }

    public function test_no_staging_domain_in_canonical_when_configured_as_production(): void
    {
        config(['magnoolia.seo.canonical_base' => 'https://magnoolia.ee']);
        $html = $this->get('/')->assertStatus(200)->getContent();
        $this->assertStringNotContainsString(
            'magnoolia.adme.ee',
            $html,
            'Canonical/hreflang must not use staging domain when production domain is configured'
        );
    }
}
