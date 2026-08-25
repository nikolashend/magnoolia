<?php

/**
 * Phase 36, Module B — image slots the client may reassign.
 *
 * WHY THIS FILE EXISTS
 * --------------------
 * Every picture on the site is referenced in a template by file name. Swapping one
 * — even for another render already sitting in the media library — is a code change
 * plus a deploy. Phase 35.1 spent real time on exactly this: the evening LED render
 * had to be placed by hand, then moved by hand when it landed in the wrong block.
 *
 * HOW IT WORKS
 * ------------
 * A slot is a named position on the site. The client binds it to a media-library
 * item in admin; the binding travels through the normal draft → publish flow
 * (Phase 36 decision 2), so previews and rollback behave like they do for texts.
 *
 * `mg_slot('home.intro.image')` returns the published image, or `default` when the
 * slot is unbound — so the site looks exactly as it does today until someone
 * deliberately changes something.
 *
 * ADDING A SLOT
 * -------------
 * 1. Replace the hardcoded <img> in the template with `mg_slot('your.slot')`.
 * 2. Register it below with the current file as `default`, so nothing changes yet.
 * 3. `alt` is the fallback alt text; once bound, the media item's own alt_et/ru/en wins.
 *
 * Structure: slot key => [page, group, label, default, alt]
 */

return [

    // ── Avaleht ────────────────────────────────────────────────────────
    'home.intro.image' => [
        'page'    => 'home',
        'group'   => 'Tutvustus',
        'label'   => 'Tutvustuse ploki foto',
        'default' => 'assets/images/magnoolia/Cam020.0000.jpg',
        'alt'     => 'Magnoolia ridaelamute õhtune fassaadivaade LED-valgustusega',
    ],
    'home.why.image' => [
        'page'    => 'home',
        'group'   => 'Miks Magnoolia',
        'label'   => 'Ploki suur foto',
        'default' => 'assets/images/magnoolia/Cam005.0000.jpg',
        'alt'     => 'Magnoolia A-energiaklassi kodud Vaela külas',
    ],

    // ── Asukoht ────────────────────────────────────────────────────────
    'asukoht.siteplan.image' => [
        'page'    => 'asukoht',
        'group'   => 'Asendiplaan',
        'label'   => 'Arhitekti asendiplaani joonis',
        'default' => 'assets/magnoolia/asendiplaan/asendiplaan-hires.webp',
        'alt'     => 'Magnoolia arenduse arhitekti asendiplaan Opmani tee tähisega',
    ],

    // ── Alamlehtede päisefotod ─────────────────────────────────────────
    'header.asukoht' => [
        'page'    => 'asukoht',
        'group'   => 'Lehe päis',
        'label'   => 'Päise taustafoto',
        'default' => 'assets/images/magnoolia/magnoolia_cam07.jpg',
        'alt'     => 'Magnoolia asukoht Vaela külas',
    ],
    'header.kodud' => [
        'page'    => 'kodud',
        'group'   => 'Lehe päis',
        'label'   => 'Päise taustafoto',
        'default' => 'assets/images/magnoolia/Cam004.0000.jpg',
        'alt'     => 'Magnoolia kodud ja hinnad',
    ],
    'header.sisedisain' => [
        'page'    => 'sisedisain',
        'group'   => 'Lehe päis',
        'label'   => 'Päise taustafoto',
        'default' => 'assets/images/magnoolia/Interior 1.jpg',
        'alt'     => 'Magnoolia siseviimistlus',
    ],
    'header.galerii' => [
        'page'    => 'galerii',
        'group'   => 'Lehe päis',
        'label'   => 'Päise taustafoto',
        'default' => 'assets/images/magnoolia/Cam005.0000.jpg',
        'alt'     => 'Magnoolia galerii',
    ],

    // ── Siseviimistlus: musta taustaga tõenduslehed ────────────────────
    'sisedisain.proof.electrical' => [
        'page'    => 'sisedisain',
        'group'   => 'Viimistluse tõenduslehed',
        'label'   => 'Elektri- ja nutiseadmed',
        'default' => 'assets/magnoolia/siseviimistlus/electrical-overview.webp',
        'alt'     => 'Magnoolia elektri- ja nutiseadmete näidised',
    ],
    'sisedisain.proof.sanitary' => [
        'page'    => 'sisedisain',
        'group'   => 'Viimistluse tõenduslehed',
        'label'   => 'Sanitaartehnika',
        'default' => 'assets/magnoolia/siseviimistlus/sanitary-overview.webp',
        'alt'     => 'Magnoolia sanitaartehnika näidised',
    ],
    'sisedisain.proof.tiles' => [
        'page'    => 'sisedisain',
        'group'   => 'Viimistluse tõenduslehed',
        'label'   => 'Plaadid ja vannitoa viimistlus',
        'default' => 'assets/magnoolia/siseviimistlus/tiles-overview.webp',
        'alt'     => 'Magnoolia plaadivalikute näidised',
    ],
    'sisedisain.proof.finish' => [
        'page'    => 'sisedisain',
        'group'   => 'Viimistluse tõenduslehed',
        'label'   => 'Siseviimistlus',
        'default' => 'assets/magnoolia/siseviimistlus/finish-overview.webp',
        'alt'     => 'Magnoolia siseviimistluse näidised',
    ],
    'sisedisain.proof.paid' => [
        'page'    => 'sisedisain',
        'group'   => 'Viimistluse tõenduslehed',
        'label'   => 'Lisavalikud lisatasu eest',
        'default' => 'assets/magnoolia/siseviimistlus/paid-options-overview.webp',
        'alt'     => 'Magnoolia lisavalikute näidised',
    ],

];
