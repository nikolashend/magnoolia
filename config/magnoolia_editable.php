<?php

/**
 * Phase 36, Module A — registry of texts the client may edit.
 *
 * WHY THIS FILE EXISTS
 * --------------------
 * Until now the editable set was a 34-entry list hidden inside a seeder command.
 * Everything else — plan facts, table headings, modal labels, FAQ answers — lived
 * in lang files and could only be changed by a developer. Phase 35.1 measured the
 * cost: of Indrek's 21 corrections, roughly 60% were plain strings he could not
 * touch himself, and a wrong figure ("143,2 m²") stayed live in three languages
 * for months purely because nobody but a developer could correct it.
 *
 * HOW IT WORKS
 * ------------
 * Each entry maps a lang key (without the "magnoolia." prefix) to a human label.
 * `php artisan magnoolia:seed-content` turns this registry into editable rows;
 * `mg_text()` reads the published override and falls back to the lang file, so
 * adding a key here changes nothing visually until someone edits it.
 *
 * ADDING A KEY
 * ------------
 * 1. Make sure the template reads it through `mg_text('some.key')`, not `__()`.
 * 2. Add it below under the right page and group, with a label that describes
 *    WHERE IT APPEARS, not what the key is called. The client is looking for
 *    "the area column heading", not for "pricing.area_total".
 * 3. Run `php artisan magnoolia:seed-content`.
 *
 * Structure: page => ['label' => …, 'groups' => [group label => [key => label]]]
 * The page keys must exist in MagnooliaContentBlock::PAGES.
 */

