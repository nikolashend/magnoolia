<?php

return [
    // Keep disabled publicly until final SVG/EXR/hotspot mapping is confirmed.
    'show_dev_hotspots' => (bool) env('MG_SHOW_DEV_HOTSPOTS', false),

    // Preferred camera for future interactive map layer.
    'default_camera' => 'cam016',
    'alternate_camera' => 'cam009',

    // Unit hotspot structure (future-ready):
    // unit_id => [x_percent, y_percent, polygon_points, render_camera, mask_id]
    // Keep values null/empty until Yellow Studio delivers final mapping.
    'hotspots' => [
        // 'tee-1-1' => [
        //     'unit_id' => 'tee-1-1',
        //     'address' => 'Magnoolia tee 1/1',
        //     'stage' => 1,
        //     'status' => 'available',
        //     'completion' => 'kevad 2027',
        //     'x_percent' => null,
        //     'y_percent' => null,
        //     'polygon_points' => [],
        //     'render_camera' => 'cam016',
        //     'mask_id' => null,
        // ],
    ],
];
