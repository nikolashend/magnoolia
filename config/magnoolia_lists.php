<?php

/**
 * Phase 36, Module C — the named lists the client may edit.
 *
 * A list is one repeating block on the site. `type` (see config/magnoolia_list_types.php)
 * decides which fields its entries carry; this file only says which lists exist,
 * where they live and what to call them in admin.
 *
 * SAFETY PROPERTY
 * ---------------
 * Same contract as the image slots: a list with nothing published falls back to
 * the array the template ships with. `mg_list()` returns [] in that case and the
 * template keeps its current source. So this can ship before anything is seeded,
 * and the site is byte-identical until someone deliberately edits a list.
 *
 * `source` documents where the shipped content lives — it is what
 * `php artisan magnoolia:seed-lists` reads to give the client a filled-in editor
 * instead of an empty screen.
 *
 * Structure: list key => [type, page, label, description, source]
 */

return [

    'home.gallery_cards' => [
        'type'        => 'feature_cards',
        'page'        => 'home',
        'label'       => 'Avalehe pildikaardid',
        'description' => 'Kaardid galeriiplokis avalehel.',
        'source'      => ['kind' => 'lang', 'key' => 'magnoolia.section.gallery_cards'],
    ],

    'arhitektuur.exterior_elements' => [
        'type'        => 'exterior_elements',
        'page'        => 'arhitektuur',
        'label'       => 'Välisruumi elemendid',
        'description' => 'Terrass, hooviala, parkimine, panipaik — plokid Arhitektuuri lehel.',
        'source'      => ['kind' => 'lang', 'key' => 'magnoolia.page.arhitektuur.features'],
    ],

    'arendajast.projects' => [
        'type'        => 'projects',
        'page'        => 'arendajast',
        'label'       => 'Arendaja teised projektid',
        'description' => 'Kaardid, mis viivad Estlanda teiste arenduste lehtedele.',
        'source'      => ['kind' => 'blade', 'note' => 'pages/magnoolia/arendajast.blade.php — $projects'],
    ],

    'kkk.faq' => [
        'type'        => 'faq',
        'page'        => 'kkk',
        'label'       => 'KKK küsimused',
        'description' => 'Nähtav KKK lehel JA Google’i küsimuste-vastuste plokk — üks ja sama nimekiri.',
        'source'      => ['kind' => 'lang', 'key' => 'magnoolia.page.kkk.groups', 'shape' => 'kkk_groups'],
        // The KKK page shows its questions under seven headings. Carrying the
        // heading on the question keeps that layout while letting the client move
        // a question from one heading to another.
        'extra_fields' => [
            'group' => ['kind' => 'select', 'label' => 'Rubriik', 'options' => 'kkk_group', 'default' => '0'],
        ],
    ],

    'arhitektuur.faq' => [
        'type'        => 'faq',
        'page'        => 'arhitektuur',
        'label'       => 'Arhitektuuri lehe KKK',
        'description' => 'Küsimused arhitektuuri kohta.',
        'source'      => ['kind' => 'lang', 'key' => 'magnoolia.page.arhitektuur.faq_items'],
    ],

    // ── Sisedisain: the equipment lists, one per category ──────────────
    'sisedisain.spec.electrical' => [
        'type'        => 'spec_items',
        'page'        => 'sisedisain',
        'label'       => 'Elektri- ja nutiseadmed',
        'description' => 'Read kategooria „Elektri- ja nutiseadmed“ all.',
        'source'      => ['kind' => 'config', 'key' => 'magnoolia_interiors.categories.electrical.items'],
    ],
    'sisedisain.spec.sanitary' => [
        'type'        => 'spec_items',
        'page'        => 'sisedisain',
        'label'       => 'Sanitaartehnika',
        'description' => 'Read kategooria „Sanitaartehnika“ all.',
        'source'      => ['kind' => 'config', 'key' => 'magnoolia_interiors.categories.sanitary.items'],
    ],
    'sisedisain.spec.tiles' => [
        'type'        => 'spec_items',
        'page'        => 'sisedisain',
        'label'       => 'Plaadid',
        'description' => 'Read kategooria „Plaadid“ all.',
        'source'      => ['kind' => 'config', 'key' => 'magnoolia_interiors.categories.tiles.items'],
    ],
    'sisedisain.spec.finish' => [
        'type'        => 'spec_items',
        'page'        => 'sisedisain',
        'label'       => 'Siseviimistlus',
        'description' => 'Read kategooria „Siseviimistlus“ all.',
        'source'      => ['kind' => 'config', 'key' => 'magnoolia_interiors.categories.finish.items'],
    ],
    'sisedisain.spec.paid' => [
        'type'        => 'spec_items',
        'page'        => 'sisedisain',
        'label'       => 'Lisavalikud lisatasu eest',
        'description' => 'Read kategooria „Lisatasu eest“ all.',
        'source'      => ['kind' => 'config', 'key' => 'magnoolia_interiors.categories.paid.items'],
    ],

    'galerii.items' => [
        'type'        => 'gallery',
        'page'        => 'galerii',
        'label'       => 'Galerii pildid ja järjekord',
        'description' => 'Millised pildid galeriis on ja mis järjekorras — lohista ümber.',
        'source'      => ['kind' => 'media', 'category' => 'gallery'],
    ],

];
