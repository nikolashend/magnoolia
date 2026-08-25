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
    protected $signature = 'magnoolia:seed-gallery
                            {--dedupe : Also collapse duplicates an earlier run created (same photo imported as .jpg and .webp)}';

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
            foreach ($this->picturesIn($abs) as $file) {
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

        if ($this->option('dedupe')) {
            $this->dedupeExisting();
        }

        $this->info("Gallery import complete. created={$created} skipped(existing)={$skipped} total gallery=" . MagnooliaMediaItem::where('category', 'gallery')->count());
        return self::SUCCESS;
    }

    /**
     * One entry per picture, not per file.
     *
     * The same photo is stored in several formats side by side (Cam001.jpg and
     * Cam001.webp are one picture), plus the generated -480w/-768w/-1200w variants.
     * Importing each file separately produced a doubled media library and a
     * doubled gallery list. The public page never showed it because it de-duplicates
     * on render — which is exactly why this went unnoticed until the admin screen
     * put the rows on screen.
     *
     * @return array<int, string> one file name per picture, in directory order
     */
    private function picturesIn(string $abs): array
    {
        $byStem = [];

        foreach (scandir($abs) ?: [] as $file) {
            if (in_array($file, ['.', '..'], true)) {
                continue;
            }
            // Generated responsive variants are not separate pictures.
            if (preg_match('/-\d+w\.\w+$/', $file)) {
                continue;
            }
            if (! preg_match('/\.(jpe?g|png|webp|avif)$/i', $file)) {
                continue;
            }

            $stem = pathinfo($file, PATHINFO_FILENAME);
            $current = $byStem[$stem] ?? null;

            if ($current === null || $this->formatRank($file) < $this->formatRank($current)) {
                $byStem[$stem] = $file;
            }
        }

        return array_values($byStem);
    }

    /** Lower is preferred: keep the most efficient format the picture exists in. */
    private function formatRank(string $file): int
    {
        return match (strtolower(pathinfo($file, PATHINFO_EXTENSION))) {
            'avif' => 0,
            'webp' => 1,
            'jpg', 'jpeg' => 2,
            default => 3,
        };
    }

    /**
     * Collapse duplicates a previous run of this command already created.
     *
     * Anything pointing at the losing row (a gallery list entry, an image slot) is
     * repointed at the survivor first, so nothing loses its picture. Only then is
     * the redundant row deleted.
     */
    private function dedupeExisting(): void
    {
        $groups = MagnooliaMediaItem::query()
            ->where('category', 'gallery')
            ->orderBy('id')
            ->get()
            ->groupBy(fn (MagnooliaMediaItem $m) => preg_replace('~\.[a-z0-9]+$~i', '', (string) $m->public_path));

        $removed = 0;

        foreach ($groups as $items) {
            if ($items->count() < 2) {
                continue;
            }

            $keep = $items->sortBy(fn (MagnooliaMediaItem $m) => $this->formatRank((string) $m->public_path))->first();

            foreach ($items as $item) {
                if ($item->id === $keep->id) {
                    continue;
                }

                // Carry over any alt text the survivor is missing before deleting.
                foreach (['et', 'ru', 'en'] as $locale) {
                    if (blank($keep->{'alt_' . $locale}) && filled($item->{'alt_' . $locale})) {
                        $keep->{'alt_' . $locale} = $item->{'alt_' . $locale};
                    }
                }

                \App\Models\MagnooliaListItem::query()->where('media_item_id', $item->id)
                    ->update(['media_item_id' => $keep->id]);
                \App\Models\MagnooliaMediaSlot::query()->where('media_item_id', $item->id)
                    ->update(['media_item_id' => $keep->id]);

                $item->delete();
                $removed++;
            }

            if ($keep->isDirty()) {
                $keep->save();
            }
        }

        // Repointing can leave two list entries on the same picture — drop the later.
        $collapsed = 0;
        foreach (\App\Models\MagnooliaList::query()->where('type', 'gallery')->with('items')->get() as $list) {
            $seen = [];
            foreach ($list->items as $entry) {
                if ($entry->media_item_id === null) {
                    continue;
                }
                if (isset($seen[$entry->media_item_id])) {
                    $entry->delete();
                    $collapsed++;
                    continue;
                }
                $seen[$entry->media_item_id] = true;
            }

            // Close the gaps the deletions left, so the editor shows 1..n.
            foreach ($list->items()->get() as $position => $entry) {
                if ($entry->sort_order !== $position) {
                    $entry->update(['sort_order' => $position]);
                }
            }
        }

        $this->line("  deduped: removed {$removed} duplicate media item(s), {$collapsed} duplicate list entr(ies).");
    }
}
