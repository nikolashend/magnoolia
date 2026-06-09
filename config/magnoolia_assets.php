<?php

/**
 * Magnoolia Phase 26 — Asset Manifest
 *
 * Maps logical asset keys to public paths and metadata.
 * Source ingestion: materials/ (project root) → public/assets/magnoolia/
 *
 * Missing assets (not found locally, awaiting client delivery):
 *  - Diana Tali photo (8-kontakt/Diana Tali.jpg)
 *  - Bigbank logo (9-logod/Bigpank/bigbank_logo_rgb.pdf)
 *  - Aet Piel logo + photo (9-logod/AET PIEL/)
 *  - Sisedisain PPTX (6-sisedisain/Magnoolia kodud Prestige.pptx)
 *  - 3D interior gallery (5-galerii/3d-interjoor-final-10-10-23/)
 *  - 3D exterior renders beyond Cam001/004/005/014 (5-galerii/3d-eksterjoor-finaal/)
 *  - Magnoolia/Estlanda vector logos (9-logod/)
 */

return [

    // ── Location / Asukoht ───────────────────────────────────────────
    'location' => [
        [
            'key'                => 'vaela_lasteaed',
            'public_path'        => 'assets/magnoolia/location/vaela-lasteaed.webp',
            'category'           => 'education',
            'real_location_photo'=> true,
            'priority'           => 10,
            'use_on'             => ['asukoht', 'homepage'],
            'alt'                => [
                'et' => 'Vaela lasteaed Magnoolia kodude lähedal',
                'ru' => 'Детский сад Vaela рядом с домами Magnoolia',
                'en' => 'Vaela kindergarten near Magnoolia homes',
            ],
        ],
        [
            'key'                => 'vaela_lasteaed_2',
            'public_path'        => 'assets/magnoolia/location/vaela-lasteaed-2.webp',
            'category'           => 'education',
            'real_location_photo'=> true,
            'priority'           => 9,
            'use_on'             => ['asukoht'],
            'alt'                => [
                'et' => 'Vaela lasteaed — hoov ja mänguväljak',
                'ru' => 'Детский сад Vaela — двор и площадка',
                'en' => 'Vaela kindergarten — yard and playground',
            ],
        ],
        [
            'key'                => 'vaela_lasteaed_3',
            'public_path'        => 'assets/magnoolia/location/vaela-lasteaed-3.jpg',
            'category'           => 'education',
            'real_location_photo'=> true,
            'priority'           => 8,
            'use_on'             => ['asukoht'],
            'alt'                => [
                'et' => 'Vaela lasteaed — õueala',
                'ru' => 'Детский сад Vaela — территория',
                'en' => 'Vaela kindergarten — outdoor area',
            ],
        ],
        [
            'key'                => 'kiili_kool',
            'public_path'        => 'assets/magnoolia/location/kiili-kool.jpg',
            'category'           => 'education',
            'real_location_photo'=> true,
            'priority'           => 9,
            'use_on'             => ['asukoht'],
            'alt'                => [
                'et' => 'Kiili kool Harjumaal',
                'ru' => 'Школа Kiili в уезде Харьюмаа',
                'en' => 'Kiili school in Harju County',
            ],
        ],
        [
            'key'                => 'kiili_kool_2',
            'public_path'        => 'assets/magnoolia/location/kiili-kool-2.jpg',
            'category'           => 'education',
            'real_location_photo'=> true,
            'priority'           => 7,
            'use_on'             => ['asukoht'],
            'alt'                => [
                'et' => 'Kiili kool — fassaad',
                'ru' => 'Школа Kiili — фасад',
                'en' => 'Kiili school — facade',
            ],
        ],
        [
            'key'                => 'kiili_algkool',
            'public_path'        => 'assets/magnoolia/location/kiili-algkool.webp',
            'category'           => 'education',
            'real_location_photo'=> true,
            'priority'           => 7,
            'use_on'             => ['asukoht'],
            'alt'                => [
                'et' => 'Kiili algkool',
                'ru' => 'Начальная школа Kiili',
                'en' => 'Kiili primary school',
            ],
        ],
        [
            'key'                => 'kiili_spordimaja',
            'public_path'        => 'assets/magnoolia/location/kiili-spordimaja.jpg',
            'category'           => 'sport',
            'real_location_photo'=> true,
            'priority'           => 8,
            'use_on'             => ['asukoht', 'homepage'],
            'alt'                => [
                'et' => 'Kiili spordihoone',
                'ru' => 'Спортивный зал Kiili',
                'en' => 'Kiili sports centre',
            ],
        ],
        [
            'key'                => 'kiili_spordihall',
            'public_path'        => 'assets/magnoolia/location/kiili-spordihall.jpg',
            'category'           => 'sport',
            'real_location_photo'=> true,
            'priority'           => 7,
            'use_on'             => ['asukoht'],
            'alt'                => [
                'et' => 'Kiili spordihall',
                'ru' => 'Спортивный центр Kiili',
                'en' => 'Kiili sports hall',
            ],
        ],
        [
            'key'                => 'kiili_selver',
            'public_path'        => 'assets/magnoolia/location/kiili-selver.jpg',
            'category'           => 'shopping',
            'real_location_photo'=> true,
            'priority'           => 8,
            'use_on'             => ['asukoht', 'homepage'],
            'alt'                => [
                'et' => 'Kiili Selver — igapäevane toidupood',
                'ru' => 'Магазин Selver в Kiili',
                'en' => 'Kiili Selver supermarket',
            ],
        ],
        [
            'key'                => 'kurna_park',
            'public_path'        => 'assets/magnoolia/location/kurna-park.jpg',
            'category'           => 'shopping',
            'real_location_photo'=> true,
            'priority'           => 8,
            'use_on'             => ['asukoht', 'homepage'],
            'alt'                => [
                'et' => 'Kurna Park ostukeskus lähedal',
                'ru' => 'Торговый центр Kurna Park рядом',
                'en' => 'Kurna Park shopping centre nearby',
            ],
        ],
        [
            'key'                => 'kurna_park_2',
            'public_path'        => 'assets/magnoolia/location/kurna-park-2.jpg',
            'category'           => 'shopping',
            'real_location_photo'=> true,
            'priority'           => 6,
            'use_on'             => ['asukoht'],
            'alt'                => [
                'et' => 'Kurna Park',
                'ru' => 'Kurna Park',
                'en' => 'Kurna Park',
            ],
        ],
        [
            'key'                => 'ikea',
            'public_path'        => 'assets/magnoolia/location/ikea.jpg',
            'category'           => 'shopping',
            'real_location_photo'=> true,
            'priority'           => 7,
            'use_on'             => ['asukoht'],
            'alt'                => [
                'et' => 'IKEA Tallinn lähedal',
                'ru' => 'IKEA рядом с Таллинном',
                'en' => 'IKEA near Tallinn',
            ],
        ],
        [
            'key'                => 'kiili_decathlon',
            'public_path'        => 'assets/magnoolia/location/kiili-decathlon.jpg',
            'category'           => 'sport',
            'real_location_photo'=> true,
            'priority'           => 7,
            'use_on'             => ['asukoht'],
            'alt'                => [
                'et' => 'Decathlon Kiili lähedal',
                'ru' => 'Decathlon рядом с Kiili',
                'en' => 'Decathlon near Kiili',
            ],
        ],
        [
            'key'                => 'kergliiklusteed',
            'public_path'        => 'assets/magnoolia/location/kergliiklusteed.jpg',
            'category'           => 'transport',
            'real_location_photo'=> true,
            'priority'           => 8,
            'use_on'             => ['asukoht'],
            'alt'                => [
                'et' => 'Kergliiklustee Kiili piirkonnas',
                'ru' => 'Велодорожка в районе Kiili',
                'en' => 'Cycle path in Kiili area',
            ],
        ],
        [
            'key'                => 'kiili_cycling',
            'public_path'        => 'assets/magnoolia/location/kiili-cycling.jpg',
            'category'           => 'sport',
            'real_location_photo'=> false,
            'priority'           => 6,
            'use_on'             => ['asukoht'],
            'alt'                => [
                'et' => 'Jalgrattasõit Kiili ümbruses',
                'ru' => 'Велопрогулки в окрестностях Kiili',
                'en' => 'Cycling around Kiili',
            ],
        ],
        [
            'key'                => 'kiili_loodus',
            'public_path'        => 'assets/magnoolia/location/kiili-loodus.jpg',
            'category'           => 'nature',
            'real_location_photo'=> true,
            'priority'           => 8,
            'use_on'             => ['asukoht'],
            'alt'                => [
                'et' => 'Kiili looduslik ümbrus',
                'ru' => 'Природа вокруг Kiili',
                'en' => 'Natural surroundings of Kiili',
            ],
        ],
        [
            'key'                => 'hea_uhendus',
            'public_path'        => 'assets/magnoolia/location/hea-uhendus-tallinnaga.avif',
            'category'           => 'transport',
            'real_location_photo'=> false,
            'priority'           => 8,
            'use_on'             => ['asukoht'],
            'alt'                => [
                'et' => 'Hea ühendus Tallinnaga',
                'ru' => 'Хорошая связь с Таллинном',
                'en' => 'Good connection to Tallinn',
            ],
        ],
        [
            'key'                => 'turvaline_keskkond',
            'public_path'        => 'assets/magnoolia/location/turvaline-keskkond.avif',
            'category'           => 'family',
            'real_location_photo'=> false,
            'priority'           => 7,
            'use_on'             => ['asukoht'],
            'alt'                => [
                'et' => 'Turvaline ja kogukondlik keskkond Kiilis',
                'ru' => 'Безопасная и сплочённая среда в Kiili',
                'en' => 'Safe and community-oriented environment in Kiili',
            ],
        ],
    ],

    // ── Gallery — Exterior ───────────────────────────────────────────
    'gallery' => [
        'exterior' => [
            ['key' => 'cam001', 'public_path' => 'assets/magnoolia/gallery/exterior/Cam001.jpg', 'alt' => ['et' => 'Magnoolia ridaelamud — välisvaade 1', 'ru' => 'Внешний вид 1', 'en' => 'Exterior view 1'], 'priority' => 10],
            ['key' => 'cam004', 'public_path' => 'assets/magnoolia/gallery/exterior/Cam004.jpg', 'alt' => ['et' => 'Magnoolia ridaelamud — välisvaade 2', 'ru' => 'Внешний вид 2', 'en' => 'Exterior view 2'], 'priority' => 9],
            ['key' => 'cam005', 'public_path' => 'assets/magnoolia/gallery/exterior/Cam005.jpg', 'alt' => ['et' => 'Magnoolia ridaelamud — välisvaade 3', 'ru' => 'Внешний вид 3', 'en' => 'Exterior view 3'], 'priority' => 8],
            ['key' => 'cam014', 'public_path' => 'assets/magnoolia/gallery/exterior/Cam014.jpg', 'alt' => ['et' => 'Magnoolia ridaelamud — välisvaade 4', 'ru' => 'Внешний вид 4', 'en' => 'Exterior view 4'], 'priority' => 7],
            ['key' => 'cam007', 'public_path' => 'assets/magnoolia/gallery/exterior/magnoolia_cam07.jpg', 'alt' => ['et' => 'Magnoolia ridaelamud — välisvaade 5', 'ru' => 'Внешний вид 5', 'en' => 'Exterior view 5'], 'priority' => 6],
            ['key' => 'cam009', 'public_path' => 'assets/magnoolia/gallery/exterior/magnoolia_cam09.jpg', 'alt' => ['et' => 'Magnoolia ridaelamud — välisvaade 6', 'ru' => 'Внешний вид 6', 'en' => 'Exterior view 6'], 'priority' => 5],
        ],
        'interior' => [
            ['key' => 'int1', 'public_path' => 'assets/magnoolia/gallery/interior/Interior-1.jpg', 'alt' => ['et' => 'Magnoolia kodu sisevaade — elutuba', 'ru' => 'Интерьер — гостиная', 'en' => 'Interior — living room'], 'priority' => 10],
            ['key' => 'int2', 'public_path' => 'assets/magnoolia/gallery/interior/Interior-2.jpg', 'alt' => ['et' => 'Magnoolia kodu sisevaade — köök', 'ru' => 'Интерьер — кухня', 'en' => 'Interior — kitchen'], 'priority' => 9],
            ['key' => 'int3', 'public_path' => 'assets/magnoolia/gallery/interior/Interior-3.jpg', 'alt' => ['et' => 'Magnoolia kodu sisevaade — magamistuba', 'ru' => 'Интерьер — спальня', 'en' => 'Interior — bedroom'], 'priority' => 8],
            ['key' => 'int4', 'public_path' => 'assets/magnoolia/gallery/interior/Interior-4.jpg', 'alt' => ['et' => 'Magnoolia kodu sisevaade — vannituba', 'ru' => 'Интерьер — ванная', 'en' => 'Interior — bathroom'], 'priority' => 7],
            ['key' => 'int5a', 'public_path' => 'assets/magnoolia/gallery/interior/Interior-5-1.jpg', 'alt' => ['et' => 'Magnoolia kodu sisevaade — trepp', 'ru' => 'Интерьер — лестница', 'en' => 'Interior — staircase'], 'priority' => 6],
            ['key' => 'int5b', 'public_path' => 'assets/magnoolia/gallery/interior/Interior-5-2.jpg', 'alt' => ['et' => 'Magnoolia kodu sisevaade — terrass', 'ru' => 'Интерьер — терраса', 'en' => 'Interior — terrace'], 'priority' => 5],
        ],
        'environment' => [
            ['key' => 'env_lasteaed', 'public_path' => 'assets/magnoolia/gallery/environment/vaela-lasteaed.webp', 'alt' => ['et' => 'Vaela lasteaed', 'ru' => 'Детский сад Vaela', 'en' => 'Vaela kindergarten'], 'priority' => 10],
            ['key' => 'env_cycling', 'public_path' => 'assets/magnoolia/gallery/environment/kiili-cycling.jpg', 'alt' => ['et' => 'Jalgrattasõit', 'ru' => 'Велопрогулка', 'en' => 'Cycling'], 'priority' => 9],
            ['key' => 'env_loodus', 'public_path' => 'assets/magnoolia/gallery/environment/kiili-loodus.jpg', 'alt' => ['et' => 'Looduslik ümbrus', 'ru' => 'Природное окружение', 'en' => 'Natural surroundings'], 'priority' => 8],
            ['key' => 'env_uhendus', 'public_path' => 'assets/magnoolia/gallery/environment/hea-uhendus-tallinnaga.avif', 'alt' => ['et' => 'Ühendus Tallinnaga', 'ru' => 'Связь с Таллинном', 'en' => 'Connection to Tallinn'], 'priority' => 7],
            ['key' => 'env_turvaline', 'public_path' => 'assets/magnoolia/gallery/environment/turvaline-keskkond.avif', 'alt' => ['et' => 'Turvaline keskkond', 'ru' => 'Безопасная среда', 'en' => 'Safe environment'], 'priority' => 6],
        ],
    ],

    // ── Sisedisain (Prestige) ────────────────────────────────────────
    'sisedisain' => [
        // No PPTX source delivered yet — images pending from 6-sisedisain/
        // Placeholder entries for when assets arrive
    ],

    // ── People ──────────────────────────────────────────────────────
    'people' => [
        [
            'key'         => 'diana_tali',
            'public_path' => 'assets/magnoolia/people/diana-tali.webp',
            'source_note' => 'MISSING — awaiting delivery from 8-kontakt/Diana Tali.jpg',
            'available'   => false,
            'alt'         => [
                'et' => 'Diana Tali — Müügikonsultant, Magnoolia',
                'ru' => 'Диана Тали — консультант по продажам',
                'en' => 'Diana Tali — Sales Consultant, Magnoolia',
            ],
        ],
    ],

    // ── Logos ───────────────────────────────────────────────────────
    'logos' => [
        [
            'key'         => 'magnoolia_dark',
            'public_path' => 'assets/magnoolia/logos/magnoolia-dark.svg',
            'source_note' => 'MISSING — awaiting delivery from 9-logod/Magnoolia/',
            'available'   => false,
        ],
        [
            'key'         => 'estlanda',
            'public_path' => 'assets/magnoolia/logos/estlanda.svg',
            'source_note' => 'MISSING — awaiting delivery from 9-logod/Estlanda/',
            'available'   => false,
        ],
        [
            'key'         => 'bigbank',
            'public_path' => 'assets/magnoolia/logos/bigbank.svg',
            'source_note' => 'MISSING — awaiting delivery from 9-logod/Bigpank/ (PDF/AI needs conversion)',
            'available'   => false,
        ],
        [
            'key'         => 'aet_piel',
            'public_path' => 'assets/magnoolia/logos/aet-piel.png',
            'source_note' => 'MISSING — awaiting delivery from 9-logod/AET PIEL/',
            'available'   => false,
        ],
    ],

    // ── Finance ─────────────────────────────────────────────────────
    'finance' => [
        [
            'key'         => 'bigbank_loan_info',
            'type'        => 'pdf',
            'source_note' => 'MISSING — awaiting 9-logod/Bigpank/BB Kodulaen.pdf',
            'available'   => false,
        ],
    ],

    // ── Floor plans ─────────────────────────────────────────────────
    'floorplans' => [
        ['key' => 'm1_floor1', 'public_path' => 'assets/magnoolia/floorplans/M1_1korrus.pdf', 'building' => 1, 'floor' => 1],
        ['key' => 'm1_floor2', 'public_path' => 'assets/magnoolia/floorplans/M1_2korrus.pdf', 'building' => 1, 'floor' => 2],
        ['key' => 'm3_floor1', 'public_path' => 'assets/magnoolia/floorplans/M3_1korrus.pdf', 'building' => 3, 'floor' => 1],
        ['key' => 'm3_floor2', 'public_path' => 'assets/magnoolia/floorplans/M3_2korrus.pdf', 'building' => 3, 'floor' => 2],
        ['key' => 'm5_floor1', 'public_path' => 'assets/magnoolia/floorplans/M5_1korrus.pdf', 'building' => 5, 'floor' => 1],
        ['key' => 'm5_floor2', 'public_path' => 'assets/magnoolia/floorplans/M5_2korrus.pdf', 'building' => 5, 'floor' => 2],
        ['key' => 'm7_floor1', 'public_path' => 'assets/magnoolia/floorplans/M7_1korrus.pdf', 'building' => 7, 'floor' => 1],
        ['key' => 'm7_floor2', 'public_path' => 'assets/magnoolia/floorplans/M7_2korrus.pdf', 'building' => 7, 'floor' => 2],
        ['key' => 'm9_floor1', 'public_path' => 'assets/magnoolia/floorplans/M9_1korrus.pdf', 'building' => 9, 'floor' => 1],
        ['key' => 'm9_floor2', 'public_path' => 'assets/magnoolia/floorplans/M9_2korrus.pdf', 'building' => 9, 'floor' => 2],
        ['key' => 'm11_floor1','public_path' => 'assets/magnoolia/floorplans/M11_1korrus.pdf','building' => 11,'floor' => 1],
        ['key' => 'm11_floor2','public_path' => 'assets/magnoolia/floorplans/M11_2korrus.pdf','building' => 11,'floor' => 2],
    ],

    // ── Asendiplaan ─────────────────────────────────────────────────
    'asendiplaan' => [
        [
            'key'         => 'asendiplaan_pdf',
            'public_path' => 'assets/magnoolia/floorplans/asendiplaan-_ASENDIPLAAN.pdf',
            'available'   => true,
        ],
    ],
];
