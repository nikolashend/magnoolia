<?php

return [
    'admin_preview_banner' => 'EELVAADE — AVALDAMATA MUUDATUSED',
    'snapshot_current_file' => storage_path('app/magnoolia/published/current.json'),
    'snapshot_version_pattern' => storage_path('app/magnoolia/published/version-%d.json'),
    'asset_base_prefix' => 'assets/magnoolia/',
    'allowed_statuses' => [
        'available',
        'reserved',
        'sold',
        'coming_soon',
    ],
];
