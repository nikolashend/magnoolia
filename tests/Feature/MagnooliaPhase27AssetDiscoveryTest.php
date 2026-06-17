<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Phase 27 — Asset discovery and manifest integrity.
 */
class MagnooliaPhase27AssetDiscoveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_assets_directory_exists(): void
    {
        $this->assertDirectoryExists(
            public_path('assets/magnoolia'),
            'public/assets/magnoolia directory must exist'
        );
    }

    public function test_gallery_exterior_images_exist(): void
    {
        $exteriorDir = public_path('assets/magnoolia/gallery/exterior');
        $this->assertDirectoryExists($exteriorDir, 'Gallery exterior directory must exist');

        $images = glob($exteriorDir . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE);
        $this->assertGreaterThan(0, count($images), 'Must have at least one exterior gallery image');
    }

    public function test_gallery_interior_images_exist(): void
    {
        $interiorDir = public_path('assets/magnoolia/gallery/interior');
        $this->assertDirectoryExists($interiorDir, 'Gallery interior directory must exist');

        $images = glob($interiorDir . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE);
        $this->assertGreaterThan(0, count($images), 'Must have at least one interior gallery image');
    }

    public function test_location_images_exist(): void
    {
        $locationDir = public_path('assets/magnoolia/location');
        $this->assertDirectoryExists($locationDir, 'Location images directory must exist');

        $images = glob($locationDir . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE);
        $this->assertGreaterThan(0, count($images), 'Must have at least one location image');
    }

    public function test_asset_manifest_config_exists(): void
    {
        $this->assertFileExists(
            config_path('magnoolia_assets.php'),
            'Asset manifest config/magnoolia_assets.php must exist'
        );
    }

    public function test_asset_manifest_is_valid_array(): void
    {
        $manifest = config('magnoolia_assets');
        $this->assertIsArray($manifest, 'Asset manifest must return an array');
    }

    public function test_no_onedrive_paths_in_public_dir(): void
    {
        $publicDir = public_path('assets/magnoolia');
        if (!is_dir($publicDir)) {
            $this->markTestSkipped('No public magnoolia assets directory.');
        }

        $allFiles = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($publicDir, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($allFiles as $file) {
            $path = $file->getPathname();
            $this->assertStringNotContainsString(
                'OneDrive',
                $path,
                "Public asset path must not contain OneDrive: $path"
            );
        }
    }

    public function test_floorplans_directory_exists(): void
    {
        $this->assertTrue(
            is_dir(public_path('assets/magnoolia/floorplans')) ||
            is_dir(public_path('assets/magnoolia/asendiplaan')),
            'Floorplans or asendiplaan directory must exist'
        );
    }

    public function test_no_source_paths_in_asset_manifest(): void
    {
        $manifest = config('magnoolia_assets', []);

        // Always assert the config resolves to an array so the test is never
        // "risky" (no-assertion) when the manifest happens to be empty.
        $this->assertIsArray($manifest, 'magnoolia_assets config must resolve to an array');

        foreach ($manifest as $key => $entry) {
            if (is_array($entry) && isset($entry['path'])) {
                $path = $entry['path'];
                $this->assertStringNotContainsString(
                    'OneDrive',
                    $path,
                    "Asset manifest entry '$key' must not contain OneDrive path"
                );
                $this->assertStringNotContainsString(
                    'resources/source-assets',
                    $path,
                    "Asset manifest entry '$key' must not contain source-assets path"
                );
            }
        }
    }
}
