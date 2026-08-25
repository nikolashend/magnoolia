<?php

namespace App\Console\Commands;

use App\Models\MagnooliaList;
use App\Models\MagnooliaMediaItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Phase 36, Module C — import the lists the site currently ships into the editor.
 *
 * Without this the client opens "Lists" and sees twelve empty rows waiting to be
 * typed in from scratch, which is worse than the code they replace. This reads the
 * lang files / config the templates read today, in all three languages, and writes
 * them as editable entries.
 *
 * Idempotent: a list that already has entries is left alone unless --force is
 * given, so it is safe in a deploy script next to `migrate`.
 *
 * Pictures referenced by those lists are registered in the media library (matched
 * on their path, so re-running does not duplicate them). That way an entry's photo
 * is an ordinary media item the client can swap for another — the same mechanism
 * as everywhere else, with no second "image path" concept.
 */
class MagnooliaSeedListsCommand extends Command
{
    protected $signature = 'magnoolia:seed-lists
                            {--force : Replace entries of lists that already have some}
                            {--list= : Seed only this list key}';

    protected $description = 'Import the site\'s current repeating blocks (cards, FAQ, spec lists, gallery) into the admin list editor';

    private const LOCALES = ['et', 'ru', 'en'];

    public function handle(): int
    {
        $only = $this->option('list');
        $force = (bool) $this->option('force');
        $created = $skipped = 0;

        foreach (MagnooliaList::definitions() as $key => $definition) {
            if ($only && $only !== $key) {
                continue;
            }

            $list = MagnooliaList::query()->firstOrCreate(
                ['list_key' => $key],
                ['type' => $definition['type'], 'page' => $definition['page'] ?? null]
            );
            // Keep type/page in step with the registry if it was edited after seeding.
            $list->fill(['type' => $definition['type'], 'page' => $definition['page'] ?? null])->save();

            if ($list->items()->exists() && ! $force) {
                $this->line(sprintf('  <fg=gray>skip</>   %-32s (%d entries already)', $key, $list->items()->count()));
                $skipped++;
                continue;
            }

            $entries = $this->readSource($key, $definition);
            if ($entries === []) {
                $this->line(sprintf('  <fg=yellow>empty</>  %-32s nothing to import', $key));
                continue;
            }

            DB::transaction(function () use ($list, $entries) {
                $list->items()->delete();
                foreach ($entries as $i => $entry) {
                    $list->items()->create([
                        'sort_order'    => $i,
                        'is_active'     => true,
                        'media_item_id' => $entry['media_item_id'] ?? null,
                        'payload_et'    => $entry['et'] ?? null,
                        'payload_ru'    => $entry['ru'] ?? null,
                        'payload_en'    => $entry['en'] ?? null,
                        'meta'          => $entry['meta'] ?? null,
                    ]);
                }
            });

            $this->line(sprintf('  <fg=green>ok</>     %-32s %d entries', $key, count($entries)));
            $created += count($entries);
        }

        $this->newLine();
        $this->info("Imported {$created} entries; {$skipped} list(s) already had content.");
        $this->line('Nothing is live yet — review under Lists, then Publish Website Changes.');

        return self::SUCCESS;
    }

    /** @return array<int, array<string, mixed>> */
    private function readSource(string $key, array $definition): array
    {
        $source = $definition['source'] ?? [];

        return match ($source['kind'] ?? '') {
            'lang'   => $this->fromLang($definition, $source),
            'config' => $this->fromConfig($source),
            'media'  => $this->fromMedia($source),
            'blade'  => $this->fromBlade($key),
            default  => [],
        };
    }

