<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Phase 29 — Rowhouse selection asset manifest integrity.
 */
class MagnooliaPhase29AssetManifestTest extends TestCase
{
    private function manifest(): array
    {
        $path = public_path('assets/magnoolia/rowhouse-selection/manifest.json');
        $this->assertFileExists($path, 'Rowhouse selection manifest must exist (run npm run magnoolia:generate:rowhouse)');
        return json_decode((string) file_get_contents($path), true);
    }

    public function test_manifest_has_6_rows_and_19_homes(): void
    {
        $m = $this->manifest();
        $this->assertSame(6, $m['counts']['rows'] ?? null);
        $this->assertSame(19, $m['counts']['homes'] ?? null);
        $this->assertCount(6, $m['rows']);
        $homes = array_sum(array_map(fn ($r) => count($r['homes']), $m['rows']));
        $this->assertSame(19, $homes);
    }

    public function test_manifest_has_clean_asendiplaan_and_overview(): void
    {
        $m = $this->manifest();
        $this->assertNotEmpty($m['asendiplaan']['clean']['base'] ?? null);
        $this->assertNotEmpty($m['overview']['primary']['base'] ?? null);
    }

    public function test_every_row_and_home_asset_file_exists_as_webp(): void
    {
        $m = $this->manifest();
        $assets = [];
        $collect = function ($variants) use (&$assets) {
            foreach (($variants ?? []) as $rel) { $assets[] = $rel; }
        };
        $collect($m['asendiplaan']['clean']);
        $collect($m['overview']['primary']);
        foreach ($m['rows'] as $r) {
            $collect($r['image']);
            foreach ($r['homes'] as $h) { $collect($h['image']); }
        }
        $this->assertNotEmpty($assets);
        foreach ($assets as $rel) {
            $this->assertStringEndsWith('.webp', $rel, "Public asset must be WebP: {$rel}");
            $this->assertFileExists(public_path($rel), "Missing generated asset: {$rel}");
        }
    }

    public function test_manifest_leaks_no_source_paths(): void
    {
        $raw = (string) file_get_contents(public_path('assets/magnoolia/rowhouse-selection/manifest.json'));
        $lower = strtolower($raw);
        $this->assertStringNotContainsString('onedrive', $lower);
        $this->assertStringNotContainsString('materials/phase29', $lower);
        $this->assertStringNotContainsString('2a.png', $lower);
        $this->assertStringNotContainsString('4a.png', $lower);
        // No colour-mask or raw render JPG referenced as a public asset
        $this->assertStringNotContainsString('/1.jpg', $lower);
        $this->assertStringNotContainsString('/5.jpg', $lower);
    }
}
