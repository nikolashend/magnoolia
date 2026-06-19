<?php

namespace App\Console\Commands;

use App\Models\MagnooliaContentBlock;
use Illuminate\Console\Command;

/**
 * Phase 33.1 — seed the Page-Texts CMS with a curated set of editable blocks,
 * pre-filled from the current ET/RU/EN lang values so publishing them changes
 * nothing visually until the client edits. Idempotent (firstOrCreate by key).
 */
class MagnooliaSeedContentCommand extends Command
{
    protected $signature = 'magnoolia:seed-content {--force : Refresh ET/RU/EN from lang files for existing blocks}';

    protected $description = 'Seed/refresh the Magnoolia Page-Texts CMS blocks from the current lang files.';

    /** [key (without "magnoolia."), page, label] */
    private const BLOCKS = [
        ['page.kodudjahinnad.page_h1', 'kodud', 'Kodud & hinnad — page heading'],
        ['page.kodudjahinnad.lead', 'kodud', 'Kodud & hinnad — lead paragraph'],
        ['page.kodudjahinnad.note', 'kodud', 'Kodud & hinnad — notice above price table'],
        ['page.kontakt.page_h1', 'kontakt', 'Contact — page heading'],
        ['page.kontakt.lead', 'kontakt', 'Contact — lead paragraph'],
        ['page.kontakt.direct_note', 'kontakt', 'Contact — response-time / direct note'],
    ];

    public function handle(): int
    {
        $force = (bool) $this->option('force');
        $created = 0;
        $updated = 0;
        $sort = 0;

        foreach (self::BLOCKS as [$key, $page, $label]) {
            $values = [
                'page' => $page,
                'label' => $label,
                'sort_order' => $sort++,
                'is_active' => true,
                'et' => $this->langValue($key, 'et'),
                'ru' => $this->langValue($key, 'ru'),
                'en' => $this->langValue($key, 'en'),
            ];

            $existing = MagnooliaContentBlock::query()->where('key', $key)->first();
            if (!$existing) {
                MagnooliaContentBlock::query()->create(array_merge(['key' => $key], $values));
                $created++;
            } elseif ($force) {
                $existing->fill($values)->save();
                $updated++;
            }
        }

        $this->info("Content blocks seeded. created={$created} updated={$updated} total=" . MagnooliaContentBlock::count());
        return self::SUCCESS;
    }

    private function langValue(string $key, string $locale): ?string
    {
        $full = 'magnoolia.' . $key;
        $val = trans($full, [], $locale);
        return is_string($val) && $val !== $full ? $val : null;
    }
}
