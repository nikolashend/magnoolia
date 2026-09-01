<?php

namespace App\Services\Magnoolia;

use Illuminate\Support\Facades\Cache;

/**
 * RowhouseSelectionService — Phase 29
 *
 * Builds the premium "row → home → detail" selection view-models for
 * /asendiplaan, /kodud-ja-hinnad and the home-detail modal.
 *
 * Data sourcing (deliberate, documented):
 *  - HOME FACTS (address, stage, completion, plan, rooms, net area, private-use
 *    land area, parking, floorplans) come from the canonical hinnatabel in
 *    config/magnoolia_units.php — the verified source of truth for the spec.
 *  - LIVE STATUS (vaba / broneeritud / müüdud / täpsustamisel) is overlaid from
 *    the published payload (admin-managed availability via MagnooliaPublication),
 *    matched by building + section. Falls back to the canonical status.
 *  - GENERATED PRESENTATION (WebP crops + map-highlight coordinates) comes from
 *    public/assets/magnoolia/rowhouse-selection/manifest.json.
 *
 * It never reads the DB directly and never emits hidden prices (no price_cents).
 */
class RowhouseSelectionService
{
    public function __construct(
        private readonly MagnooliaUnitDiscoveryService $discovery,
    ) {
    }

    private function rowOrder(): array
    {
        return (array) config('magnoolia_rowhouses.row_order', [1, 3, 5, 7, 9, 11]);
    }

    /**
     * All rows as view-models, in canonical order, each with its homes.
     *
     * @return array<int, array<string, mixed>>
     */
    public function rows(): array
    {
        $manifestRows = [];
        foreach (($this->manifest()['rows'] ?? []) as $r) {
            $manifestRows[(int) $r['building']] = $r;
        }

        $canonicalByBuilding = $this->canonicalByBuilding();
        $live = $this->liveStatusIndex();

        $rows = [];
        foreach ($this->rowOrder() as $building) {
            $canon = $canonicalByBuilding[$building] ?? [];
            if (empty($canon)) {
                continue; // server-side guard against the historic "0 homes" bug
            }

            $mRow = $manifestRows[$building] ?? [];
            $mHomes = [];
            foreach (($mRow['homes'] ?? []) as $mh) {
                $mHomes[$mh['unit_key']] = $mh;
            }

            $homes = array_map(fn (array $u) => $this->homeViewModel($u, $mHomes, $live), $canon);
            $first = $canon[0];

            // Perspective hotspot: hand-editable config/magnoolia_hotspots.php wins
            // over the auto-detected manifest hull (straight, hand-set polygons).
            $perspective = $mRow['perspective'] ?? null;
            $hotCfg = (array) config('magnoolia_hotspots.perspective', []);
            if ((bool) config('magnoolia_hotspots.enabled', false) && isset($hotCfg['tee-' . $building])) {
                $h = $hotCfg['tee-' . $building];
                $perspective = [
                    'marker' => $h['marker'] ?? ($perspective['marker'] ?? null),
                    'hull'   => $h['polygon'] ?? ($perspective['hull'] ?? null),
                ];
            }

            $rows[] = [
                'building'            => $building,
                'pos'                 => $mRow['pos'] ?? ('tee-' . $building),
                'title'               => 'Magnoolia tee ' . $building,
                'stage'               => (int) ($first['stage'] ?? 1),
                'completion'          => $first['completion'] ?? null,
                'home_count'          => count($homes),
                'availability_counts' => $this->availabilityCounts($homes),
                'row_image'           => $mRow['image'] ?? null,
                'map_highlight'       => $mRow['map_highlight'] ?? null,
                'perspective'         => $perspective, // {marker, hull} on the render (config-overridable)
                'homes'               => $homes,
            ];
        }

        return $rows;
    }

    /** @return array<int, array<string, mixed>> */
    public function allHomes(): array
    {
        $homes = [];
        foreach ($this->rows() as $row) {
            foreach ($row['homes'] as $home) {
                $homes[] = $home;
            }
        }
        return $homes;
    }

    /** @return array<int, array<string, mixed>> */
    public function homesForRow(int $building): array
    {
        foreach ($this->rows() as $row) {
            if ($row['building'] === $building) {
                return $row['homes'];
            }
        }
        return [];
    }

