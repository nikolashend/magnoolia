<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Phase 28 — SEO/AI launch files test
 * Verifies sitemap.xml, robots.txt, and llms.txt are present and valid.
 */
class MagnooliaPhase28SeoAiLaunchFilesTest extends TestCase
{
    public function test_sitemap_xml_exists_and_is_valid(): void
    {
        $this->assertFileExists(public_path('sitemap.xml'),
            'sitemap.xml must exist in public folder');

        $content = file_get_contents(public_path('sitemap.xml'));
        $this->assertNotEmpty($content, 'sitemap.xml must not be empty');
        $this->assertStringContainsString('urlset', $content,
            'sitemap.xml must contain urlset element');
        $this->assertStringContainsString('magnoolia.ee', $content,
            'sitemap.xml must reference magnoolia.ee domain');
    }

    public function test_sitemap_contains_all_required_pages(): void
    {
        $content = file_get_contents(public_path('sitemap.xml'));

        $requiredUrls = [
            'magnoolia.ee/',
            'kodud-ja-hinnad',
            'asendiplaan',
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
                "sitemap.xml must contain URL containing '{$url}'");
        }
    }

    public function test_sitemap_has_ru_and_en_alternatives(): void
    {
        $content = file_get_contents(public_path('sitemap.xml'));
        $this->assertStringContainsString('/ru/', $content,
            'sitemap.xml must include RU locale pages');
        $this->assertStringContainsString('/en/', $content,
            'sitemap.xml must include EN locale pages');
    }

    public function test_robots_txt_exists_and_allows_crawling(): void
    {
        $this->assertFileExists(public_path('robots.txt'),
            'robots.txt must exist');

        $content = file_get_contents(public_path('robots.txt'));
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
