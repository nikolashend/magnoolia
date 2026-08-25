<?php

namespace App\Console\Commands;

use App\Models\MagnooliaSetting;
use Illuminate\Console\Command;

/**
 * Import the campaign that ships in config/magnoolia.php into the admin Campaign
 * screen, so the client starts from the live wording instead of a blank form.
 *
 * WHY THIS EXISTS
 * ---------------
 * Phase 35.1 made the admin Campaign screen the single source once anything has
 * been published — that was the point: the 20 000 € offer used to be hardcoded in
 * three disagreeing places and nobody could retire it. But it left a sharp edge:
 * publishing for the first time with an untouched settings row makes the offer
 * disappear from the site, because "no campaign in admin" is read as "no campaign".
 * The text is not lost — it is still in config — but nobody would guess that from
 * the admin screen.
 *
 * The fix follows the same rule as the lists: seed the editor from what the site
 * already ships, then let the editor be authoritative. Nothing is inferred at
 * render time, so switching the campaign off in admin still switches it off
 * everywhere — which is the behaviour Indrek asked for.
 *
 * Idempotent: a campaign already filled in admin is left alone unless --force.
 */
class MagnooliaSeedCampaignCommand extends Command
{
    protected $signature = 'magnoolia:seed-campaign
                            {--force : Overwrite a campaign already filled in admin}';

    protected $description = 'Import the campaign wording from config into the admin Campaign screen (idempotent)';

    public function handle(): int
    {
        $config = config('magnoolia.campaign', []);

        if ($config === []) {
            $this->warn('config/magnoolia.php has no campaign block — nothing to import.');
            return self::SUCCESS;
        }

        $settings = MagnooliaSetting::query()->latest('id')->first() ?? MagnooliaSetting::query()->create([]);

        $alreadyFilled = filled($settings->campaign_note_et);
        if ($alreadyFilled && ! $this->option('force')) {
            $this->line('  <fg=gray>skip</>  the admin campaign already has text (use --force to replace it).');
            $this->line('  Edit it under Campaign in the admin.');

            return self::SUCCESS;
        }

        $amount = (float) ($config['amount_eur'] ?? 0);

        $settings->fill([
            // Wording: prefer what visitors actually saw. The red banner on the home
            // page and on /kodud-ja-hinnad rendered `magnoolia.pricing.special_offer`
            // until Phase 35.1 replaced it with the campaign source. config's own
            // `body_*` is a second, differently worded copy of the same offer — one
            // of the three disagreeing sources that phase unified — so importing it
            // instead would quietly reword the live promise.
            'campaign_note_et'        => $this->wording('et') ?? ($config['body_et'] ?? null),
            'campaign_note_ru'        => $this->wording('ru') ?? ($config['body_ru'] ?? null),
            'campaign_note_en'        => $this->wording('en') ?? ($config['body_en'] ?? null),
            'campaign_active'         => (bool) ($config['enabled'] ?? false),
            // config's own `type` is a marketing label ("discount_or_kitchen_package");
            // the admin screen wants how the figure is shown, and this is a fixed sum.
            'campaign_discount_type'  => $amount > 0 ? 'fixed' : 'text',
            'campaign_discount_cents' => $amount > 0 ? (int) round($amount * 100) : null,
            // The one-line ribbon text — config's own `body_short_*`, which is what
            // the home page printed before Phase 35.1 routed it through admin.
            'campaign_note_short_et'  => $config['body_short_et'] ?? null,
            'campaign_note_short_ru'  => $config['body_short_ru'] ?? null,
            'campaign_note_short_en'  => $config['body_short_en'] ?? null,
            'campaign_deadline'       => $config['deadline'] ?? null,
            // The small print beside the ribbon was hardcoded per language in the
            // template, so it has no config key. These are the exact strings the
            // site showed; config's `disclaimer_et` is a differently worded variant
            // that was never on screen.
            'campaign_legal_note'     => 'Täpsed tingimused kinnitab Diana.',
            'campaign_legal_note_ru'  => 'Точные условия уточняет Diana.',
            'campaign_legal_note_en'  => 'Exact terms confirmed by Diana.',
        ])->save();

        $this->info('Campaign imported into the admin.');
        $this->line('  ET: ' . \Illuminate\Support\Str::limit((string) $settings->campaign_note_et, 90));
        $this->line('  Deadline: ' . ($settings->campaign_deadline?->toDateString() ?? '—')
            . '   Discount: ' . ($settings->campaign_discount_cents ? number_format($settings->campaign_discount_cents / 100, 0, ',', ' ') . ' €' : '—'));
        $this->newLine();
        $this->line('Not live yet — review under Campaign, then Publish Website Changes.');

        return self::SUCCESS;
    }

    /**
     * The sentence the banner rendered before Phase 35.1, in one language.
     *
     * Returns null when the key is missing, so the caller can fall back to config.
     */
    private function wording(string $locale): ?string
    {
        $key = 'magnoolia.pricing.special_offer';
        $value = __($key, [], $locale);

        return is_string($value) && $value !== $key ? $value : null;
    }
}
