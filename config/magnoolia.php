<?php

/*
|--------------------------------------------------------------------------
| Magnoolia Project Config
|--------------------------------------------------------------------------
| Central config for all project-specific data.
| Do NOT hardcode these values into Blade templates directly.
| Future phases will expand this file with full unit data, media assets, etc.
|--------------------------------------------------------------------------
*/

return [

    /*
    |------------------------------------------------------------------
    | Project identity
    |------------------------------------------------------------------
    */
    'project' => [
        'name'          => 'Magnoolia Kodud',
        'brand_name'    => 'Magnoolia',
        'slogan'        => 'Kodu, mis kestab', // placeholder — final copy in Phase 2
        'location'      => 'Vaela küla, Kiili vald, Harjumaa',
        'completion'    => 'Suvi 2027',        // placeholder
        'homes_count'   => 19,
        'energy_class'  => 'A',
        'developer'     => 'Placeholder OÜ',  // replace with real developer
        'contact_email' => 'info@magnoolia.ee', // placeholder
        'contact_phone' => '+372 000 0000',     // placeholder
    ],

    /*
    |------------------------------------------------------------------
    | Navigation items
    | label is a translation key — resolve with __()
    |------------------------------------------------------------------
    */
    'navigation' => [
        ['id' => 'about',       'label_key' => 'nav.about'],
        ['id' => 'homes',       'label_key' => 'nav.homes'],
        ['id' => 'masterplan',  'label_key' => 'nav.masterplan'],
        ['id' => 'location',    'label_key' => 'nav.location'],
        ['id' => 'contact',     'label_key' => 'nav.contact'],
    ],

    /*
    |------------------------------------------------------------------
    | Facts bar
    | Displayed in hero and standalone facts-bar component
    |------------------------------------------------------------------
    */
    'facts' => [
        [
            'value'   => '19',
            'label'   => 'ridaelamut',        // placeholder — multilingual in Phase 2
            'icon'    => 'home',
        ],
        [
            'value'   => 'A',
            'label'   => 'energiaklass',
            'icon'    => 'energy',
        ],
        [
            'value'   => '600–1200 m²',
            'label'   => 'eraaed',
            'icon'    => 'garden',
        ],
        [
            'value'   => '2027',
            'label'   => 'valmib suvel',
            'icon'    => 'calendar',
        ],
        [
            'value'   => '20 min',
            'label'   => 'Tallinnast',
            'icon'    => 'location',
        ],
    ],

    /*
    |------------------------------------------------------------------
    | Units — Phase 1 sample only (2–3 placeholders)
    | Full 19-unit table to be built in Phase 3/4
    |------------------------------------------------------------------
    | statuses: available | reserved | sold
    |------------------------------------------------------------------
    */
    'units' => [
        [
            'id'               => 1,
            'address'          => 'Magnoolia tee 1, Vaela',
            'unit_number'      => 'M-01',
            'rooms'            => 4,
            'net_area'         => 118.5,
            'terrace_area'     => 18.0,
            'balcony_area'     => 0,
            'storage_area'     => 8.5,
            'parking'          => 2,
            'private_use_area' => 650,
            'price'            => null, // price on request in Phase 1
            'status'           => 'available',
            'floor_plan_1'     => null, // public/images/magnoolia/plans/m01-ground.jpg
            'floor_plan_2'     => null, // public/images/magnoolia/plans/m01-upper.jpg
        ],
        [
            'id'               => 2,
            'address'          => 'Magnoolia tee 2, Vaela',
            'unit_number'      => 'M-02',
            'rooms'            => 4,
            'net_area'         => 122.0,
            'terrace_area'     => 20.0,
            'balcony_area'     => 0,
            'storage_area'     => 8.5,
            'parking'          => 2,
            'private_use_area' => 720,
            'price'            => null,
            'status'           => 'reserved',
            'floor_plan_1'     => null,
            'floor_plan_2'     => null,
        ],
        [
            'id'               => 3,
            'address'          => 'Magnoolia tee 3, Vaela',
            'unit_number'      => 'M-03',
            'rooms'            => 5,
            'net_area'         => 143.0,
            'terrace_area'     => 24.0,
            'balcony_area'     => 10.0,
            'storage_area'     => 10.0,
            'parking'          => 2,
            'private_use_area' => 900,
            'price'            => null,
            'status'           => 'available',
            'floor_plan_1'     => null,
            'floor_plan_2'     => null,
        ],
    ],

    /*
    |------------------------------------------------------------------
    | Unit status labels (resolved per locale in Phase 2)
    |------------------------------------------------------------------
    */
    'statuses' => [
        'available' => 'Saadaval',
        'reserved'  => 'Broneeritud',
        'sold'      => 'Müüdud',
    ],

    /*
    |------------------------------------------------------------------
    | Media — Phase 1 placeholders
    | Replace null values with actual paths when assets are ready
    |------------------------------------------------------------------
    */
    'media' => [
        'hero_video'    => null, // public/images/magnoolia/renders/hero.mp4
        'hero_poster'   => null, // public/images/magnoolia/renders/hero-poster.jpg
        'logo_light'    => null, // public/images/magnoolia/logos/magnoolia-white.svg
        'logo_dark'     => null, // public/images/magnoolia/logos/magnoolia-dark.svg
        'renders'       => [],   // array of render paths
        'masterplan'    => null, // public/images/magnoolia/masterplan/masterplan.jpg
    ],

];
