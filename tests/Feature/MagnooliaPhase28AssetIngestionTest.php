<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Phase 28 — Asset ingestion verification
 * Verifies that logos and PPTX images were processed and are accessible.
 */
class MagnooliaPhase28AssetIngestionTest extends TestCase
{
    public function test_magnoolia_logo_webp_exists(): void
    {
        $this->assertFileExists(public_path('assets/magnoolia/logos/magnoolia-dark.webp'),
            'Magnoolia logo (dark/no-background) WebP must exist');
        $this->assertFileExists(public_path('assets/magnoolia/logos/magnoolia-light.webp'),
            'Magnoolia logo (light/with-background) WebP must exist');
    }

    public function test_estlanda_logo_webp_exists(): void
    {
        $this->assertFileExists(public_path('assets/magnoolia/logos/estlanda.webp'),
            'Estlanda logo WebP must exist');
        $this->assertFileExists(public_path('assets/magnoolia/logos/estlanda-2.webp'),
            'Estlanda horizontal logo WebP must exist');
    }

    public function test_pptx_images_extracted(): void
    {
        $sisedisainDir = public_path('assets/magnoolia/sisedisain/pptx');
        $this->assertDirectoryExists($sisedisainDir, 'PPTX extraction directory must exist');

        // Count WebP images recursively using RecursiveIterator (cross-platform)
        $total = 0;
        if (is_dir($sisedisainDir)) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($sisedisainDir, \RecursiveDirectoryIterator::SKIP_DOTS)
            );
            foreach ($iterator as $file) {
                if (strtolower($file->getExtension()) === 'webp') {
                    $total++;
                }
            }
        }

        $this->assertGreaterThan(20, $total,
            'At least 20 WebP images should have been extracted from PPTX files');
    }

    public function test_banner_images_processed(): void
    {
        $this->assertFileExists(public_path('assets/magnoolia/banners/magnoolia-banner-1600w.webp'),
            'Banner 1600w WebP must exist');
        $this->assertFileExists(public_path('assets/magnoolia/banners/magnoolia-banner-768w.webp'),
            'Banner 768w WebP must exist');
    }

    public function test_no_diana_photo_unavailable_is_documented(): void
    {
        // Diana photo was NOT in the phase28 import folder — this is documented
        // The contact page must gracefully handle missing photo (no placeholder icon)
        $importDir = base_path('materials/onedrive-import/phase28');
        $dianaFiles = glob($importDir . '/diana*.jpg') ?: glob($importDir . '/Diana*.jpg') ?: [];
        $this->assertEmpty($dianaFiles,
            'Diana photo was not in phase28 import (confirmed missing — see blocked-assets doc)');
    }

    public function test_logo_png_source_files_copied(): void
    {
        $this->assertFileExists(public_path('assets/magnoolia/logos/magnoolia-dark.png'),
            'Magnoolia logo PNG source (Taustata) must be copied');
        $this->assertFileExists(public_path('assets/magnoolia/logos/magnoolia-light.png'),
            'Magnoolia logo PNG with background (Taustaga) must be copied');
    }
}
