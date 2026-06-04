<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Support\MagnooliaRenderedHtmlAudit;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class MagnooliaSeoMetaLocaleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider urls
     */
    public function test_seo_meta_is_locale_aware(string $url): void
    {
        $response = $this->renderUrl($url);
        $this->assertSame(200, $response->getStatusCode(), $url . ' returned unexpected status.');

        $audit = MagnooliaRenderedHtmlAudit::analyze($url, $response->getContent());
        $expectedLocale = match (true) {
            str_starts_with($url, '/ru') => 'ru-EE',
            str_starts_with($url, '/en') => 'en-EE',
            default => 'et-EE',
        };

        $this->assertNotEmpty($audit['title'], $url . ' missing title');
        $this->assertNotEmpty($audit['description'], $url . ' missing description');
        $this->assertNotEmpty($audit['canonical'], $url . ' missing canonical');
        $this->assertStringStartsWith('https://magnoolia.ee', $audit['canonical']);
        $this->assertSame(str_replace('-', '_', $expectedLocale), $audit['og_locale']);
        $this->assertSame($expectedLocale, $audit['schema_in_language']);
    }

    /**
     * @return array<int, array{0: string}>
     */
    public static function urls(): array
    {
        return array_map(static fn (string $url): array => [$url], MagnooliaRenderedHtmlAudit::urls());
    }

    public function test_titles_are_unique(): void
    {
        $titles = [];

        foreach (MagnooliaRenderedHtmlAudit::urls() as $url) {
            $audit = MagnooliaRenderedHtmlAudit::analyze($url, $this->renderUrl($url)->getContent());
            $titles[] = $audit['title'];
        }

        $this->assertCount(count($titles), array_unique($titles), 'Every public URL must have a unique title.');
    }

    private function renderUrl(string $url)
    {
        /** @var HttpKernel $kernel */
        $kernel = $this->app->make(HttpKernel::class);

        return $kernel->handle(Request::create($url, 'GET'));
    }
}
