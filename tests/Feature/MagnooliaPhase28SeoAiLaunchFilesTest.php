<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Phase 28 — SEO/AI launch files test
 * Verifies sitemap.xml, robots.txt, and llms.txt are present and valid.
 */
class MagnooliaPhase28SeoAiLaunchFilesTest extends TestCase
{
    /**
     * Phase 35.0: /sitemap.xml is a dynamic route, not a file. A static
     * public/sitemap.xml would be served by Apache before Laravel ever runs and
     * would silently freeze the sitemap (it predates the 14 SEO landing pages),
     * so its absence is the requirement.
     */
    public function test_no_static_sitemap_shadows_the_dynamic_route(): void
    {
        $this->assertFileDoesNotExist(public_path('sitemap.xml'),
            'A static public/sitemap.xml shadows the dynamic /sitemap.xml route — delete it.');
    }

    public function test_sitemap_xml_is_valid(): void
    {
        $content = $this->sitemap();

        $this->assertNotEmpty($content, 'sitemap must not be empty');
        $this->assertStringContainsString('urlset', $content,
            'sitemap must contain urlset element');
        $this->assertStringContainsString('magnoolia.ee', $content,
            'sitemap must reference magnoolia.ee domain');
    }

    public function test_sitemap_contains_all_required_pages(): void
    {
        $content = $this->sitemap();

        $requiredUrls = [
            'magnoolia.ee',
            'kodud-ja-hinnad',
            // 'asendiplaan' is intentionally absent: it 301s into
            // /kodud-ja-hinnad#mg-masterplan and a sitemap must not list redirects.
            'asukoht',
            'ehitusinfo',
            'sisedisain',
            'arhitektuur-ja-valisdisain',
            'galerii',
            'finantseerimine',
            'ostuprotsess',
            'kkk',
            'kontakt',
        ];

        foreach ($requiredUrls as $url) {
            $this->assertStringContainsString($url, $content,
                "sitemap must contain URL containing '{$url}'");
        }
    }

    public function test_sitemap_has_ru_and_en_alternatives(): void
    {
        $content = $this->sitemap();
        $this->assertStringContainsString('/ru/', $content,
            'sitemap must include RU locale pages');
        $this->assertStringContainsString('/en/', $content,
            'sitemap must include EN locale pages');
    }

    private function sitemap(): string
    {
        return $this->get('/sitemap.xml')->assertOk()->getContent();
    }

    /**
     * Phase 35.0: like the sitemap, robots.txt is a route, not a file. A static
     * public/robots.txt is served by Apache before Laravel runs, so it froze the
     * sitemap URL to one hardcoded domain and could contradict the robots meta tag.
     */
    public function test_no_static_robots_shadows_the_dynamic_route(): void
    {
        $this->assertFileDoesNotExist(public_path('robots.txt'),
            'A static public/robots.txt shadows the dynamic /robots.txt route — delete it.');
    }

    public function test_robots_txt_allows_crawling(): void
    {
        $content = $this->get('/robots.txt')->assertOk()->getContent();
        $this->assertStringContainsString('Allow', $content,
            'robots.txt must not block all crawling');
        $this->assertStringContainsString('sitemap', strtolower($content),
            'robots.txt must reference sitemap');
    }

    public function test_llms_txt_exists_and_has_required_content(): void
    {
        $this->assertFileExists(public_path('llms.txt'),
            'llms.txt must exist');

        $content = file_get_contents(public_path('llms.txt'));

        $required = [
            'Magnoolia',
            '19',
            'Vaela',
            'Kiili',
            'Diana Tali',
            'energy-class',
        ];

        foreach ($required as $term) {
            $this->assertStringContainsString($term, $content,
                "llms.txt must mention '{$term}'");
        }
    }

    public function test_llms_txt_does_not_contain_fake_prices(): void
    {
        $content = file_get_contents(public_path('llms.txt'));

        // llms.txt must not contain specific price figures
        $this->assertStringNotContainsString('€450,000', $content,
            'llms.txt must not contain specific prices');
        $this->assertStringNotContainsString('price_cents', $content,
            'llms.txt must not contain price_cents');
    }

    public function test_sitemap_accessible_via_http(): void
    {
        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml');
    }
}
