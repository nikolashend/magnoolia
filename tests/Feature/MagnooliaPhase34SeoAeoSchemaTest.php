<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Phase 34 — SEO / AEO / structured-data gate. All JSON-LD must be valid JSON
 * (the Phase 34 fix to the FAQ serializer is regression-guarded here), and the
 * key entity types must be present.
 */
class MagnooliaPhase34SeoAeoSchemaTest extends TestCase
{
    use RefreshDatabase;

    /** @return string[] raw JSON-LD blocks */
    private function jsonLdBlocks(string $html): array
    {
        preg_match_all('#<script[^>]*type="application/ld\+json"[^>]*>(.*?)</script>#s', $html, $m);
        return $m[1] ?? [];
    }

    private function types(string $html): array
    {
        $types = [];
        foreach ($this->jsonLdBlocks($html) as $block) {
            $j = json_decode($block, true);
            $this->assertNotNull($j, 'JSON-LD must be valid JSON');
            $walk = function ($node) use (&$walk, &$types) {
                if (is_array($node)) {
                    if (isset($node['@type'])) {
                        foreach ((array) $node['@type'] as $t) $types[] = $t;
                    }
                    foreach ($node as $v) {
                        if (is_array($v)) $walk($v);
                    }
                }
            };
            $walk($j);
        }
        return $types;
    }

    public function test_all_json_ld_is_valid_in_all_locales(): void
    {
        foreach (['et', 'ru', 'en'] as $loc) {
            foreach (['', '/kodud-ja-hinnad', '/kkk', '/kontakt', '/ostuprotsess'] as $p) {
                $url = $loc === 'et' ? ($p ?: '/') : '/' . $loc . $p;
                $html = $this->get($url)->assertOk()->getContent();
                foreach ($this->jsonLdBlocks($html) as $block) {
                    $this->assertNotNull(json_decode($block, true), "invalid JSON-LD on {$url}");
                }
            }
        }
    }

    public function test_core_entity_types_present_on_home(): void
    {
        $types = $this->types($this->get('/')->assertOk()->getContent());
        foreach (['Organization', 'WebSite', 'ApartmentComplex', 'BreadcrumbList'] as $t) {
            $this->assertContains($t, $types, "home schema must include {$t}");
        }
    }

    public function test_faq_page_schema_present_where_faq_exists(): void
    {
        $types = $this->types($this->get('/kkk')->assertOk()->getContent());
        $this->assertContains('FAQPage', $types, 'KKK must expose FAQPage schema');

        $types = $this->types($this->get('/ostuprotsess')->assertOk()->getContent());
        $this->assertContains('HowTo', $types, 'Ostuprotsess should expose HowTo schema');
    }

    public function test_contact_page_has_contactpage_schema(): void
    {
        $types = $this->types($this->get('/kontakt')->assertOk()->getContent());
        $this->assertContains('ContactPage', $types);
    }

    public function test_sitemap_has_all_locales_and_no_admin(): void
    {
        $xml = $this->get('/sitemap.xml')->assertOk()->getContent();
        // 12 core pages × 3 locales = 36, plus unit-detail pages in each locale.
        $this->assertGreaterThanOrEqual(36, substr_count($xml, '<loc>'), 'at least 12 pages × 3 locales');
        $this->assertStringNotContainsString('/admin', $xml);
        $this->assertStringContainsString('/ru/', $xml);
        $this->assertStringContainsString('/en/', $xml);
    }
}
