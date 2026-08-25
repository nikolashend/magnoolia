<?php

namespace App\View\Composers;

use App\Services\Magnoolia\MagnooliaPublicDataRepository;
use Illuminate\View\View;

class MagnooliaPublicDataComposer
{
    public function __construct(private readonly MagnooliaPublicDataRepository $repository)
    {
    }

    public function compose(View $view): void
    {
        $payload = $this->repository->getPublicPayload();
        $units = $payload['units'] ?? [];
        $settings = $payload['settings'] ?? [];

        $stages = [
            1 => [
                'label' => 'I etapp',
                'buildings' => ['Magnoolia tee 1', 'Magnoolia tee 3'],
                'completion' => $settings['stage_1_completion'] ?? config('magnoolia.stages.1.completion', 'kevad 2027'),
                'homes' => collect($units)->where('stage', 1)->count(),
            ],
            2 => [
                'label' => 'II etapp',
                'buildings' => ['Magnoolia tee 5', 'Magnoolia tee 7', 'Magnoolia tee 9', 'Magnoolia tee 11'],
                'completion' => $settings['stage_2_completion'] ?? config('magnoolia.stages.2.completion', 'kevad 2028'),
                'homes' => collect($units)->where('stage', 2)->count(),
            ],
        ];

        // ── Campaign / Eripakkumine — ONE source for the whole site (Phase 35.1, items 5 + 9)
        //
        // Before this, three places disagreed: the homepage teaser read
        // config/magnoolia.php, the red banner on /kodud-ja-hinnad was a hardcoded
        // lang string, and only the lower banner used the admin Campaign screen. So
        // switching the campaign off in admin left the offer visible on two pages.
        //
        // Rule: once anything has been published, the admin Campaign screen is
        // authoritative — inactive there means the offer disappears everywhere.
        // config/magnoolia.php is only a fallback for a site that has never
        // published (fresh install), so nothing breaks before the first publish.
        $locale = app()->getLocale();
        $hasPublication = ! empty($settings);

        if ($hasPublication) {
            $published = $settings['campaign'] ?? [];
            $active = (bool) ($published['active'] ?? false);
            $body = $active
                ? ($published['note_' . $locale] ?? $published['note_et'] ?? null)
                : null;

            // The gold ribbon on the home page is sized for a one-liner, the red
            // banner for the full sentence. They are separate fields; falling back
            // to the long text keeps the ribbon filled if the short one is blank.
            $short = $active
                ? ($published['note_short_' . $locale] ?? $published['note_short_et'] ?? null)
                : null;

            $legal = $locale === 'et'
                ? ($published['legal_note'] ?? null)
                : ($published['legal_note_' . $locale] ?? $published['legal_note'] ?? null);

            $campaign = [
                'enabled'    => $active && filled($body),
                'title'      => 'KAMPAANIA',
                'body'       => $body,
                'body_short' => filled($short) ? $short : $body,
                'deadline'   => $published['deadline'] ?? null,
                'legal_note' => $legal,
                'source'     => 'admin',
            ];
        } else {
            $cfg = config('magnoolia.campaign', []);

            $campaign = [
                'enabled'    => (bool) ($cfg['enabled'] ?? false),
                'title'      => $cfg['title'] ?? 'KAMPAANIA',
                'body'       => $cfg['body_' . $locale] ?? $cfg['body_et'] ?? null,
                'body_short' => $cfg['body_short_' . $locale] ?? $cfg['body_short_et'] ?? null,
                'deadline'   => $cfg['deadline'] ?? null,
                'legal_note' => $cfg['disclaimer_et'] ?? null,
                'source'     => 'config',
            ];
        }

        $view->with('mgPublic', [
            'units' => $units,
            'settings' => $settings,
            'stages' => $stages,
            'campaign' => $campaign,
            // Phase 35: "included" / "extras" are config-managed marketing content
            // (not part of the unit draft→publish flow), so read them live from
            // config — edits apply immediately without needing a republish.
            'commercial' => config('magnoolia.commercial', $settings['commercial'] ?? []),
            'project' => array_merge(config('magnoolia.project', []), [
                'contact_name' => $settings['sales_contact_name'] ?? config('magnoolia.project.contact_name', 'Diana Tali'),
                'contact_phone' => $settings['sales_contact_phone'] ?? config('magnoolia.project.contact_phone'),
                'contact_email' => $settings['sales_contact_email'] ?? config('magnoolia.project.contact_email'),
            ]),
        ]);
    }
}
