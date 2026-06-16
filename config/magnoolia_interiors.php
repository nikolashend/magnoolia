<?php

/**
 * Phase 31 — Magnoolia interior-finish / equipment standard data.
 *
 * Structure for the premium /ehitusinfo "Siseviimistlus ja varustuse standard"
 * section. Category copy (title/description) is translated via lang keys
 * (magnoolia.interior.{key}.title / .description). Product NAMES stay in their
 * original brand form (same in every language); the standard / lisatasu badge
 * is translated. `overview` is the optimised proof sheet shown in the detail
 * accordion + lightbox. Image paths are relative to public/.
 *
 * Honest content rules: no invented product names; items flagged 'paid' are
 * shown as "lisatasu eest". A developer-equivalence disclaimer accompanies the
 * section (see magnoolia.interior.disclaimer).
 */

$base = 'assets/magnoolia/siseviimistlus/';

return [

    'editorial' => [
        'image_day'     => $base . 'interior-living-day.webp',
        'image_evening' => $base . 'interior-living-evening.webp',
    ],

    'categories' => [

        'electrical' => [
            'icon'     => 'electrical',
            'overview' => $base . 'electrical-overview.webp',
            'items'    => [
                ['name' => 'Põrandakütte displei', 'type' => 'standard'],
                ['name' => 'Pistikud ja lülitid Jung LS 990, mattvalge', 'type' => 'standard'],
                ['name' => 'Korteri fonotelefon', 'type' => 'standard'],
                ['name' => 'Süvistatavad kohtvalgustid (IP20 / IP65), mattvalge', 'type' => 'standard'],
            ],
        ],

        'sanitary' => [
            'icon'     => 'sanitary',
            'overview' => $base . 'sanitary-overview.webp',
            'items'    => [
                ['name' => 'RAK Resort rimless WC-pott', 'type' => 'standard'],
                ['name' => 'Loputuskasti nupp SLIM (kroom või must)', 'type' => 'standard'],
                ['name' => 'Valamu Balteco Onyx 40', 'type' => 'standard'],
                ['name' => 'Damixa Core valamusegisti', 'type' => 'standard'],
                ['name' => 'ACO plaaditud duširenn', 'type' => 'standard'],
                ['name' => 'Dušiklaas alumiiniumraamis, ulatub laeni', 'type' => 'standard'],
                ['name' => 'Damixa Core dušilift', 'type' => 'standard'],
                ['name' => 'Valamukapp Balteco Nova (aeglaselt sulguv sahtlisüsteem, Gelcast Nova valamarmor)', 'type' => 'standard'],
            ],
        ],

        'tiles' => [
            'icon'     => 'tiles',
            'overview' => $base . 'tiles-overview.webp',
            'items'    => [
                ['name' => 'Seina-/põrandaplaat Pure Alt Bazalt 60×60', 'type' => 'standard'],
                ['name' => 'Seina-/põrandaplaat Freedust Grey 60×60', 'type' => 'standard'],
                ['name' => 'Suure gabariidiga plaadid 60×120 samast seeriast', 'type' => 'paid'],
                ['name' => 'Monpelli White struktuurplaat', 'type' => 'paid'],
            ],
        ],

        'finish' => [
            'icon'     => 'finish',
            'overview' => $base . 'finish-overview.webp',
            'items'    => [
                ['name' => 'Täispuidust raamuks Swedoor', 'type' => 'standard'],
                ['name' => 'Milk Oak Rustic Light', 'type' => 'standard'],
                ['name' => 'Ivory Oak Rustic Natural', 'type' => 'standard'],
                ['name' => 'Seinavärv Tikkurila Symphony Opus II, G497', 'type' => 'standard'],
                ['name' => 'Seinavärv Tikkurila Symphony Opus II, L497', 'type' => 'standard'],
                ['name' => 'Ukselink BETA SLIM (must / harjatud kroom / grafiit)', 'type' => 'standard'],
                ['name' => 'Puitliist 65×15, värvitud', 'type' => 'standard'],
            ],
        ],

        'paid' => [
            'icon'     => 'paid',
            'overview' => $base . 'paid-options-overview.webp',
            'items'    => [
                ['name' => 'Peegel Balteco EC-seeria 80 cm (soe/külm valgus + soojendus)', 'type' => 'paid'],
                ['name' => 'Prantsuse kalasabaparkett Selekt (naturaalne või tumedam antiik)', 'type' => 'paid'],
                ['name' => 'Jahutusseadmed Samsung (sise- ja välisosad)', 'type' => 'paid'],
                ['name' => 'Valamukapp Nu40 valamule', 'type' => 'paid'],
            ],
        ],

    ],
];
