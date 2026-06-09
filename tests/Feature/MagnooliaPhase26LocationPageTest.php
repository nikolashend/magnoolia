<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Phase 26 — Asukoht (location) page content and asset checks.
 */
class MagnooliaPhase26LocationPageTest extends TestCase
{
    public function test_asukoht_page_renders_successfully(): void
    {
        $this->get('/asukoht')->assertStatus(200);
    }

    public function test_asukoht_page_renders_location_images(): void
    {
        $response = $this->get('/asukoht');
        $html = $response->getContent();
        // Page should reference images from the magnoolia location folder
        $this->assertStringContainsString('assets/magnoolia/location/', $html,
            'Asukoht page must reference location images');
    }

    public function test_asukoht_page_has_education_section(): void
    {
        $response = $this->get('/asukoht');
        $html = $response->getContent();
        // Should mention either education or Vaela kindergarten or Kiili school
        $this->assertTrue(
            str_contains($html, 'lasteaed') || str_contains($html, 'kool') || str_contains($html, 'education'),
            'Asukoht page must have education/school content'
        );
    }

    public function test_asukoht_page_has_no_onedrive_links(): void
    {
        $html = $this->get('/asukoht')->getContent();
        $this->assertStringNotContainsStringIgnoringCase('onedrive.live.com', $html);
        $this->assertStringNotContainsStringIgnoringCase('sharepoint.com', $html);
    }

    public function test_asukoht_page_has_no_source_asset_paths(): void
    {
        $html = $this->get('/asukoht')->getContent();
        $this->assertStringNotContainsString('resources/source-assets', $html);
    }

    public function test_asukoht_ru_renders(): void
    {
        $this->get('/ru/asukoht')->assertStatus(200);
    }

    public function test_asukoht_en_renders(): void
    {
        $this->get('/en/asukoht')->assertStatus(200);
    }

    public function test_asukoht_page_has_images_with_alt_text(): void
    {
        $html = $this->get('/asukoht')->getContent();
        // All img tags should have alt
        preg_match_all('/<img[^>]+>/i', $html, $matches);
        foreach ($matches[0] as $img) {
            // Skip images that are purely decorative without alt (but they should have alt="")
            if (str_contains($img, 'assets/magnoolia/')) {
                $this->assertMatchesRegularExpression('/alt\s*=/i', $img,
                    "Image tag missing alt: $img");
            }
        }
    }

    public function test_asukoht_page_has_no_price_cents(): void
    {
        $html = $this->get('/asukoht')->getContent();
        $this->assertStringNotContainsString('price_cents', $html);
    }

    public function test_homepage_lifestyle_proof_section_exists(): void
    {
        $html = $this->get('/')->getContent();
        // Homepage should include the lifestyle proof section
        $this->assertStringContainsString('assets/magnoolia/location/', $html,
            'Homepage must include lifestyle proof block with location images');
    }
}