    /**
     * Read the same lang array in each language and zip the entries together by
     * position — which is how the translations are maintained today.
     */
    private function fromLang(array $definition, array $source): array
    {
        $byLocale = [];
        foreach (self::LOCALES as $locale) {
            $value = __($source['key'], [], $locale);
            $byLocale[$locale] = is_array($value) ? $value : [];
        }

        if (($source['shape'] ?? null) === 'kkk_groups') {
            return $this->fromKkkGroups($byLocale);
        }

        $out = [];
        foreach (array_values($byLocale['et']) as $i => $etRow) {
            if (! is_array($etRow)) {
                continue;
            }
            $rows = ['et' => $etRow];
            foreach (['ru', 'en'] as $locale) {
                $candidate = array_values($byLocale[$locale])[$i] ?? null;
                $rows[$locale] = is_array($candidate) ? $candidate : [];
            }
            $out[] = $this->mapRow($definition['type'], $rows);
        }

        return $out;
    }

    /** The KKK page keeps its questions under seven headings; flatten, carrying the heading. */
    private function fromKkkGroups(array $byLocale): array
    {
        $out = [];
        foreach (array_values($byLocale['et']) as $gi => $group) {
            foreach (array_values($group['faqs'] ?? []) as $qi => $etFaq) {
                $entry = ['meta' => ['group' => (string) $gi], 'et' => ['q' => $etFaq['q'] ?? '', 'a' => $etFaq['a'] ?? '']];
                foreach (['ru', 'en'] as $locale) {
                    $faq = array_values(array_values($byLocale[$locale])[$gi]['faqs'] ?? [])[$qi] ?? null;
                    $entry[$locale] = is_array($faq) ? ['q' => $faq['q'] ?? '', 'a' => $faq['a'] ?? ''] : [];
                }
                $out[] = $entry;
            }
        }

        return $out;
    }

    /** Equipment lists live in config, and product names are the same in every language. */
    private function fromConfig(array $source): array
    {
        $items = config($source['key'], []);
        $out = [];
        foreach ($items as $item) {
            $out[] = [
                'meta' => ['name' => $item['name'] ?? '', 'type' => $item['type'] ?? 'standard'],
                'et'   => [],
            ];
        }

        return $out;
    }

    /** The gallery is already media items; this only gives them an order to drag. */
    private function fromMedia(array $source): array
    {
        $out = [];
        $items = MagnooliaMediaItem::query()->where('category', $source['category'] ?? 'gallery')->orderBy('id')->get();
        foreach ($items as $item) {
            $cat = 'valised';
            if (preg_match('#/gallery/(exterior|interior|environment)/#', (string) $item->public_path, $m)) {
                $cat = ['exterior' => 'valised', 'interior' => 'interjer', 'environment' => 'keskkond'][$m[1]];
            }
            $out[] = [
                'media_item_id' => $item->id,
                'meta' => ['cat' => $cat],
                'et' => ['alt' => (string) $item->alt_et],
                'ru' => ['alt' => (string) $item->alt_ru],
                'en' => ['alt' => (string) $item->alt_en],
            ];
        }

        return $out;
    }

    /**
     * The sister developments are a literal in the Blade file — there is no lang
     * key to read, so the shipped three are listed here. Names are the same in
     * every language.
     */
    private function fromBlade(string $key): array
    {
        if ($key !== 'arendajast.projects') {
            return [];
        }

        $shipped = [
            ['name' => 'Keila Park Residence', 'url' => 'https://keilaresidence.estlanda.ee/', 'img' => 'keila.webp'],
            ['name' => 'Nõmmeliiva kodud',     'url' => 'https://nommeliiva.estlanda.ee/',     'img' => 'nommeliiva.webp'],
            ['name' => 'Kakumäe Residence',    'url' => 'https://kakumae.com/',                'img' => 'kakumae.webp'],
        ];

        $out = [];
        foreach ($shipped as $project) {
            $out[] = [
                'media_item_id' => $this->mediaFor('assets/magnoolia/developments/' . $project['img'], $project['name'], 'other'),
                'meta' => ['url' => $project['url']],
                'et'   => ['name' => $project['name']],
            ];
        }

        return $out;
    }

