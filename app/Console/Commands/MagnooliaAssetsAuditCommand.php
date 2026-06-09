<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MagnooliaAssetsAuditCommand extends Command
{
    protected $signature = 'magnoolia:assets:audit
                            {--json : Output as JSON}
                            {--write-report : Write audit to docs/magnoolia-phase26-asset-audit.md}';

    protected $description = 'Audit Magnoolia source and public asset folders';

    public function handle(): int
    {
        $manifest = config('magnoolia_assets', []);

        $results = [
            'public_asset_count'  => 0,
            'manifest_entries'    => 0,
            'missing_manifest'    => [],
            'missing_public'      => [],
            'oversized'           => [],
            'onedrive_leakage'    => [],
            'source_path_leakage' => [],
            'unsupported_types'   => [],
            'missing_folders'     => [],
        ];

        // ── Check expected public folders ────────────────────────────
        $expectedFolders = [
            'assets/magnoolia/location',
            'assets/magnoolia/gallery/exterior',
            'assets/magnoolia/gallery/interior',
            'assets/magnoolia/gallery/environment',
            'assets/magnoolia/sisedisain',
            'assets/magnoolia/people',
            'assets/magnoolia/logos',
            'assets/magnoolia/finance',
            'assets/magnoolia/floorplans',
        ];

        foreach ($expectedFolders as $folder) {
            $fullPath = public_path($folder);
            if (!is_dir($fullPath)) {
                $results['missing_folders'][] = $folder;
            }
        }

        // ── Scan public asset folders ────────────────────────────────
        $publicBase = public_path('assets/magnoolia');
        if (is_dir($publicBase)) {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($publicBase));
            foreach ($files as $file) {
                if (!$file->isFile()) {
                    continue;
                }
                $results['public_asset_count']++;
                $size = $file->getSize();
                $relativePath = 'assets/magnoolia/' . ltrim(str_replace('\\', '/', substr($file->getPathname(), strlen($publicBase) + 1)), '/');

                if ($size > 500 * 1024) {
                    $results['oversized'][] = [
                        'path' => $relativePath,
                        'size_kb' => round($size / 1024),
                    ];
                }

                $ext = strtolower($file->getExtension());
                if (in_array($ext, ['exr', 'ai', 'pptx', 'xlsx', 'docx'], true)) {
                    $results['unsupported_types'][] = [
                        'path' => $relativePath,
                        'type' => $ext,
                    ];
                }
            }
        }

        // ── Check manifest entries exist publicly ────────────────────
        $allEntries = $this->flattenManifest($manifest);
        $results['manifest_entries'] = count($allEntries);

        foreach ($allEntries as $entry) {
            $publicPath = $entry['public_path'] ?? null;
            if (!$publicPath) {
                continue;
            }
            if (!file_exists(public_path($publicPath))) {
                $results['missing_public'][] = [
                    'key'  => $entry['key'] ?? '?',
                    'path' => $publicPath,
                    'note' => $entry['source_note'] ?? '',
                ];
            }
        }

        // ── Scan Blade/config files for OneDrive & source-asset leakage ─
        $scanDirs = [
            resource_path('views'),
            config_path(),
            resource_path('lang'),
        ];

        $forbidden = [
            'onedrive.live.com'   => 'onedrive_leakage',
            'sharepoint.com'      => 'onedrive_leakage',
            'resources/source-assets' => 'source_path_leakage',
            'source-assets/magnoolia' => 'source_path_leakage',
            'materials/asukoht'   => 'source_path_leakage',
        ];

        foreach ($scanDirs as $dir) {
            if (!is_dir($dir)) {
                continue;
            }
            $iter = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
            foreach ($iter as $file) {
                if (!$file->isFile() || !in_array($file->getExtension(), ['php', 'blade', 'json'], true)) {
                    continue;
                }
                $content = file_get_contents($file->getPathname());
                foreach ($forbidden as $needle => $bucket) {
                    if (str_contains($content, $needle)) {
                        $results[$bucket][] = $file->getPathname();
                    }
                }
            }
        }

        $results['onedrive_leakage']    = array_unique($results['onedrive_leakage']);
        $results['source_path_leakage'] = array_unique($results['source_path_leakage']);

        // ── Output ───────────────────────────────────────────────────
        if ($this->option('json')) {
            $this->line(json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            return self::SUCCESS;
        }

        $this->info('Magnoolia — Asset Audit');
        $this->line(str_repeat('─', 50));

        $this->line("Public assets found:   {$results['public_asset_count']}");
        $this->line("Manifest entries:      {$results['manifest_entries']}");

        if ($results['missing_folders']) {
            $this->warn('Missing public folders:');
            foreach ($results['missing_folders'] as $f) {
                $this->line("  ✗ {$f}");
            }
        } else {
            $this->line('Missing public folders: none');
        }

        if ($results['oversized']) {
            $this->warn('Images over 500 KB (need optimization):');
            foreach ($results['oversized'] as $f) {
                $this->line("  ! {$f['path']} ({$f['size_kb']} KB)");
            }
        } else {
            $this->line('Oversized images: none');
        }

        if ($results['missing_public']) {
            $this->warn('Manifest entries missing from public/:');
            foreach ($results['missing_public'] as $m) {
                $note = $m['note'] ? " — {$m['note']}" : '';
                $this->line("  ✗ [{$m['key']}] {$m['path']}{$note}");
            }
        } else {
            $this->line('Missing public assets: none');
        }

        $counts = [
            'OneDrive leakage'    => count($results['onedrive_leakage']),
            'Source path leakage' => count($results['source_path_leakage']),
            'Unsupported types'   => count($results['unsupported_types']),
        ];

        foreach ($counts as $label => $count) {
            $icon = $count > 0 ? '!' : '✓';
            $this->line("{$icon} {$label}: {$count}");
        }

        if ($this->option('write-report')) {
            $this->writeReport($results);
            $this->info('Report written: docs/magnoolia-phase26-asset-audit.md');
        }

        return self::SUCCESS;
    }

    private function flattenManifest(array $manifest): array
    {
        $entries = [];
        array_walk_recursive($manifest, function ($v, $k) use (&$entries, $manifest) {
        });
        // Flatten nested manifest arrays
        foreach ($manifest as $section => $items) {
            if (!is_array($items)) {
                continue;
            }
            foreach ($items as $item) {
                if (isset($item['public_path'])) {
                    $entries[] = $item;
                } elseif (is_array($item)) {
                    // Nested (gallery has exterior/interior/environment sub-arrays)
                    foreach ($item as $subItem) {
                        if (is_array($subItem) && isset($subItem['public_path'])) {
                            $entries[] = $subItem;
                        }
                    }
                }
            }
        }
        return $entries;
    }

    private function writeReport(array $results): void
    {
        $now   = now()->format('Y-m-d H:i');
        $lines = ["# Magnoolia Phase 26 — Asset Audit\n", "_Generated: {$now}_\n"];

        $lines[] = "\n## Summary\n";
        $lines[] = "| Metric | Value |\n|--------|-------|\n";
        $lines[] = "| Public assets | {$results['public_asset_count']} |\n";
        $lines[] = "| Manifest entries | {$results['manifest_entries']} |\n";
        $lines[] = "| Missing folders | " . count($results['missing_folders']) . " |\n";
        $lines[] = "| Oversized (>500KB) | " . count($results['oversized']) . " |\n";
        $lines[] = "| Missing from public | " . count($results['missing_public']) . " |\n";
        $lines[] = "| OneDrive leakage | " . count($results['onedrive_leakage']) . " |\n";
        $lines[] = "| Source path leakage | " . count($results['source_path_leakage']) . " |\n";

        if ($results['missing_public']) {
            $lines[] = "\n## Missing Assets (awaiting delivery)\n";
            foreach ($results['missing_public'] as $m) {
                $lines[] = "- `{$m['path']}` — {$m['note']}\n";
            }
        }

        if ($results['oversized']) {
            $lines[] = "\n## Oversized Images (need WebP conversion)\n";
            foreach ($results['oversized'] as $f) {
                $lines[] = "- `{$f['path']}` — {$f['size_kb']} KB\n";
            }
        }

        $docsDir = base_path('docs');
        if (!is_dir($docsDir)) {
            mkdir($docsDir, 0755, true);
        }
        file_put_contents($docsDir . '/magnoolia-phase26-asset-audit.md', implode('', $lines));
    }
}
