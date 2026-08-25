<?php

/**
 * Phase 36, Module C — list types.
 *
 * WHY A TYPE
 * ----------
 * The site's repeating blocks are all arrays in code: 12 cards on the home page,
 * 4 exterior elements on Arhitektuur, the equipment lists on Sisedisain, the sister
 * developments, the gallery, the FAQ. They differ only in which fields each entry
 * carries. Giving a list a *type* means one editor screen can serve all of them:
 * the type says which inputs to draw.
 *
 * FIELD KINDS
 * -----------
 *   text      one-line input
 *   textarea  paragraph
 *   lines     one bullet per line — stored as an array
 *   image     media-library picker (stored as media_item_id, not a path)
 *   url       absolute link
 *   select    fixed choices, `options` below
 *   bool      checkbox
 *
 * `t => true` means the field is per-language (et/ru/en). Everything else is shared
 * across languages: an icon name, a URL and a badge do not get translated.
 * Following Phase 36 decision 4, only Estonian is required — a blank RU/EN falls
 * back to the Estonian text rather than to a stale older translation.
 *
 * Structure: type => [label, item_label, fields[]]
 */

return [

    'feature_cards' => [
        'label'      => 'Pildikaardid (pilt + pealkiri + kolm rida)',
        'item_label' => 'Kaart',
        'fields'     => [
            'image' => ['kind' => 'image',  'label' => 'Pilt'],
            'title' => ['kind' => 'text',   'label' => 'Pealkiri', 't' => true, 'required' => true],
            'alt'   => ['kind' => 'text',   'label' => 'Pildi alt-tekst', 't' => true,
                        'help' => 'Mida pildil näha on. Loevad ekraanilugejad ja Google.'],
            'cap1_icon' => ['kind' => 'select', 'label' => '1. rea ikoon', 'options' => 'icons'],
            'cap1'  => ['kind' => 'text',   'label' => '1. rida', 't' => true],
            'cap2_icon' => ['kind' => 'select', 'label' => '2. rea ikoon', 'options' => 'icons'],
            'cap2'  => ['kind' => 'text',   'label' => '2. rida', 't' => true],
            'cap3_icon' => ['kind' => 'select', 'label' => '3. rea ikoon', 'options' => 'icons'],
            'cap3'  => ['kind' => 'text',   'label' => '3. rida', 't' => true],
        ],
    ],

    'exterior_elements' => [
        'label'      => 'Välisruumi elemendid (suur pilt + tekst + punktid)',
        'item_label' => 'Element',
        'fields'     => [
            'image'   => ['kind' => 'image',    'label' => 'Foto'],
            'kicker'  => ['kind' => 'text',     'label' => 'Ülapealkiri', 't' => true,
                          'help' => 'Lühike sõna pealkirja kohal, nt „Terrass“.'],
            'title'   => ['kind' => 'text',     'label' => 'Pealkiri', 't' => true, 'required' => true],
            'body'    => ['kind' => 'textarea', 'label' => 'Tekst', 't' => true],
            'list'    => ['kind' => 'lines',    'label' => 'Punktid', 't' => true,
                          'help' => 'Üks punkt rea kohta.'],
            'reverse' => ['kind' => 'bool',     'label' => 'Pilt paremal',
                          'help' => 'Vaheldumisi paigutus: iga teine element peegeldatakse.'],
        ],
    ],

    'spec_items' => [
        'label'      => 'Varustuse read (nimetus + märgis)',
        'item_label' => 'Rida',
        'fields'     => [
            // Product names stay in their brand form in every language, so `name`
            // is deliberately NOT translatable — the badge next to it is.
            'name' => ['kind' => 'text',   'label' => 'Nimetus', 'required' => true,
                       'help' => 'Tootenimi jääb igas keeles samaks.'],
            'type' => ['kind' => 'select', 'label' => 'Märgis', 'options' => 'spec_badge', 'default' => 'standard'],
        ],
    ],

    'projects' => [
        'label'      => 'Arendaja projektid (pilt + nimi + link)',
        'item_label' => 'Projekt',
        'fields'     => [
            'image' => ['kind' => 'image', 'label' => 'Foto'],
            'name'  => ['kind' => 'text',  'label' => 'Projekti nimi', 't' => true, 'required' => true],
            'url'   => ['kind' => 'url',   'label' => 'Link projekti lehele'],
        ],
    ],

    'gallery' => [
        'label'      => 'Galerii (pilt + alt + kategooria)',
        'item_label' => 'Pilt',
        'fields'     => [
            'image' => ['kind' => 'image',  'label' => 'Pilt', 'required' => true],
            'alt'   => ['kind' => 'text',   'label' => 'Alt-tekst', 't' => true],
            'cat'   => ['kind' => 'select', 'label' => 'Kategooria', 'options' => 'gallery_cat', 'default' => 'valised'],
        ],
    ],

    'faq' => [
        'label'      => 'Korduma kippuvad küsimused',
        'item_label' => 'Küsimus',
        'fields'     => [
            'q' => ['kind' => 'text',     'label' => 'Küsimus', 't' => true, 'required' => true],
            'a' => ['kind' => 'textarea', 'label' => 'Vastus',  't' => true, 'required' => true],
        ],
        // The visible FAQ and the Google FAQ snippet are generated from this one
        // list, so they can no longer drift apart (they already had — Phase 35.1 #12).
        'feeds_schema' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Option sets referenced by `options` above
    |--------------------------------------------------------------------------
    */
    '_options' => [
        'spec_badge' => [
            'standard' => 'Standard',
            'paid'     => 'Lisatasu eest',
        ],
        // Mirrors the seven groups the KKK page already renders, in page order.
        // A question carries its group so the page keeps its current structure
        // while the client can move a question between groups.
        'kkk_group' => [
            '0' => 'Kodud ja hinnad',
            '1' => 'Asendiplaan',
            '2' => 'Ehitusinfo',
            '3' => 'Siseviimistlus',
            '4' => 'Asukoht',
            '5' => 'Ostuprotsess',
            '6' => 'Kontakt',
        ],
        'gallery_cat' => [
            'valised'  => 'Välisvaated',
            'interjer' => 'Interjöör',
            'keskkond' => 'Keskkond',
        ],
        // The icon set already shipped in the theme; names must match the CSS classes.
        'icons' => [
            'icon-bedroom'     => 'Magamistuba',
            'icon-labyrinth'   => 'Pindala',
            'icon-real-estate' => 'Terrass / rõdu',
            'icon-house'       => 'Maja',
            'icon-garage'      => 'Parkimine',
            'icon-pin'         => 'Asukoht',
            'icon-trophy'      => 'Energiaklass',
            'icon-celemder'    => 'Valmimine',
            'icon-buildings'   => 'Hooned',
            'icon-flooring'    => 'Põrand',
        ],
    ],

];
