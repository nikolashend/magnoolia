<?php

/*
|--------------------------------------------------------------------------
| Magnoolia Page SEO Config
| Phase 14 — per-page meta, breadcrumbs, slugs
|--------------------------------------------------------------------------
*/

$base = rtrim(env('MAGNOOLIA_CANONICAL_DOMAIN', env('MAGNOOLIA_CANONICAL_BASE', env('APP_URL', 'https://magnoolia.ee'))), '/');

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

        'interior' => [
            'slug'        => 'sisedisain',
            'title'       => 'Magnoolia siseviimistlus — A-energiaklassi kodu sisekujundus',
            'description' => 'Tutvu Magnoolia ridaelamukodude siseviimistluse näidislahendustega — elutuba, magamistuba, vannituba, saun ja köök. Moodne Skandinaavia-stiil Harjumaal.',
            'h1'          => 'Magnoolia siseviimistlus ja sisekujunduse võimalused',
            'breadcrumb'  => 'Sisedisain',
            'canonical'   => $base . '/sisedisain',
        ],

        'architecture' => [
            'slug'        => 'arhitektuur-ja-valisdisain',
            'title'       => 'Magnoolia arhitektuur ja välisdisain — privaatse kodu tunne',
            'description' => 'Magnoolia ridaelamukodud on projekteeritud majaomaniku tunnega: terrass, privaatne hooviala, rõdu ja moodne fassaad. Vaata välisvaateid ja arhitektuuri kirjeldust.',
            'h1'          => 'Arhitektuur ja välisdisain — privaatse kodu tunne ridaelamu mugavusega',
            'breadcrumb'  => 'Arhitektuur',
            'canonical'   => $base . '/arhitektuur-ja-valisdisain',
        ],

        'gallery' => [
            'slug'        => 'galerii',
            'title'       => 'Magnoolia galerii — välisvaated, interjöörid ja plaanid',
            'description' => 'Vaata Magnoolia A-energiaklassi ridaelamukodude renderpilte, interjööri näidiseid, asendiplaanikaarti ja korrusplaane.',
            'h1'          => 'Magnoolia galerii — välisvaated, interjöörid ja asendiplaan',
            'breadcrumb'  => 'Galerii',
            'canonical'   => $base . '/galerii',
        ],

        'purchase' => [
            'slug'        => 'ostuprotsess',
            'title'       => 'Magnoolia kodu ostuprotsess — kuidas osta uut ridaelamukodu',
            'description' => 'Sammud Magnoolia kodu ostmisel: kodu valimine, broneerimine, VÕL, AÕL ja üleandmine. Selge ülevaade ostja teekonnast.',
            'h1'          => 'Magnoolia kodu ostuprotsess',
            'breadcrumb'  => 'Ostuprotsess',
            'canonical'   => $base . '/ostuprotsess',
        ],

        'financing' => [
            'slug'        => 'finantseerimine',
            'title'       => 'Magnoolia kodu finantseerimine ja eelarveplaneerimine',
            'description' => 'Kuidas planeerida Magnoolia kodu ost — eelarve, pangalaen, omafinantseering ja ajaplaan. Üldine ülevaade ilma finantsnõuandeta.',
            'h1'          => 'Finantseerimine ja kodu ostmise planeerimine',
            'breadcrumb'  => 'Finantseerimine',
            'canonical'   => $base . '/finantseerimine',
        ],

        'faq' => [
            'slug'        => 'kkk',
            'title'       => 'KKK — korduma kippuvad küsimused Magnoolia kodude kohta',
            'description' => 'Vastused levinuimatele küsimustele Magnoolia kodude hindade, asukoha, ehitusinfo, siseviimistluse, ostuprotsessi ja kontakti kohta.',
            'h1'          => 'Korduma kippuvad küsimused Magnoolia kodude kohta',
            'breadcrumb'  => 'KKK',
            'canonical'   => $base . '/kkk',
        ],

    ],

];
