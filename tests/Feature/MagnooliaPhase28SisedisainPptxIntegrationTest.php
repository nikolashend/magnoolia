<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Phase 28 — Sisedisain PPTX integration test
 * Verifies that the Sisedisain page uses extracted PPTX images.
 */
class MagnooliaPhase28SisedisainPptxIntegrationTest extends TestCase
{
    public function test_pptx_webp_images_exist_in_public(): void
    {
        $sisedisainWebpDir = public_path('assets/magnoolia/sisedisain/pptx/Magnoolia__kodud_Prestige_Sisedisain/webp');
        $this->assertDirectoryExists($sisedisainWebpDir,
            'Sisedisain PPTX WebP directory must exist');

        $webpCount = count(glob($sisedisainWebpDir . DIRECTORY_SEPARATOR . '*.webp') ?: []);
        $this->assertGreaterThan(10, $webpCount,
            'At least 10 WebP images must be extracted from Sisedisain PPTX');
    }

    public function test_sisedisain_page_renders(): void
    {
        $response = $this->get('/sisedisain');
        $response->assertStatus(200);
    }

    public function test_sisedisain_page_has_pptx_gallery_section(): void
    {
        $response = $this->get('/sisedisain');
        $response->assertStatus(200);
        $html = $response->getContent();

        // Phase 28 adds PPTX gallery section
        $hasPptxSection = str_contains($html, 'pptx-gallery') || str_contains($html, 'pptx');
        // Only assert if images actually exist
        if (is_dir(public_path('assets/magnoolia/sisedisain/pptx/Magnoolia__kodud_Prestige_Sisedisain/webp'))) {
            $this->assertTrue($hasPptxSection,
                'Sisedisain must have PPTX gallery section when images exist');
        }
    }

    public function test_sisedisain_pptx_images_have_alt_text(): void
    {
        $response = $this->get('/sisedisain');
        $response->assertStatus(200);
        $html = $response->getContent();

        if (str_contains($html, 'pptx')) {
            // Any pptx images must have alt attributes
            $this->assertMatchesRegularExpression('/pptx[^"]*\.webp"[^>]*alt="[^"]+"/',
                $html, 'PPTX images in Sisedisain must have alt text');
        }
    }

    public function test_sisedisain_has_illustrative_disclaimer(): void
    {
        $response = $this->get('/sisedisain');
        $response->assertStatus(200);
        $html = $response->getContent();

        // Must have disclaimer that images are illustrative
        $textContent = strip_tags($html);
        $this->assertTrue(
            str_contains(strtolower($textContent), 'illustratiiv') ||
            str_contains(strtolower($textContent), 'illustrative') ||
            str_contains(strtolower($textContent), 'иллюстратив'),
            'Sisedisain must have disclaimer that images/materials are illustrative'
        );
    }

    public function test_pptx_report_generated(): void
    {
        $this->assertFileExists(base_path('docs/magnoolia-phase28-pptx-extraction-report.md'),
            'PPTX extraction report must exist after Phase 28 processing');
    }
}
