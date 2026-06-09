<?php
/**
 * Magnoolia project configuration
 *
 * GROUND RULES for this file:
 * - Do NOT invent prices. price => null until client provides confirmed hinnatabel.
 * - Do NOT invent private_yard_area. Use null until defined by architect.
 * - Do NOT invent terrace/balcony/storage. Use null until confirmed.
 * - Unit addresses are based on real project address structure from materials.
 * - Stage 1: Magnoolia tee 1 + 3 (keypunch: kevad 2027)
 * - Stage 2: Magnoolia tee 5, 7, 9, 11 (keypunch: kevad 2028)
 * - Status: available | reserved | sold | tbc
 * - plan_type: type-a (4-room 129.6m2) | type-b (5-room 143.2m2, corner)
 */

return [

    'canonical_domain' => env('MAGNOOLIA_CANONICAL_DOMAIN', 'https://magnoolia.ee'),

    'project' => [
        'name'          => 'Magnoolia Kodud',
        'brand_name'    => 'Magnoolia',
        'slogan'        => 'Ridaelamu mugavus. Eramaja privaatsus.',
        'location'      => 'Vaela küla, Kiili vald, Harjumaa',
        'completion_1'  => 'kevad 2027',
        'completion_2'  => 'kevad 2028',
        'homes_count'   => 19,
        'energy_class'  => 'A',
        'developer'     => 'Estlanda OÜ',
        'contact_email' => 'diana@estlanda.ee',
        'contact_phone' => '+37258164078',

        // Phase 11 feature flag — set to true only when client confirms
        // diana_photo_approved: show Diana photo in contact block
        'diana_photo_approved' => false,
    ],

    'stages' => [
        1 => [
            'label'      => 'I etapp',
            'buildings'  => ['Magnoolia tee 1', 'Magnoolia tee 3'],
            'completion' => 'kevad 2027',
            'homes'      => 7,
        ],
        2 => [
            'label'      => 'II etapp',
            'buildings'  => ['Magnoolia tee 5', 'Magnoolia tee 7', 'Magnoolia tee 9', 'Magnoolia tee 11'],
            'completion' => 'kevad 2028',
            'homes'      => 12,
        ],
    ],

    'navigation' => [
        ['id' => 'about',       'label_key' => 'magnoolia.nav.about'],
        ['id' => 'hinnad',      'label_key' => 'magnoolia.nav.homes'],
        ['id' => 'asendiplaan', 'label_key' => 'magnoolia.nav.masterplan'],
        ['id' => 'ehitusinfo',  'label_key' => 'magnoolia.nav.building'],
        ['id' => 'kontakt',     'label_key' => 'magnoolia.nav.contact'],
    ],

    'facts' => [
        ['value' => '19',       'label' => 'kodu',           'icon' => 'home'],
        ['value' => 'A',        'label' => 'energiaklass',   'icon' => 'energy'],
        ['value' => '~129 m2',  'label' => 'elamispind',     'icon' => 'flooring'],
        ['value' => 'kevad 2027', 'label' => 'I etapp',      'icon' => 'calendar'],
        ['value' => '20 min',   'label' => 'Tallinnast',     'icon' => 'location'],
    ],

    'units' => require __DIR__ . '/magnoolia_units.php',

    'campaign' => [
        'enabled'        => true,
        'amount_eur'     => 20000,
        'type'           => 'discount_or_kitchen_package',
        'deadline'       => '2026-07-31',
        'legal_final'    => false,
        'title'          => 'Kampaania',
        'body_et'        => 'Kampaania: võlaõiguslepingu sõlmimisel enne 2026. aasta juuli lõppu kehtib hinnasoodustus 20 000 € hinnakirjas näidatud maksumusest.',
        'body_short_et'  => '20 000 € soodustus — pakkumine kehtib juuli 2026 lõpuni',
        'body_ru'        => 'Акция: при подписании договора до конца июля 2026 года действует скидка 20 000 € от прайс-листа.',
        'body_short_ru'  => 'Скидка 20 000 € — предложение действует до конца июля 2026',
        'body_en'        => 'Campaign: a discount of €20 000 off the listed price applies when signing a contract before the end of July 2026.',
        'body_short_en'  => '€20 000 discount — offer valid until end of July 2026',
        'disclaimer_et'  => 'Täpsemad kampaaniatingimused kinnitab Diana Tali.',
    ],

    'commercial' => [
        'included' => [
            'Iga ridaelamu sektsiooni juures varjualusega 2 parkimiskohta',
            'Kütmata panipaik iga ridaelamu sektsiooni juures',
        ],
        'excluded' => [
            'Kaminakorsten (vilpra 200 mm) ei sisaldu standardlahenduses',
        ],
        'extras' => [
            ['name' => 'Delux viimistluspaketi hinnalisa', 'price' => 8200],
            ['name' => 'Päikesepaneelid koos salvestusseadmega (5kW)', 'price' => 6500],
            ['name' => 'Jahutus elutuba-köök tsoonis (3kW)', 'price' => 2800],
            ['name' => 'Valvesignalisatsiooni ja suitsuandurite kaabeldus koos lõppseadmetega', 'price' => 2500],
            ['name' => 'Valvesignalisatsiooni ja suitsuandurite kaabeldus ilma lõppseadmeteta', 'price' => 600],
            ['name' => 'Videovalve 4 välikaameraga + fonolukk rakendusega', 'price' => 2000],
            ['name' => 'Sauna siseviimistluse hinnalisa koos klaaslahendusega', 'price' => 3500],
            ['name' => 'WC valamukapp ja peegel (Balteco EC-seeria)', 'price' => 800],
            ['name' => 'Bilsey nutikodu lahendus', 'price' => 2500],
        ],
    ],

    'price_visibility' => [
        'stage_1_public' => true,
        'stage_2_public' => false,
    ],

    'statuses' => [
        'available' => 'Vaba',
        'reserved'  => 'Broneeritud',
        'sold'      => 'Müüdud',
        'tbc'       => 'Täpsustamisel',
    ],

    'media' => [
        'hero_video'  => null,
        'hero_poster' => null,
        'logo_light'  => null,
        'logo_dark'   => null,
        'renders'     => [],
        'masterplan'  => null,
    ],

    'seo' => [
        // Production canonical domain. Set MAGNOOLIA_CANONICAL_DOMAIN in .env before launch.
        // Staging: MAGNOOLIA_CANONICAL_DOMAIN=https://magnoolia.adme.ee
        // Production: MAGNOOLIA_CANONICAL_DOMAIN=https://magnoolia.ee
        'production_domain' => env('MAGNOOLIA_PUBLIC_DOMAIN', env('MAGNOOLIA_CANONICAL_DOMAIN', 'https://magnoolia.ee')),
        'staging_domain'    => env('MAGNOOLIA_STAGING_DOMAIN', 'https://magnoolia.adme.ee'),
        // canonical_base: resolves from MAGNOOLIA_PUBLIC_DOMAIN when indexable, otherwise APP_URL
        'canonical_base'    => env('MAGNOOLIA_CANONICAL_DOMAIN', env('MAGNOOLIA_CANONICAL_BASE', 'https://magnoolia.ee')),
        'og_image'          => 'assets/images/magnoolia/Cam001.0000.jpg',

        // Indexing control — MAGNOOLIA_INDEXABLE=true → index,follow,max-image-preview:large
        //                     MAGNOOLIA_INDEXABLE=false (default) → noindex,nofollow
        // Legacy support: MAGNOOLIA_NOINDEX=false also enables indexing
        'indexable' => env('MAGNOOLIA_INDEXABLE', false),
        'env'       => env('MAGNOOLIA_ENV', 'staging'),
        'noindex'   => env('MAGNOOLIA_NOINDEX', true),
    ],

];