    /** Find a home by payload unit_key ("B3-S1"), slug, or asset key ("tee-3-1"). */
    public function findHome(string $key): ?array
    {
        foreach ($this->allHomes() as $home) {
            if ($home['unit_key'] === $key || $home['slug'] === $key || $home['asset_key'] === $key) {
                return $home;
            }
        }
        return null;
    }

    /** Clean asendiplaan asset variants from the manifest (or null). */
    public function asendiplaanImage(): ?array
    {
        return $this->manifest()['asendiplaan']['clean'] ?? null;
    }

    /** Perspective render (primary selector) asset variants from the manifest. */
    public function perspectiveImage(): ?array
    {
        return $this->manifest()['perspective']['image'] ?? ($this->manifest()['overview']['primary'] ?? null);
    }

    /**
     * Ordered perspective view set for the switcher. Each entry:
     * ['key', 'label', 'image' => variants, 'hotspots' => bool].
     * Only the primary view is hotspot-calibrated.
     *
     * @return array<int, array<string, mixed>>
     */
    public function perspectiveViews(): array
    {
        $views = $this->manifest()['perspective']['views'] ?? [];
        if (!empty($views)) {
            return $views;
        }
        $img = $this->perspectiveImage();
        return $img ? [['key' => 'primary', 'label' => 'view_primary', 'image' => $img, 'hotspots' => true]] : [];
    }

    /** Floor-plan image variants for a plan type (fallback), or null. */
    public function floorplansForType(?string $planType): ?array
    {
        if ($planType === null) {
            return null;
        }
        return $this->manifest()['floorplans'][$planType] ?? null;
    }

    /** Authoritative per-building floor-plan sheets for a building number, or null. */
    public function floorplansForBuilding(int $building): ?array
    {
        return $this->manifest()['floorplans_by_building'][(string) $building] ?? null;
    }

    /** Overview render assets from the manifest. */
    public function overview(): array
    {
        return $this->manifest()['overview'] ?? ['primary' => null, 'secondary' => null, 'has_secondary_view' => false];
    }

    /** Relative public path to the "enlarge" asendiplaan PDF. */
    public function enlargePdf(): ?string
    {
        return config('magnoolia_rowhouses.enlarge_pdf');
    }

    /**
     * Cache-busting token for generated rowhouse assets (the manifest's mtime).
     * Appended as ?v=… so regenerated images (stable filenames) refresh in browsers.
     */
    public function assetVersion(): string
    {
        return (string) Cache::remember('magnoolia.rowhouse.assetver', 60, function () {
            $rel = (string) config('magnoolia_rowhouses.manifest', 'assets/magnoolia/rowhouse-selection/manifest.json');
            $path = public_path($rel);
            return is_file($path) ? (string) filemtime($path) : '0';
        });
    }

    // ---------------------------------------------------------------------

