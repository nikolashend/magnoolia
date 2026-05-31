<?php

namespace Database\Seeders;

use App\Models\NavItem;
use Illuminate\Database\Seeder;

class NavItemSeeder extends Seeder
{
    public function run(): void
    {
        NavItem::truncate();

        $items = [
            [
                'label'      => ['et' => 'Avaleht',         'ru' => 'Главная',          'en' => 'Home'],
                'route_name' => 'home',
                'sort_order' => 1,
            ],
            [
                'label'      => ['et' => 'Kodud ja hinnad', 'ru' => 'Дома и цены',       'en' => 'Homes & prices'],
                'route_name' => 'magnoolia.homes',
                'sort_order' => 2,
            ],
            [
                'label'      => ['et' => 'Asendiplaan',     'ru' => 'План',                 'en' => 'Site plan'],
                'route_name' => 'magnoolia.site-plan',
                'sort_order' => 3,
            ],
            [
                'label'      => ['et' => 'Asukoht',         'ru' => 'Расположение',      'en' => 'Location'],
                'route_name' => 'magnoolia.location',
                'sort_order' => 4,
            ],
            [
                'label'      => ['et' => 'Ehitusinfo',      'ru' => 'Строительство',     'en' => 'Building info'],
                'route_name' => 'magnoolia.construction',
                'sort_order' => 5,
            ],
            [
                'label'      => ['et' => 'Kontakt',         'ru' => 'Контакт',           'en' => 'Contact'],
                'route_name' => 'magnoolia.contact',
                'sort_order' => 6,
            ],
        ];

        foreach ($items as $item) {
            NavItem::create(array_merge(['is_active' => true, 'open_blank' => false], $item));
        }
    }
}
