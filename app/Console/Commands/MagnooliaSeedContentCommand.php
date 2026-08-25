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

    /**
     * Phase 36 Module A — the editable set now lives in config/magnoolia_editable.php,
     * grouped by page and section with labels written for the client ("Veerg: netopind
     * kokku"), not for a developer ("pricing.area_total").
     *
     * It used to be a 34-entry array right here, which meant every new editable text
     * was a code change. Phase 35.1 showed the cost of that: ~60% of the client's
     * corrections were strings he could not reach.
     *
     * @return array<int, array{0: string, 1: string, 2: string, 3: string}> [key, page, label, group]
     */
    private function registry(): array
    {
        $rows = [];
        foreach (config('magnoolia_editable', []) as $page => $definition) {
            foreach ($definition['groups'] ?? [] as $group => $keys) {
                foreach ($keys as $key => $label) {
                    $rows[] = [$key, $page, $label, $group];
                }
            }
        }

        return $rows;
    }

    public function handle(): int
    {
        $force = (bool) $this->option('force');
        $created = 0;
        $updated = 0;
        $sort = 0;

        foreach ($this->registry() as [$key, $page, $label, $group]) {
            $values = [
                'page' => $page,
                'label' => $label,
                'group' => $group,
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