    /**
     * @param array<string,mixed> $canon  canonical unit (config/magnoolia_units)
     * @param array<string,array> $mHomes manifest homes keyed by asset key
     * @param array<string,array> $live   live status index keyed by "b-s"
     * @return array<string,mixed>
     */
    private function homeViewModel(array $canon, array $mHomes, array $live): array
    {
        $building = $this->canonBuilding($canon);
        $section  = $this->canonSection($canon);
        $bs       = $building . '-' . $section;
        $assetKey = 'tee-' . $building . '-' . $section;
        $mh       = $mHomes[$assetKey] ?? [];
        $liveRow  = $live[$bs] ?? null;

        $status      = $liveRow['status'] ?? ($canon['status'] ?? 'tbc');
        $unitKey     = $liveRow['unit_key'] ?? ($canon['id'] ?? $assetKey);
        $slug        = $liveRow['slug'] ?? ($canon['id'] ?? $assetKey);
        $stage       = (int) ($canon['stage'] ?? 1);
        // Price comes ONLY from the live publication (never the unconfirmed config
        // price). If nothing is published, price stays hidden ("to be confirmed").
        $pricePublic = (bool) ($liveRow['price_public'] ?? false);
        $price       = $pricePublic ? ($liveRow['price'] ?? null) : null;

        /**
         * Measurements come from the publication when there is one.
         *
         * This mapper took every number from config/magnoolia_units.php and only the
         * status and price from the published payload. So a terrace corrected in the
         * admin from 47,1 to 37,5 was saved and published — the price table showed
         * the new value, but this view model (the home-detail modal) kept showing the
         * config one. Same for rooms, net area, balcony, storage, plot and parking.
         *
         * `array_key_exists` rather than `??`: a field the client deliberately
         * cleared must read as unknown, not silently fall back to the config number
         * it was corrected away from. A publication that predates a field simply
         * lacks the key, and the config value is used.
         */
        $live = fn (string $field, mixed $fallback): mixed => is_array($liveRow) && array_key_exists($field, $liveRow)
            ? $liveRow[$field]
            : $fallback;

        $area = fn (mixed $v): ?float => ($v === null || $v === '') ? null : (float) $v;

        $cta = $this->discovery->ctaContext([
            'unit_key'     => $unitKey,
            'slug'         => $slug,
            'address'      => $canon['address'] ?? null,
            'stage'        => $stage,
            'status'       => $status,
            'price_public' => $pricePublic,
        ], 'home_detail_modal');

        // Per-home plot polygon on the clean asendiplaan: hand-editable
        // config/magnoolia_hotspots.php → 'asendiplaan' wins over the
        // auto-generated single map point. Falls back to the manifest pin.
        $mapHighlight = $mh['map_highlight'] ?? null;
        $mapPolygon   = null;
        if ((bool) config('magnoolia_hotspots.enabled', false)) {
            $aCfg = (array) config('magnoolia_hotspots.asendiplaan', []);
            $a    = $aCfg[$assetKey] ?? null;
            if (!empty($a['polygon'])) {
                $mapPolygon = $a['polygon'];
            }
            if (!empty($a['marker'])) {
                $mapHighlight = ['x' => $a['marker'][0], 'y' => $a['marker'][1]];
            } elseif ($mapPolygon) {
                $sx = 0.0;
                $sy = 0.0;
                foreach ($mapPolygon as $p) {
                    $sx += $p[0];
                    $sy += $p[1];
                }
                $n = count($mapPolygon);
                $mapHighlight = ['x' => round($sx / $n, 4), 'y' => round($sy / $n, 4)];
            }
        }

        return [
            'unit_key'          => $unitKey,                 // payload key (CTA-consistent)
            'asset_key'         => $assetKey,                // "tee-3-1"
            'slug'              => $slug,
            'address'           => $canon['address'] ?? null,        // "Magnoolia tee 3/1"
            'display_address'   => 'Magnoolia tee ' . $building . '-' . $section, // "Magnoolia tee 3-1"
            'building'          => $building,
            'section'           => $section,
            'stage'             => $stage,
            'completion'        => $canon['completion'] ?? null,
            'status'            => $status,
            'plan_type'         => $live('plan_type', $canon['plan_type'] ?? null),
            'plan_label'        => MagnooliaUnitDiscoveryService::planLabel($live('plan_type', $canon['plan_type'] ?? null)),
            'rooms'             => $live('rooms', $canon['rooms'] ?? null),
            'net_area'          => $area($live('net_area', $canon['net_area'] ?? null)),
            'terrace_area'      => $area($live('terrace_area', $canon['terrace_area'] ?? null)),
            'balcony_area'      => $area($live('balcony_area', $canon['balcony_area'] ?? null)),
            // Phase 35.1 item 11 — storage_area was the one area field this mapper
            // dropped, so "panipaiga pind" was filled in the admin but invisible on
            // the public site, and "Netopind kokku" (köetav + panipaiga) could not
            // be computed for the price table.
            'storage_area'      => $area($live('storage_area', $canon['storage_area'] ?? null)),
            'private_yard_area' => $area($live('private_yard_area', $canon['private_yard_area'] ?? null)),
            'parking_spaces'    => $live('parking_spaces', $canon['parking_spaces'] ?? null),
            'price'             => $price,
            'price_public'      => $pricePublic,
            'floorplan_1_pdf'   => $canon['floorplan_1_pdf'] ?? null,
            'floorplan_2_pdf'   => $canon['floorplan_2_pdf'] ?? null,
            // Floor plans: per-UNIT (authoritative) → per-building sheet → plan-type.
            'floorplans'        => ($mh['floorplans'] ?? null)
                                    ?: ($this->floorplansForBuilding($building)
                                    ?? $this->floorplansForType($canon['plan_type'] ?? null)),
            'image'             => $mh['image'] ?? null,
            'map_highlight'     => $mapHighlight,
            'map_polygon'       => $mapPolygon,
            'cta_context'       => $cta,
        ];
    }

