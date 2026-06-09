<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Phase 26 — Asset manifest integrity checks.
 */
class MagnooliaPhase26AssetManifestTest extends TestCase
{
    public function test_asset_manifest_config_exists(): void
    {
        $manifest = config_path('magnoolia_assets.php');
        $this->assertFileExists($manifest, 'config/magnoolia_assets.php must exist');
    }

    public function test_asset_manifest_returns_array(): void
    {
        $manifest = require config_path('magnoolia_assets.php');
        $this->assertIsArray($manifest);
    }

    public function test_manifest_has_required_top_level_categories(): void
    {
        $manifest = require config_path('magnoolia_assets.php');
        $required = ['location', 'gallery', 'people', 'logos', 'floorplans'];
        foreach ($required as $cat) {
            $this->assertArrayHasKey($cat, $manifest, "Manifest must have '$cat' category");
        }
    }

    public function test_manifest_location_entries_have_required_keys(): void
    {
        $manifest = require config_path('magnoolia_assets.php');
        $this->assertIsArray($manifest['location']);
        foreach ($manifest['location'] as $entry) {
            $this->assertArrayHasKey('key', $entry);
            $this->assertArrayHasKey('public_path', $entry);
            $this->assertArrayHasKey('alt', $entry);
        }
    }

    public function test_manifest_public_paths_do_not_contain_source_paths(): void
    {
        $manifest = require config_path('magnoolia_assets.php');
        $this->assertManifestNoPaths($manifest);
    }

    public function test_manifest_does_not_contain_onedrive_urls(): void
    {
        $content = file_get_contents(config_path('magnoolia_assets.php'));
        $this->assertStringNotContainsStringIgnoringCase('onedrive.live.com', $content);
        $this->assertStringNotContainsStringIgnoringCase('sharepoint.com', $content);
    }

    public function test_manifest_does_not_expose_internal_source_paths_in_public_path_field(): void
    {
        $manifest = require config_path('magnoolia_assets.php');
        $this->walkManifest($manifest, function (array $entry) {
            if (isset($entry['public_path'])) {
                $this->assertStringNotContainsString('resources/source-assets', $entry['public_path'],
                    "public_path must not expose internal source paths");
                $this->assertStringNotContainsString('onedrive', strtolower($entry['public_path']),
                    "public_path must not expose OneDrive paths");
            }
        });
    }

    public function test_gallery_manifest_has_exterior_and_interior_entries(): void
    {
        $manifest = require config_path('magnoolia_assets.php');
        $this->assertArrayHasKey('gallery', $manifest);
        $gallery = $manifest['gallery'];
        $this->assertArrayHasKey('exterior', $gallery, "Gallery must have 'exterior' entries");
        $this->assertArrayHasKey('interior', $gallery, "Gallery must have 'interior' entries");
        $this->assertNotEmpty($gallery['exterior']);
        $this->assertNotEmpty($gallery['interior']);
    }

    private function assertManifestNoPaths(array $manifest): void
    {
        $this->walkManifest($manifest, function (array $entry) {
            if (isset($entry['public_path'])) {
                $this->assertStringStartsWith('assets/', $entry['public_path'],
                    "public_path should be relative: {$entry['public_path']}");
            }
        });
    }

    private function walkManifest(array $manifest, callable $fn): void
    {
        array_walk_recursive($manifest, function ($value, $key) use ($fn, $manifest) {
            // We need to walk entries, not scalar values
        });
        foreach ($manifest as $section) {
            if (!is_array($section)) {
                continue;
            }
            foreach ($section as $entry) {
                if (is_array($entry) && isset($entry['key'])) {
                    $fn($entry);
                } elseif (is_array($entry)) {
                    foreach ($entry as $subEntry) {
                        if (is_array($subEntry) && isset($subEntry['key'])) {
                            $fn($subEntry);
                        }
                    }
                }
            }
        }
    }
}
