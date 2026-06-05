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

        $campaign = [
            'enabled' => (bool) ($settings['campaign']['active'] ?? false),
            'title' => 'KAMPAANIA',
            'body' => null,
        ];

        if ($campaign['enabled']) {
            $locale = app()->getLocale();
            $noteKey = 'note_' . $locale;
            $campaign['body'] = $settings['campaign'][$noteKey]
                ?? $settings['campaign']['note_et']
                ?? config('magnoolia.campaign.body');
        }

        $view->with('mgPublic', [
            'units' => $units,
            'settings' => $settings,
            'stages' => $stages,
            'campaign' => $campaign,
            'commercial' => $settings['commercial'] ?? config('magnoolia.commercial', []),
            'project' => array_merge(config('magnoolia.project', []), [
                'contact_name' => $settings['sales_contact_name'] ?? config('magnoolia.project.contact_name', 'Diana Tali'),
                'contact_phone' => $settings['sales_contact_phone'] ?? config('magnoolia.project.contact_phone'),
                'contact_email' => $settings['sales_contact_email'] ?? config('magnoolia.project.contact_email'),
            ]),
        ]);
    }
}