    /** Canonical hinnatabel units grouped by building number, in file order. */
    private function canonicalByBuilding(): array
    {
        $grouped = [];
        foreach ((array) config('magnoolia_units', []) as $unit) {
            $grouped[$this->canonBuilding($unit)][] = $unit;
        }
        return $grouped;
    }

    /** Live status overlay keyed by "building-section" (e.g. "3-1"). */
    private function liveStatusIndex(): array
    {
        $index = [];
        foreach ($this->discovery->allUnits() as $u) {
            $b = $this->payloadBuilding($u);
            $s = $this->payloadSection($u);
            if ($b === 0) {
                continue;
            }
            $row = [
                'status'       => $u['status'] ?? null,
                'unit_key'     => $u['unit_key'] ?? null,
                'slug'         => $u['slug'] ?? null,
                'price_public' => $u['price_public'] ?? null,
                'price'        => $u['price'] ?? null,
            ];

            // The measurements are editable in admin, so they travel with the
            // publication too. Only keys the payload actually has are copied.
            foreach (['rooms', 'net_area', 'terrace_area', 'balcony_area',
                      'storage_area', 'private_yard_area', 'parking_spaces', 'plan_type'] as $field) {
                if (array_key_exists($field, $u)) {
                    $row[$field] = $u[$field];
                }
            }

            $index[$b . '-' . $s] = $row;
        }
        return $index;
    }

    /** @param array<int,array<string,mixed>> $homes */
    private function availabilityCounts(array $homes): array
    {
        $counts = ['available' => 0, 'reserved' => 0, 'sold' => 0, 'tbc' => 0];
        foreach ($homes as $home) {
            $status = $home['status'] ?? 'tbc';
            if (!array_key_exists($status, $counts)) {
                $status = 'tbc';
            }
            $counts[$status]++;
        }
        return $counts;
    }

    // ---- canonical (config) key parsing: id "tee-3-1", section "3/1" ----
    private function canonBuilding(array $u): int
    {
        if (preg_match('/tee-(\d+)-/', (string) ($u['id'] ?? ''), $m)) {
            return (int) $m[1];
        }
        if (preg_match('/(\d+)/', (string) ($u['building'] ?? ''), $m)) {
            return (int) $m[1];
        }
        return 0;
    }

    private function canonSection(array $u): int
    {
        if (preg_match('/tee-\d+-(\d+)/', (string) ($u['id'] ?? ''), $m)) {
            return (int) $m[1];
        }
        if (preg_match('#/\s*(\d+)#', (string) ($u['section'] ?? ''), $m)) {
            return (int) $m[1];
        }
        return 0;
    }

    // ---- live payload key parsing: unit_key "B3-S1", address "Magnoolia tee 3/1" ----
    private function payloadBuilding(array $u): int
    {
        if (preg_match('/B(\d+)-S/i', (string) ($u['unit_key'] ?? ''), $m)) {
            return (int) $m[1];
        }
        if (preg_match('/(\d+)/', (string) ($u['building'] ?? ''), $m)) {
            return (int) $m[1];
        }
        return 0;
    }

    private function payloadSection(array $u): int
    {
        if (preg_match('/-S(\d+)/i', (string) ($u['unit_key'] ?? ''), $m)) {
            return (int) $m[1];
        }
        if (preg_match('#/\s*(\d+)#', (string) ($u['address'] ?? ''), $m)) {
            return (int) $m[1];
        }
        return 0;
    }

    private function manifest(): array
    {
        return Cache::remember('magnoolia.rowhouse.manifest', 60, function () {
            $rel = (string) config('magnoolia_rowhouses.manifest', 'assets/magnoolia/rowhouse-selection/manifest.json');
            $path = public_path($rel);
            if (!is_file($path)) {
                return [];
            }
            $decoded = json_decode((string) file_get_contents($path), true);
            return is_array($decoded) ? $decoded : [];
        });
    }

    /**
     * Format an area for public ET-style display with a decimal comma and one
     * decimal place: 129.6 → "129,6", 959.7 → "959,7". Null when missing.
     */
    public static function formatArea(float|int|null $area): ?string
    {
        if ($area === null) {
            return null;
        }
        return number_format((float) $area, 1, ',', ' ');
    }
}
