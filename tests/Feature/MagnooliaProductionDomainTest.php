<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Support\MagnooliaRenderedHtmlAudit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class MagnooliaProductionDomainTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('app.url', 'https://magnoolia.ee');
        Config::set('magnoolia.canonical_domain', 'https://magnoolia.ee');
        Config::set('magnoolia.seo.canonical_base', 'https://magnoolia.ee');
        Config::set('magnoolia.seo.production_domain', 'https://magnoolia.ee');
        Config::set('magnoolia.seo.noindex', false);
        URL::forceRootUrl('https://magnoolia.ee');
        URL::forceScheme('https');
    }

    /**
     * @dataProvider urls
     */
    public function test_production_rendered_html_has_no_staging_domain(string $url): void
    {
        $response = $this->get($url);
        $response->assertStatus(200);

        $html = $response->getContent();
        $audit = MagnooliaRenderedHtmlAudit::analyze($url, $html);

        $this->assertStringContainsString('https://magnoolia.ee', $html, $url . ' should use production domain.');
        $this->assertStringNotContainsString('magnoolia.adme.ee', $html, $url . ' must not leak staging domain.');
        $this->assertNotNull($audit['canonical']);
        $this->assertStringStartsWith('https://magnoolia.ee', $audit['canonical']);
    }

    /**
     * @return array<int, array{0: string}>
     */
    public static function urls(): array
    {
        return array_map(static fn (string $url): array => [$url], MagnooliaRenderedHtmlAudit::urls());
    }
}
