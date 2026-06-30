<?php

namespace Database\Seeders;

use App\Models\NavItem;
use Illuminate\Database\Seeder;

class NavItemSeeder extends Seeder
{
    public function run(): void
    {
        NavItem::truncate();

        // Phase 35: top menu mirrors the code fallback in partials/header.blade.php
        // (kakumae-style). Keep these in sync so DB-seeded and fallback menus match.
        $items = [
            [
                'label'      => ['et' => 'Asukoht',         'ru' => 'Расположение', 'en' => 'Location'],
                'route_name' => 'magnoolia.location',
                'sort_order' => 1,
            ],
            [
                'label'      => ['et' => 'Galerii',         'ru' => 'Галерея',      'en' => 'Gallery'],
                'route_name' => 'magnoolia.galerii',
                'sort_order' => 2,
            ],
            [
                'label'      => ['et' => 'Kodud ja hinnad', 'ru' => 'Дома и цены',  'en' => 'Homes & Prices'],
                'route_name' => 'magnoolia.homes',
                'sort_order' => 3,
            ],
            [
                'label'      => ['et' => 'Arhitektuur',     'ru' => 'Архитектура',  'en' => 'Architecture'],
                'route_name' => 'magnoolia.arhitektuur',
                'sort_order' => 4,
            ],
            [
                'label'      => ['et' => 'Sisedisain',      'ru' => 'Дизайн',       'en' => 'Interior'],
                'route_name' => 'magnoolia.sisedisain',
                'sort_order' => 5,
            ],
            [
                'label'      => ['et' => 'Ehitusinfo',      'ru' => 'Строительство', 'en' => 'Building info'],
                'route_name' => 'magnoolia.construction',
                'sort_order' => 6,
            ],
            [
                'label'      => ['et' => 'Arendaja',        'ru' => 'Застройщик',   'en' => 'Developer'],
                'route_name' => 'magnoolia.developer',
                'sort_order' => 7,
            ],
            [
                'label'      => ['et' => 'Kontakt',         'ru' => 'Контакт',      'en' => 'Contact'],
                'route_name' => 'magnoolia.contact',
                'sort_order' => 8,
            ],
        ];

        foreach ($items as $item) {
            NavItem::create(array_merge(['is_active' => true, 'open_blank' => false], $item));
        }
    }
}
