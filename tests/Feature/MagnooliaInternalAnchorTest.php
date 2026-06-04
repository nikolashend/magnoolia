<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Support\MagnooliaRenderedHtmlAudit;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class MagnooliaInternalAnchorTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider urls
     */
    public function test_internal_anchors_are_valid(string $url): void
    {
        $response = $this->renderUrl($url);
        $this->assertSame(200, $response->getStatusCode(), $url . ' returned unexpected status.');

        $audit = MagnooliaRenderedHtmlAudit::analyze($url, $response->getContent());

        $this->assertSame([], $audit['broken_anchors'], $url . ' has broken anchors: ' . implode(', ', $audit['broken_anchors']));
        $this->assertSame([], $audit['duplicate_ids'], $url . ' has duplicate IDs: ' . implode(', ', $audit['duplicate_ids']));
        $this->assertStringNotContainsString('href="#"', $response->getContent());
        $this->assertStringNotContainsString('javascript:void(0)', $response->getContent());
    }

    /**
     * @return array<int, array{0: string}>
     */
    public static function urls(): array
    {
        return array_map(static fn (string $url): array => [$url], MagnooliaRenderedHtmlAudit::urls());
    }

    private function renderUrl(string $url)
    {
        /** @var HttpKernel $kernel */
        $kernel = $this->app->make(HttpKernel::class);

        return $kernel->handle(Request::create($url, 'GET'));
    }
}
