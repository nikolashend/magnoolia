<?php

/**
 * Phase 30.2 — HAND-EDITABLE perspective row hotspots for /asendiplaan.
 *
 * This file lets you set the row zones by hand, with STRAIGHT edges (no rounded
 * auto-hulls). It is read at runtime — just edit and refresh (run
 * `php artisan config:clear` if config is cached). No asset regeneration needed.
 *
 * Coordinate system: normalised on the perspective render (1.jpg).
 *   x = 0.0 (left)   … 1.0 (right)
 *   y = 0.0 (top)    … 1.0 (bottom)
 *
 * Each row (keyed by "tee-N"):
 *   'marker'  => [x, y]          — gold pin/label position on the render
 *   'polygon' => [[x,y], [x,y], …] — clickable zone. Straight lines between points,
 *                                    listed clockwise. Use 4 points for a simple
 *                                    quad, or add more for an L-shape / plot outline.
 *
 * To read coordinates visually: open  /asendiplaan?mp_grid=1  — a numbered 0–1
 * grid is drawn over the render so you can pick x/y for each corner.
 *
 * Set 'enabled' => false to ignore this file and use the auto-detected hulls
 * from the generated manifest instead.
 */

return [

    'enabled' => true,

    'perspective' => [
        'tee-11'  => ['marker' => [0.150, 0.734], 'polygon' => [[0.01, 0.644], [0.178, 0.534], [0.324, 0.777], [0.119, 0.976]]],
        'tee-9'  => ['marker' => [0.320, 0.549], 'polygon' => [[0.181, 0.534], [0.358, 0.412], [0.46, 0.541], [0.272, 0.69]]],
        'tee-7'  => ['marker' => [0.482, 0.434], 'polygon' => [[0.358, 0.412], [0.514, 0.313], [0.614, 0.422], [0.461, 0.541]]],
        'tee-5'  => ['marker' => [0.635, 0.312], 'polygon' => [[0.54, 0.291], [0.656, 0.22], [0.773, 0.297], [0.645, 0.4]]],
        'tee-3'  => ['marker' => [0.801, 0.237], 'polygon' => [[0.66, 0.217], [0.782, 0.294], [0.899, 0.21], [0.798, 0.128]]],
        'tee-1' => ['marker' => [0.924, 0.188], 'polygon' => [[0.803, 0.121], [0.867, 0.08], [0.994, 0.184], [0.966, 0.219], [0.925, 0.21], [0.905, 0.201], [0.903, 0.208]]],
    ],

    /*
     * Per-VIEW hotspots for the alternate perspective tabs (the switcher).
     *   'secondary' = "Teine vaade" (3.jpg)
     *   'dusk'      = "Õhtuvaade"   (0.png)
     *
     * EMPTY by default — on those views selection falls back to the row cards
     * (no markers). To add markers/zones for a view:
     *   1) open  /asendiplaan?mp_grid=1
     *   2) switch to that tab (Teine vaade / Õhtuvaade)
     *   3) click the corners, copy the polygon, set the marker, paste below.
     * Same coordinate system / format as 'perspective' above.
     */
    'perspective_views' => [
        'secondary' => [
            'tee-11' => ['marker' => [0.869, 0.147], 'polygon' => [[0.793, 0.057], [0.947, 0.143], [0.892, 0.193], [0.734, 0.105]]],
            'tee-9' => ['marker' => [0.768, 0.212], 'polygon' => [[0.81, 0.284], [0.691, 0.205], [0.773, 0.126], [0.887, 0.201]]],
            'tee-7' => ['marker' => [0.683, 0.297], 'polygon' => [[0.809, 0.284], [0.713, 0.388], [0.598, 0.289], [0.69, 0.207]]],
            'tee-5' => ['marker' => [0.558, 0.426], 'polygon' => [[0.69, 0.419], [0.569, 0.532], [0.447, 0.422], [0.576, 0.313]]],
            'tee-3' => ['marker' => [0.355, 0.575], 'polygon' => [[0.571, 0.534], [0.36, 0.77], [0.233, 0.618], [0.446, 0.422]]],
            'tee-1' => ['marker' => [0.162, 0.817], 'polygon' => [[0.359, 0.774], [0.129, 0.995], [0.071, 0.688], [0.145, 0.625], [0.223, 0.613]]],
         ],
        'dusk' => [
            'tee-11'  => ['marker' => [0.175, 0.712], 'polygon' => [[0.199, 0.511], [0.343, 0.742], [0.141, 0.903], [0.032, 0.613]]],
            'tee-9'  => ['marker' => [0.320, 0.549], 'polygon' => [[0.202, 0.512], [0.375, 0.404], [0.479, 0.52], [0.291, 0.649]]],
            'tee-7'  => ['marker' => [0.503, 0.412], 'polygon' => [[0.38, 0.407], [0.529, 0.322], [0.63, 0.418], [0.479, 0.521]]],
            'tee-5'  => ['marker' => [0.658, 0.302], 'polygon' => [[0.555, 0.302], [0.671, 0.237], [0.782, 0.31], [0.661, 0.398]]],
            'tee-3'  => ['marker' => [0.809, 0.24],  'polygon' => [[0.785, 0.308], [0.673, 0.235], [0.797, 0.161], [0.912, 0.232]]],
            'tee-1' => ['marker' => [0.899, 0.173], 'polygon' => [[0.798, 0.162], [0.876, 0.112], [1, 0.2], [0.98, 0.235], [0.932, 0.229], [0.911, 0.231]]],
        ],
    ],

    /*
     * Per-HOME plot polygons on the CLEAN top-down asendiplaan
     * (magnoolia-asendiplaan-clean-*.webp), shown as the "Asukoht plaanil"
     * support map when a home is selected. Keyed by HOME asset key
     * ("tee-3-4" = Magnoolia tee 3-4). Same coordinate system as above
     * (x: 0 left → 1 right, y: 0 top → 1 bottom on the clean asendiplaan).
     *
     *   'polygon' => [[x,y], …]  — the plot outline, drawn as a gold highlight.
     *   'marker'  => [x, y]      — optional pin/label anchor. If omitted, the
     *                             polygon centroid is used.
     *
     * To pick coordinates visually open  /asendiplaan?my_grid=1  — a numbered
     * 0–1 grid is drawn over the clean asendiplaan with the same click-to-copy
     * picker as the perspective render. EMPTY by default — homes without an
     * entry fall back to the auto-generated single pin from the manifest.
     */
    'asendiplaan' => [
        'tee-1-1' => ['polygon' => [[0.812, 0.897], [0.68, 0.917], [0.644, 0.866], [0.662, 0.823], [0.749, 0.79], [0.764, 0.812], [0.754, 0.825]]],
        'tee-1-2' => ['polygon' => [[0.81, 0.898], [0.753, 0.823], [0.767, 0.816], [0.749, 0.791], [0.804, 0.771], [0.822, 0.796], [0.834, 0.817], [0.817, 0.826], [0.87, 0.892]]],
        'tee-1-3' => ['polygon' => [[0.869, 0.891], [0.817, 0.826], [0.838, 0.817], [0.801, 0.77], [0.842, 0.756], [0.938, 0.882]]],

        'tee-3-1' => ['polygon' => [[0.546, 0.639], [0.654, 0.6], [0.705, 0.633], [0.66, 0.642], [0.669, 0.66], [0.583, 0.69]]],
        'tee-3-2' => ['polygon' => [[0.583, 0.69], [0.684, 0.649], [0.679, 0.635], [0.697, 0.632], [0.734, 0.663], [0.704, 0.677], [0.709, 0.691], [0.61, 0.726]]],
        'tee-3-3' => ['polygon' => [[0.612, 0.725], [0.707, 0.689], [0.7, 0.679], [0.736, 0.664], [0.774, 0.697], [0.738, 0.71], [0.747, 0.721], [0.637, 0.763]]],
        'tee-3-4' => ['polygon' => [[0.636, 0.763], [0.747, 0.72], [0.738, 0.709], [0.793, 0.689], [0.833, 0.742], [0.669, 0.806]]],
    
        'tee-5-1' => ['polygon' => [[0.531, 0.627], [0.446, 0.512], [0.514, 0.484], [0.536, 0.512], [0.52, 0.518], [0.589, 0.605]]],
        'tee-5-2' => ['polygon' => [[0.591, 0.605], [0.521, 0.517], [0.536, 0.512], [0.515, 0.484], [0.572, 0.465], [0.605, 0.512], [0.586, 0.519], [0.638, 0.587]]],
        'tee-5-3' => ['polygon' => [[0.639, 0.586], [0.587, 0.52], [0.607, 0.51], [0.569, 0.464], [0.599, 0.454], [0.613, 0.448], [0.698, 0.564]]],

        'tee-7-1' => ['polygon' => [[0.407, 0.457], [0.323, 0.344], [0.392, 0.32], [0.411, 0.346], [0.399, 0.354], [0.464, 0.44]]],
        'tee-7-2' => ['polygon' => [[0.466, 0.44], [0.397, 0.353], [0.412, 0.346], [0.393, 0.317], [0.445, 0.3], [0.477, 0.348], [0.464, 0.354], [0.518, 0.423]]],
        'tee-7-3' => ['polygon' => [[0.518, 0.425], [0.461, 0.354], [0.482, 0.343], [0.445, 0.299], [0.473, 0.288], [0.487, 0.281], [0.582, 0.407]]],

        'tee-9-1' => ['polygon' => [[0.308, 0.326], [0.228, 0.223], [0.298, 0.195], [0.321, 0.224], [0.304, 0.231], [0.363, 0.306]]],
        'tee-9-2' => ['polygon' => [[0.363, 0.308], [0.305, 0.229], [0.317, 0.223], [0.298, 0.195], [0.352, 0.175], [0.386, 0.222], [0.369, 0.229], [0.417, 0.29]]],
        'tee-9-3' => ['polygon' => [[0.417, 0.289], [0.367, 0.229], [0.387, 0.222], [0.351, 0.175], [0.397, 0.16], [0.479, 0.269]]],

        'tee-11-1' => ['polygon' => [[0.214, 0.187], [0.144, 0.209], [0.083, 0.132], [0.083, 0.12], [0.093, 0.114], [0.168, 0.088], [0.185, 0.115], [0.169, 0.122]]],
        'tee-11-2' => ['polygon' => [[0.214, 0.187], [0.17, 0.123], [0.187, 0.115], [0.168, 0.087], [0.224, 0.071], [0.257, 0.12], [0.237, 0.124], [0.265, 0.169]]],
        'tee-11-3' => ['polygon' => [[0.267, 0.172], [0.236, 0.125], [0.255, 0.116], [0.221, 0.069], [0.305, 0.041], [0.375, 0.135]]],

    ],
];