return [

    'home' => [
        'label' => 'Avaleht (Homepage)',
        'groups' => [
            'Hero' => [
                'hero.h1'           => 'Suur pealkiri',
                'hero.subheadline'  => 'Pealkirja alltekst',
                'hero.cta_primary'  => 'Peamine nupp',
            ],
            'Miks Magnoolia' => [
                'section.why_tagline' => 'Väike pealkiri (eyebrow)',
                'section.why_title'   => 'Ploki pealkiri',
                'section.why_desc'    => 'Ploki tekst',
            ],
        ],
    ],

    'kodud' => [
        'label' => 'Kodud ja hinnad',
        'groups' => [
            'Lehe päis' => [
                'page.kodudjahinnad.page_h1' => 'Lehe pealkiri',
                'page.kodudjahinnad.lead'    => 'Sissejuhatus',
                'page.kodudjahinnad.note'    => 'Märkus hinnatabeli kohal',
            ],
            'Hinnatabel — veergude pealkirjad' => [
                'pricing.address'      => 'Veerg: aadress',
                'pricing.area_total'   => 'Veerg: netopind kokku',
                'pricing.private_land' => 'Veerg: isiklik maa-ala',
                'pricing.rooms'        => 'Veerg: tube',
                'pricing.terrace'      => 'Veerg: terrass',
                'pricing.balcony'      => 'Veerg: rõdu',
                'pricing.parking'      => 'Veerg: parkimine',
                'pricing.price'        => 'Veerg: hind',
                'pricing.status'       => 'Veerg: saadavus',
            ],
            'Planeeringutüübid (Plaan A / Plaan B)' => [
                'page.kodudjahinnad.plans_title'  => 'Ploki pealkiri',
                'page.kodudjahinnad.plans_sub'    => 'Ploki alltekst',
                'page.kodudjahinnad.plan_a_badge' => 'Plaan A — silt kaardi kohal',
                'page.kodudjahinnad.plan_a_title' => 'Plaan A — pealkiri',
                'page.kodudjahinnad.plan_a_size'  => 'Plaan A — pindala rida',
                'page.kodudjahinnad.plan_a_pitch' => 'Plaan A — kirjeldus',
                'page.kodudjahinnad.plan_b_badge' => 'Plaan B — silt kaardi kohal',
                'page.kodudjahinnad.plan_b_title' => 'Plaan B — pealkiri',
                'page.kodudjahinnad.plan_b_size'  => 'Plaan B — pindala rida',
                'page.kodudjahinnad.plan_b_pitch' => 'Plaan B — kirjeldus',
                'page.kodudjahinnad.spec_washrooms' => 'Rida: pesuruume',
            ],
            'Kodu hüpikaken (modal)' => [
                'modal.spec_heated_area' => 'Rida: köetav pind',
                'modal.spec_storage'     => 'Rida: panipaiga pind',
                'modal.spec_net_total'   => 'Rida: netopind kokku',
                'modal.spec_terrace'     => 'Rida: terrass',
                'modal.spec_balcony'     => 'Rida: rõdu',
                'modal.spec_yard'        => 'Rida: isiklik maa-ala',
                'modal.spec_parking'     => 'Rida: parkimine',
                'modal.spec_completion'  => 'Rida: valmimine',
            ],
            'Kodude valiku hüpikaken' => [
                'rowhouse.spec_heated'  => 'Rida: köetav netopind',
                'rowhouse.spec_storage' => 'Rida: panipaiga pind',
                'rowhouse.spec_total'   => 'Rida: netopind kokku',
                'rowhouse.spec_terrace' => 'Rida: terrassi pind',
                'rowhouse.spec_balcony' => 'Rida: rõdu pind',
                'rowhouse.spec_yard'    => 'Rida: isiklik maa-ala',
                'rowhouse.yard_inline'  => 'Pealkirja all olev rida (maa-ala)',
            ],
        ],
    ],

    'asendiplaan' => [
        'label' => 'Asendiplaan',
        'groups' => [
            'Lehe päis' => [
                'page.asendiplaan.page_h1' => 'Lehe pealkiri',
                'page.asendiplaan.lead'    => 'Sissejuhatus',
                'page.asendiplaan.note'    => 'Märkus',
            ],
        ],
    ],

    'asukoht' => [
        'label' => 'Asukoht',
        'groups' => [
            'Lehe päis' => [
                'page.asukoht.page_h1' => 'Lehe pealkiri',
                'page.asukoht.lead'    => 'Sissejuhatus',
            ],
            'Kaart ja asendiplaan' => [
                'page.asukoht.address_label'  => 'Aadressi silt',
                'page.asukoht.map_nearby'     => 'Lähedal asuvate objektide rea algus',
                'page.asukoht.siteplan_title' => 'Asendiplaani ploki pealkiri',
                'page.asukoht.siteplan_sub'   => 'Asendiplaani ploki alltekst',
                'page.asukoht.siteplan_open'  => 'Link "ava suuremalt"',
            ],
        ],
    ],

    'ehitusinfo' => [
        'label' => 'Ehitusinfo',
        'groups' => [
            'Lehe päis' => [
                'page.ehitusinfo.page_h1' => 'Lehe pealkiri',
                'page.ehitusinfo.lead'    => 'Sissejuhatus',
                'page.ehitusinfo.note'    => 'Vastutuse märkus',
            ],
        ],
    ],

    'sisedisain' => [
        'label' => 'Siseviimistlus (Sisedisain)',
        'groups' => [
            'Lehe päis' => [
                'page.sisedisain.page_h1'          => 'Lehe pealkiri',
                'page.sisedisain.lead'             => 'Sissejuhatus',
                'page.sisedisain.note'             => 'Märkus',
                'page.sisedisain.disclaimer_body'  => 'Näidispiltide märkus',
                'page.sisedisain.cta_inquiry'      => 'Nupp "täpsusta viimistlust"',
            ],
            'Viimistluse standard' => [
                'interior.section_title'    => 'Ploki pealkiri',
                'interior.section_subtitle' => 'Ploki alltekst',
                'interior.disclaimer'       => 'Vastutuse märkus',
                'interior.standard_label'   => 'Silt "standard"',
                'interior.paid_label'       => 'Silt "lisatasu eest"',
                'interior.open_larger'      => 'Link "ava suuremalt"',
                'interior.editorial_cta'    => 'Nupp "küsi täpset viimistluspaketti"',
            ],
        ],
    ],

    'galerii' => [
        'label' => 'Galerii',
        'groups' => [
            'Lehe päis' => [
                'page.galerii.page_h1' => 'Lehe pealkiri',
                'page.galerii.lead'    => 'Sissejuhatus',
                'page.galerii.note'    => 'Märkus',
            ],
        ],
    ],

    'ostuprotsess' => [
        'label' => 'Ostuprotsess',
        'groups' => [
            'Lehe päis' => [
                'page.ostuprotsess.page_h1' => 'Lehe pealkiri',
                'page.ostuprotsess.lead'    => 'Sissejuhatus',
                'page.ostuprotsess.note'    => 'Märkus',
            ],
        ],
    ],

    'finantseerimine' => [
        'label' => 'Finantseerimine',
        'groups' => [
            'Lehe päis' => [
                'page.finantseerimine.page_h1' => 'Lehe pealkiri',
                'page.finantseerimine.lead'    => 'Sissejuhatus',
                'page.finantseerimine.note'    => 'Märkus',
            ],
        ],
    ],

    'kkk' => [
        'label' => 'KKK (korduma kippuvad küsimused)',
        'groups' => [
            'Lehe päis' => [
                'page.kkk.page_h1' => 'Lehe pealkiri',
                'page.kkk.lead'    => 'Sissejuhatus',
            ],
        ],
    ],

    'kontakt' => [
        'label' => 'Kontakt',
        'groups' => [
            'Lehe päis' => [
                'page.kontakt.page_h1'     => 'Lehe pealkiri',
                'page.kontakt.lead'        => 'Sissejuhatus',
                'page.kontakt.direct_note' => 'Vastamise aja märkus',
            ],
        ],
    ],

    'footer' => [
        'label' => 'Jalus (Footer)',
        'groups' => [
            'Jalus' => [
                'footer.tagline' => 'Jaluse loosung',
                'footer.desc'    => 'Jaluse kirjeldus',
            ],
        ],
    ],

];
