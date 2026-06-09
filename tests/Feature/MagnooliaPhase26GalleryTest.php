<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Phase 26 — Gallery page: category tabs, images, accessibility.
 */
class MagnooliaPhase26GalleryTest extends TestCase
{
    public function test_gallery_page_renders_successfully(): void
    {
        $this->get('/galerii')->assertStatus(200);
    }

    public function test_gallery_ru_renders(): void
    {
        $this->get('/ru/galerii')->assertStatus(200);
    }

    public function test_gallery_en_renders(): void
    {
        $this->get('/en/galerii')->assertStatus(200);
    }

    public function test_gallery_has_category_navigation(): void
    {
        $html = $this->get('/galerii')->getContent();
        // Gallery must have filter/tab navigation
        $this->assertTrue(
            str_contains($html, 'välisvaated') || str_contains($html, 'Välisvaated') ||
            str_contains($html, 'data-filter') || str_contains($html, 'gallery-filter'),
            'Gallery must have category filter/tab navigation'
        );
    }

    public function test_gallery_has_exterior_images(): void
    {
        $html = $this->get('/galerii')->getContent();
        $this->assertStringContainsString('gallery/exterior', $html,
            'Gallery must reference exterior images');
    }

    public function test_gallery_has_interior_images(): void
    {
        $html = $this->get('/galerii')->getContent();
        $this->assertStringContainsString('gallery/interior', $html,
            'Gallery must reference interior images');
    }

    public function test_gallery_has_environment_or_location_images(): void
    {
        $html = $this->get('/galerii')->getContent();
        $this->assertTrue(
            str_contains($html, 'gallery/environment') || str_contains($html, 'location/'),
            'Gallery must reference environment/location images'
        );
    }

    public function test_gallery_images_have_alt_text(): void
    {
        $html = $this->get('/galerii')->getContent();
        preg_match_all('/<img[^>]+>/i', $html, $matches);
        foreach ($matches[0] as $img) {
            if (str_contains($img, 'assets/magnoolia/gallery/')) {
                $this->assertMatchesRegularExpression('/alt\s*=/i', $img,
                    "Gallery image missing alt attribute: $img");
            }
        }
    }

    public function test_gallery_has_no_onedrive_links(): void
    {
        $html = $this->get('/galerii')->getContent();
        $this->assertStringNotContainsStringIgnoringCase('onedrive.live.com', $html);
    }

    public function test_gallery_has_no_source_paths(): void
    {
        $html = $this->get('/galerii')->getContent();
        $this->assertStringNotContainsString('resources/source-assets', $html);
    }

    public function test_gallery_has_no_price_cents(): void
    {
        $html = $this->get('/galerii')->getContent();
        $this->assertStringNotContainsString('price_cents', $html);
    }

    public function test_gallery_has_keyboard_accessible_elements(): void
    {
        $html = $this->get('/galerii')->getContent();
        // Should have aria labels or keyboard accessible attributes
        $this->assertTrue(
            str_contains($html, 'aria-label') || str_contains($html, 'role=') || str_contains($html, 'tabindex'),
            'Gallery must have accessible keyboard/ARIA attributes'
        );
    }
}