    /** Translate one shipped row into the entry shape for its list type. */
    private function mapRow(string $type, array $rows): array
    {
        $et = $rows['et'];

        return match ($type) {
            'feature_cards' => [
                'media_item_id' => $this->mediaFor(
                    'assets/images/magnoolia/' . ($et['img'] ?? ''),
                    $et['title'] ?? ($et['img'] ?? ''),
                    'exterior',
                    ['et' => $et['alt'] ?? null, 'ru' => $rows['ru']['alt'] ?? null, 'en' => $rows['en']['alt'] ?? null]
                ),
                'meta' => array_filter([
                    'cap1_icon' => $et['f'][0]['i'] ?? null,
                    'cap2_icon' => $et['f'][1]['i'] ?? null,
                    'cap3_icon' => $et['f'][2]['i'] ?? null,
                ]),
                'et' => $this->cardText($rows['et']),
                'ru' => $this->cardText($rows['ru']),
                'en' => $this->cardText($rows['en']),
            ],

            'exterior_elements' => [
                'media_item_id' => $this->mediaFor(
                    'assets/images/magnoolia/' . ($et['img'] ?? ''),
                    $et['title'] ?? ($et['img'] ?? ''),
                    'exterior'
                ),
                'meta' => ['reverse' => (bool) ($et['reverse'] ?? false)],
                'et' => $this->elementText($rows['et']),
                'ru' => $this->elementText($rows['ru']),
                'en' => $this->elementText($rows['en']),
            ],

            'faq' => [
                'et' => ['q' => $rows['et']['q'] ?? '', 'a' => $rows['et']['a'] ?? ''],
                'ru' => ['q' => $rows['ru']['q'] ?? '', 'a' => $rows['ru']['a'] ?? ''],
                'en' => ['q' => $rows['en']['q'] ?? '', 'a' => $rows['en']['a'] ?? ''],
            ],

            default => ['et' => $et],
        };
    }

    private function cardText(array $row): array
    {
        return array_filter([
            'title' => $row['title'] ?? null,
            'alt'   => $row['alt'] ?? null,
            'cap1'  => $row['f'][0]['v'] ?? null,
            'cap2'  => $row['f'][1]['v'] ?? null,
            'cap3'  => $row['f'][2]['v'] ?? null,
        ], fn ($v) => $v !== null);
    }

    private function elementText(array $row): array
    {
        return array_filter([
            'kicker' => $row['kicker'] ?? null,
            'title'  => $row['title'] ?? null,
            'body'   => $row['body'] ?? null,
            'list'   => $row['list'] ?? null,
        ], fn ($v) => $v !== null);
    }

    /**
     * Find or register a shipped image in the media library.
     *
     * Matched on public_path, so re-running is idempotent and an image used by two
     * lists becomes one library entry — swap it once and both places follow.
     */
    private function mediaFor(string $path, string $title, string $category, array $alt = []): ?int
    {
        $file = trim($path);
        if ($file === '' || str_ends_with($file, '/') || ! is_file(public_path($file))) {
            return null;
        }

        $item = MagnooliaMediaItem::query()->firstOrCreate(
            ['public_path' => $file],
            [
                'title'    => $title !== '' ? $title : basename($file),
                'category' => $category,
                'alt_et'   => $alt['et'] ?? null,
                'alt_ru'   => $alt['ru'] ?? null,
                'alt_en'   => $alt['en'] ?? null,
            ]
        );

        // An existing library entry keeps its own alt text — the client may have
        // improved it, and the lang file is not a better source than that.
        foreach (['et', 'ru', 'en'] as $locale) {
            if (blank($item->{'alt_' . $locale}) && filled($alt[$locale] ?? null)) {
                $item->{'alt_' . $locale} = $alt[$locale];
            }
        }
        if ($item->isDirty()) {
            $item->save();
        }

        return $item->id;
    }
}
