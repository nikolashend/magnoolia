<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class MagnooliaRobotsReleaseGateTest extends TestCase
{
    use RefreshDatabase;

    public function test_staging_home_has_noindex(): void
    {
        Config::set('magnoolia.seo.noindex', true);

        $response = $this->renderUrl('/');
        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('noindex,nofollow', $response->getContent());
    }

    public function test_production_home_has_no_noindex(): void
    {
        Config::set('magnoolia.seo.noindex', false);

        $response = $this->renderUrl('/');
        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringNotContainsString('noindex', $response->getContent());
    }

    public function test_thank_you_page_always_noindex(): void
    {
        Config::set('magnoolia.seo.noindex', false);

        foreach (['/aitah', '/ru/aitah', '/en/aitah'] as $url) {
            $response = $this->renderUrl($url);
            $this->assertSame(200, $response->getStatusCode(), $url . ' returned unexpected status.');
            $this->assertStringContainsString('noindex', $response->getContent());
        }
    }

    public function test_production_robots_allows_google(): void
    {
        Config::set('magnoolia.seo.noindex', false);

        $response = $this->renderUrl('/robots.txt');
        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('User-agent: *', $response->getContent());
        $this->assertStringContainsString('Allow: /', $response->getContent());
        // The whole site must NOT be blocked (no standalone "Disallow: /" line)…
        $this->assertDoesNotMatchRegularExpression('/^Disallow: \/$/m', $response->getContent());
        // …but the admin area is explicitly excluded (Phase 34 hardening).
        $this->assertStringContainsString('Disallow: /admin', $response->getContent());
    }

    public function test_sitemap_accessible_and_not_empty(): void
    {
        $response = $this->renderUrl('/sitemap.xml');

        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('<urlset', $response->getContent());
    }

    private function renderUrl(string $url)
    {
        /** @var HttpKernel $kernel */
        $kernel = $this->app->make(HttpKernel::class);

        return $kernel->handle(Request::create($url, 'GET'));
    }
}
