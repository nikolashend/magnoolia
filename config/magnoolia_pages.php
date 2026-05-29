<?php

/*
|--------------------------------------------------------------------------
| Magnoolia Page SEO Config
| Phase 14 — per-page meta, breadcrumbs, slugs
|--------------------------------------------------------------------------
*/

$base = rtrim(env('MAGNOOLIA_CANONICAL_BASE', env('APP_URL', 'https://magnoolia.ee')), '/');

return [

    /*
    | Slug map — ET is canonical, RU/EN ready for Phase 15+
    */
    'slugs' => [
        'et' => [
            'homes'        => 'kodud-ja-hinnad',
            'site_plan'    => 'asendiplaan',
            'location'     => 'asukoht',
            'construction' => 'ehitusinfo',
            'contact'      => 'kontakt',
        ],
        'ru' => [
            'homes'        => 'doma-i-tseny',
            'site_plan'    => 'genplan',
            'location'     => 'raspolozhenie',
            'construction' => 'stroitelstvo',
            'contact'      => 'kontakt',
        ],
        'en' => [
            'homes'        => 'homes-and-prices',
            'site_plan'    => 'site-plan',
            'location'     => 'location',
            'construction' => 'construction-info',
            'contact'      => 'contact-us',
        ],
    ],

    /*
    | Per-page SEO metadata (ET primary)
    */
    'pages' => [

        'home' => [
            'slug'        => '',
            'title'       => 'Magnoolia Kodud — A-energiaklassi ridaelamukodud Vaelas Tallinna lähedal',
            'description' => 'Magnoolia Kodud on 19 A-energiaklassi ridaelamukodu Vaela külas, Kiili vallas. Privaatne hooviala, läbimõeldud plaanid, I etapp kevad 2027 ja II etapp kevad 2028.',
            'h1'          => 'A-energiaklassi ridaelamukodud Tallinna lähedal',
            'breadcrumb'  => 'Avaleht',
            'canonical'   => $base,
        ],

        'homes' => [
            'slug'        => 'kodud-ja-hinnad',
            'title'       => 'Magnoolia kodud ja hinnad — A-energiaklassi ridaelamud Vaelas',
            'description' => 'Vaata Magnoolia 19 A-energiaklassi ridaelamukodu etappe, plaane, saadavust ja hinnainfot Vaela külas, Kiili vallas, Tallinna lähedal.',
            'h1'          => 'Magnoolia kodud ja hinnad',
            'breadcrumb'  => 'Kodud ja hinnad',
            'canonical'   => $base . '/kodud-ja-hinnad',
        ],

        'site_plan' => [
            'slug'        => 'asendiplaan',
            'title'       => 'Magnoolia asendiplaan — 19 kodu Vaela külas, Kiili vallas',
            'description' => 'Vaata Magnoolia kodude paiknemist, etappe ja krundiloogikat Vaela külas. Interaktiivne kodude kaart lisatakse pärast EXR/SVG/hotspot mappingu kinnitamist.',
            'h1'          => 'Magnoolia asendiplaan',
            'breadcrumb'  => 'Asendiplaan',
            'canonical'   => $base . '/asendiplaan',
        ],

        'location' => [
            'slug'        => 'asukoht',
            'title'       => 'Magnoolia asukoht — ridaelamukodud Vaelas Tallinna lähedal',
            'description' => 'Magnoolia asub Vaela külas, Kiili vallas, Tallinna lähedal. Rahulik kodukeskkond, kiire ühendus linna, koolide, lasteaedade ja igapäevateenustega.',
            'h1'          => 'Magnoolia asukoht — Vaela küla, Kiili vald, Tallinna lähedal',
            'breadcrumb'  => 'Asukoht',
            'canonical'   => $base . '/asukoht',
        ],

        'construction' => [
            'slug'        => 'ehitusinfo',
            'title'       => 'Magnoolia ehitusinfo — A-energiaklass, maasoojuspump ja ventilatsioon',
            'description' => 'Tutvu Magnoolia kodude tehniliste lahendustega: A-energiaklass, maasoojuspump, ventilatsioon, päikesepaneelide valmidus, EV-laadimise valmidus ja läbimõeldud ehitusloogika.',
            'h1'          => 'Magnoolia ehitusinfo ja tehnilised lahendused',
            'breadcrumb'  => 'Ehitusinfo',
            'canonical'   => $base . '/ehitusinfo',
        ],

        'contact' => [
            'slug'        => 'kontakt',
            'title'       => 'Kontakt — küsi Magnoolia kodu pakkumist',
            'description' => 'Võta ühendust Diana Taliga ja küsi Magnoolia kodude saadavust, plaani, hinda või broneerimise järgmist sammu.',
            'h1'          => 'Küsi Magnoolia kodu kohta pakkumist',
            'breadcrumb'  => 'Kontakt',
            'canonical'   => $base . '/kontakt',
        ],

    ],

];
