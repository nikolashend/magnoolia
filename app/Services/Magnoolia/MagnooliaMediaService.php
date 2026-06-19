<?php

namespace App\Services\Magnoolia;

use App\Models\MagnooliaMediaItem;
use App\Models\MagnooliaPublication;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/**
 * Phase 33.1 — Magnoolia Media Library file handling.
 *
 * Stores the original privately, and for raster images produces a public,
 * web-optimized WebP plus a thumbnail (via GD). SVG/PDF are copied to the public
 * media folder as-is (no raster pipeline). All public assets live under
 * public/assets/magnoolia/media/ (same convention as the rest of the site).
 */
class MagnooliaMediaService
{
    public const PUBLIC_DIR = 'assets/magnoolia/media';
    public const MAX_WIDTH = 1600;
    public const THUMB_WIDTH = 320;

    /** Build a media item from an uploaded file. */
    public function store(UploadedFile $file, array $meta): MagnooliaMediaItem
    {
        $ext = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'bin');
        $base = Str::slug(pathinfo($meta['title'] ?? $file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'media';
        $base = $base . '-' . Str::lower(Str::random(8));

        // Private original
        $privateRel = 'magnoolia/media/originals/' . $base . '.' . $ext;
        $privateAbs = storage_path('app/private/' . $privateRel);
        $this->ensureDir(dirname($privateAbs));
        copy($file->getRealPath(), $privateAbs);

        $publicDirAbs = public_path(self::PUBLIC_DIR);
        $thumbDirAbs = $publicDirAbs . '/thumbs';
        $this->ensureDir($publicDirAbs);
        $this->ensureDir($thumbDirAbs);

        $width = null;
        $height = null;
        $publicPath = null;
        $thumbPath = null;

        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            [$width, $height] = $this->rasterPipeline($file->getRealPath(), $ext, $publicDirAbs, $thumbDirAbs, $base);
            $publicPath = self::PUBLIC_DIR . '/' . $base . '.webp';
            $thumbPath = self::PUBLIC_DIR . '/thumbs/' . $base . '-thumb.webp';
        } elseif (in_array($ext, ['svg', 'pdf'], true)) {
            // Copy to public as-is (SVG logos; PDF floor plans are linked, not previewed).
            copy($file->getRealPath(), $publicDirAbs . '/' . $base . '.' . $ext);
            $publicPath = self::PUBLIC_DIR . '/' . $base . '.' . $ext;
            if ($ext === 'svg') {
                $thumbPath = $publicPath; // SVG scales for the thumbnail
                $dims = @getimagesize($file->getRealPath());
                $width = $dims[0] ?? null;
                $height = $dims[1] ?? null;
            }
        }

        return MagnooliaMediaItem::create([
            'title' => $meta['title'] ?? $file->getClientOriginalName(),
            'category' => $meta['category'] ?? 'other',
            'original_name' => $file->getClientOriginalName(),
            'mime' => $file->getMimeType(),
            'size_bytes' => $file->getSize() ?: 0,
            'width' => $width,
            'height' => $height,
            'original_path' => $privateRel,
            'public_path' => $publicPath,
            'thumb_path' => $thumbPath,
            'alt_et' => $meta['alt_et'] ?? null,
            'alt_ru' => $meta['alt_ru'] ?? null,
            'alt_en' => $meta['alt_en'] ?? null,
            'assignment_target' => $meta['assignment_target'] ?? null,
            'uploaded_by' => $meta['uploaded_by'] ?? null,
        ]);
    }

    /** @return array{0:int,1:int} original [width,height] */
    private function rasterPipeline(string $srcPath, string $ext, string $publicDirAbs, string $thumbDirAbs, string $base): array
    {
        $src = match ($ext) {
            'png' => imagecreatefrompng($srcPath),
            'webp' => imagecreatefromwebp($srcPath),
            default => imagecreatefromjpeg($srcPath),
        };
        $w = imagesx($src);
        $h = imagesy($src);

        $this->resampleWebp($src, $w, $h, min(self::MAX_WIDTH, $w), $publicDirAbs . '/' . $base . '.webp', 82);
        $this->resampleWebp($src, $w, $h, min(self::THUMB_WIDTH, $w), $thumbDirAbs . '/' . $base . '-thumb.webp', 78);

        imagedestroy($src);
        return [$w, $h];
    }

    private function resampleWebp($src, int $w, int $h, int $targetW, string $outAbs, int $quality): void
    {
        $targetW = max(1, $targetW);
        $targetH = (int) round($h * ($targetW / $w));
        $dst = imagecreatetruecolor($targetW, $targetH);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $targetW, $targetH, $w, $h);
        imagewebp($dst, $outAbs, $quality);
        imagedestroy($dst);
    }

    /** Is this media's public asset referenced by the active published snapshot? */
    public function isUsedInActivePublication(MagnooliaMediaItem $item): bool
    {
        if (!$item->public_path) {
            return false;
        }
        $active = MagnooliaPublication::query()->where('status', 'active')->orderByDesc('version')->first();
        if (!$active) {
            return false;
        }
        $json = json_encode($active->public_payload ?? [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        return is_string($json) && str_contains($json, $item->public_path);
    }

    /** Delete the DB row and its files. */
    public function delete(MagnooliaMediaItem $item): void
    {
        foreach ([public_path((string) $item->public_path), public_path((string) $item->thumb_path), storage_path('app/private/' . $item->original_path)] as $abs) {
            if ($item && $abs && is_file($abs)) {
                @unlink($abs);
            }
        }
        $item->delete();
    }

    private function ensureDir(string $dir): void
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
    }
}
