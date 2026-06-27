<?php

/**
 * Phase 29 — Rowhouse selection STATIC presentation config.
 *
 * This file holds only the small, hand-authored bits that are NOT generated.
 * The bulk of the presentation layer (per-row / per-home WebP crops, responsive
 * variants and the map-highlight coordinates) is produced by
 *   scripts/magnoolia-generate-rowhouse-assets.mjs
 * and read at runtime from
 *   public/assets/magnoolia/rowhouse-selection/manifest.json
 * by RowhouseSelectionService.
 *
 * Home FACTS (status, areas, stage, rooms, floorplans) remain the single source
 * of truth in config/magnoolia_units.php → DB → public payload ($mgPublic).
 * Nothing here duplicates them.
 */

return [
    // Path (relative to public/) to the manifest produced by the generator.
    'manifest' => 'assets/magnoolia/rowhouse-selection/manifest.json',

    // Buyer-facing full asendiplaan PDF used by the "Suurenda asendiplaani" link.
    'enlarge_pdf' => 'assets/magnoolia/asendiplaan/asendiplaan.pdf',

    // Canonical display order of the address groups.
    'row_order' => [1, 3, 5, 7, 9, 11],

    // Phase 35: show the 2D "Asukoht arenduse plaanil" locator map (the top-down
    // asendiplaan with a pin) in the home detail + /kodud-ja-hinnad modal.
    // Removed by request — set to true to RESTORE it (no code changes needed).
    'show_location_map' => true,
];
