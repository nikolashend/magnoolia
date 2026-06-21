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
     * Curated, client-facing editable blocks. Each entry: [lang key (without
     * "magnoolia."), page slug (must exist in MagnooliaContentBlock::PAGES), label].
     * Every key here is wired into a public template via mg_text(), so editing +
     * publishing it changes the live site. Values are pre-filled from lang files,
     * so seeding/publishing changes nothing visually until the client edits.
     */
    private const BLOCKS = [
        // Homepage hero
        ['hero.h1', 'home', 'Homepage — hero headline (H1)'],
        ['hero.subheadline', 'home', 'Homepage — hero subheadline'],
        ['hero.cta_primary', 'home', 'Homepage — primary CTA button'],

        // Kodud ja hinnad
        ['page.kodudjahinnad.page_h1', 'kodud', 'Homes & prices — page heading'],
        ['page.kodudjahinnad.lead', 'kodud', 'Homes & prices — lead paragraph'],
        ['page.kodudjahinnad.note', 'kodud', 'Homes & prices — notice above price table'],

        // Asendiplaan
        ['page.asendiplaan.page_h1', 'asendiplaan', 'Site plan — page heading'],
        ['page.asendiplaan.lead', 'asendiplaan', 'Site plan — lead paragraph'],
        ['page.asendiplaan.note', 'asendiplaan', 'Site plan — notice'],

        // Asukoht
        ['page.asukoht.page_h1', 'asukoht', 'Location — page heading'],
        ['page.asukoht.lead', 'asukoht', 'Location — lead paragraph'],

        // Ehitusinfo
        ['page.ehitusinfo.page_h1', 'ehitusinfo', 'Construction — page heading'],
        ['page.ehitusinfo.lead', 'ehitusinfo', 'Construction — lead paragraph'],
        ['page.ehitusinfo.note', 'ehitusinfo', 'Construction — disclaimer notice'],

        // Sisedisain / siseviimistlus
        ['page.sisedisain.page_h1', 'sisedisain', 'Interior — page heading'],
        ['page.sisedisain.lead', 'sisedisain', 'Interior — lead paragraph'],
        ['page.sisedisain.note', 'sisedisain', 'Interior — notice'],
        ['page.sisedisain.disclaimer_body', 'sisedisain', 'Interior — illustrative-samples disclaimer'],

        // Galerii
        ['page.galerii.page_h1', 'galerii', 'Gallery — page heading'],
        ['page.galerii.lead', 'galerii', 'Gallery — lead paragraph'],
        ['page.galerii.note', 'galerii', 'Gallery — notice'],

        // Ostuprotsess
        ['page.ostuprotsess.page_h1', 'ostuprotsess', 'Buying process — page heading'],
        ['page.ostuprotsess.lead', 'ostuprotsess', 'Buying process — lead paragraph'],
        ['page.ostuprotsess.note', 'ostuprotsess', 'Buying process — notice'],

        // Finantseerimine
        ['page.finantseerimine.page_h1', 'finantseerimine', 'Financing — page heading'],
        ['page.finantseerimine.lead', 'finantseerimine', 'Financing — lead paragraph'],
        ['page.finantseerimine.note', 'finantseerimine', 'Financing — notice'],

        // KKK
        ['page.kkk.page_h1', 'kkk', 'FAQ — page heading'],
        ['page.kkk.lead', 'kkk', 'FAQ — lead paragraph'],

        // Kontakt
        ['page.kontakt.page_h1', 'kontakt', 'Contact — page heading'],
        ['page.kontakt.lead', 'kontakt', 'Contact — lead paragraph'],
        ['page.kontakt.direct_note', 'kontakt', 'Contact — response-time / direct note'],

        // Footer (global)
        ['footer.tagline', 'footer', 'Footer — tagline'],
        ['footer.desc', 'footer', 'Footer — description paragraph'],
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
