<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Support\MagnooliaRenderedHtmlAudit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Http\Request;
use Tests\TestCase;

class MagnooliaRenderedHtmlAuditTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider urls
     */
    public function test_rendered_html_is_clean(string $url): void
    {
        $response = $this->renderUrl($url);
        $this->assertSame(200, $response->getStatusCode(), $url . ' returned unexpected status.');

        $audit = MagnooliaRenderedHtmlAudit::analyze($url, $response->getContent());

        $this->assertSame([], $audit['forbidden_found'], $url . ' contains forbidden strings: ' . implode(', ', $audit['forbidden_found']));
        $this->assertSame([], $audit['duplicate_ids'], $url . ' has duplicate ids: ' . implode(', ', $audit['duplicate_ids']));
        $this->assertSame([], $audit['broken_anchors'], $url . ' has broken anchors: ' . implode(', ', $audit['broken_anchors']));
        $this->assertSame([], $audit['template_residue'], $url . ' has template residue: ' . implode(', ', $audit['template_residue']));
        $this->assertNotEmpty($audit['title'], $url . ' missing title');
        $this->assertNotEmpty($audit['description'], $url . ' missing description');
    }

    /**
     * @return array<int, array{0: string}>
     */
    public static function urls(): array
    {
        return array_map(static fn (string $url): array => [$url], MagnooliaRenderedHtmlAudit::urls());
    }

    public function test_all_rendered_titles_are_unique(): void
    {
        $titles = [];

        foreach (MagnooliaRenderedHtmlAudit::urls() as $url) {
            $html = $this->renderUrl($url)->getContent();
            $audit = MagnooliaRenderedHtmlAudit::analyze($url, $html);
            $titles[$url] = $audit['title'];
        }

        $this->assertCount(count($titles), array_unique($titles), 'Rendered titles must be unique across all public URLs.');
    }

    private function renderUrl(string $url)
    {
        /** @var HttpKernel $kernel */
        $kernel = $this->app->make(HttpKernel::class);

        return $kernel->handle(Request::create($url, 'GET'));
    }
}
