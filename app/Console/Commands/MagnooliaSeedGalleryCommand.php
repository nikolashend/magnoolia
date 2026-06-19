<?php

namespace App\Console\Commands;

use App\Models\MagnooliaMediaItem;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

/**
 * Phase 33.1 — import the existing public gallery images into the Media Library
 * (category = gallery) so the admin gallery manager is populated and manageable
 * (alt text, replace, delete-guard) instead of showing an empty "No gallery"
 * screen while the public /galerii clearly has images. Idempotent.
 */
class MagnooliaSeedGalleryCommand extends Command
{
    protected $signature = 'magnoolia:seed-gallery';

    protected $description = 'Import existing public gallery images into the Media Library (category=gallery).';

    private const DIRS = [
        'exterior' => 'Exterior',
        'interior' => 'Interior',
        'environment' => 'Environment',
    ];

    public function handle(): int
    {
        $created = 0;
        $skipped = 0;

        foreach (self::DIRS as $dir => $label) {
            $abs = public_path('assets/magnoolia/gallery/' . $dir);
            if (!is_dir($abs)) {
                continue;
            }
            foreach (scandir($abs) ?: [] as $file) {
                if (in_array($file, ['.', '..'], true)) {
                    continue;
                }
                // Skip responsive variants (e.g. Cam001-480w.webp) and non-images.
                if (preg_match('/-\d+w\.\w+$/', $file)) {
                    continue;
                }
                if (!preg_match('/\.(jpe?g|png|webp|avif)$/i', $file)) {
                    continue;
                }

                $rel = 'assets/magnoolia/gallery/' . $dir . '/' . $file;
                if (MagnooliaMediaItem::query()->where('public_path', $rel)->exists()) {
                    $skipped++;
                    continue;
                }

                // Prefer a small webp variant for the admin thumbnail if present.
                $stem = pathinfo($file, PATHINFO_FILENAME);
                $thumb = $rel;
                foreach (['-480w.webp', '-768w.webp'] as $suf) {
                    if (is_file($abs . '/' . $stem . $suf)) {
                        $thumb = 'assets/magnoolia/gallery/' . $dir . '/' . $stem . $suf;
                        break;
                    }
                }

                $dims = @getimagesize($abs . '/' . $file) ?: [null, null];

                MagnooliaMediaItem::query()->create([
                    'title' => $label . ' — ' . Str::headline($stem),
                    'category' => 'gallery',
                    'original_name' => $file,
                    'mime' => null,
                    'size_bytes' => @filesize($abs . '/' . $file) ?: 0,
                    'width' => $dims[0] ?? null,
                    'height' => $dims[1] ?? null,
                    'original_path' => null,
                    'public_path' => $rel,
                    'thumb_path' => $thumb,
                ]);
                $created++;
            }
        }

        $this->info("Gallery import complete. created={$created} skipped(existing)={$skipped} total gallery=" . MagnooliaMediaItem::where('category', 'gallery')->count());
        return self::SUCCESS;
    }
}
