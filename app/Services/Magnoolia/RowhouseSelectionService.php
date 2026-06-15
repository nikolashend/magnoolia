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
                'perspective'         => $mRow['perspective'] ?? null, // {marker, hull} on the render
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

        $cta = $this->discovery->ctaContext([
            'unit_key'     => $unitKey,
            'slug'         => $slug,
            'address'      => $canon['address'] ?? null,
            'stage'        => $stage,
            'status'       => $status,
            'price_public' => $pricePublic,
        ], 'home_detail_modal');

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
            'plan_type'         => $canon['plan_type'] ?? null,
            'plan_label'        => MagnooliaUnitDiscoveryService::planLabel($canon['plan_type'] ?? null),
            'rooms'             => $canon['rooms'] ?? null,
            'net_area'          => isset($canon['net_area']) ? (float) $canon['net_area'] : null,
            'terrace_area'      => isset($canon['terrace_area']) ? (float) $canon['terrace_area'] : null,
            'balcony_area'      => isset($canon['balcony_area']) ? (float) $canon['balcony_area'] : null,
            'private_yard_area' => isset($canon['private_yard_area']) ? (float) $canon['private_yard_area'] : null,
            'parking_spaces'    => $canon['parking_spaces'] ?? null,
            'price'             => $price,
            'price_public'      => $pricePublic,
            'floorplan_1_pdf'   => $canon['floorplan_1_pdf'] ?? null,
            'floorplan_2_pdf'   => $canon['floorplan_2_pdf'] ?? null,
            // Prefer the authoritative per-building floor-plan sheet; fall back to the plan-type plan.
            'floorplans'        => $this->floorplansForBuilding($building) ?? $this->floorplansForType($canon['plan_type'] ?? null),
            'image'             => $mh['image'] ?? null,
            'map_highlight'     => $mh['map_highlight'] ?? null,
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
            $index[$b . '-' . $s] = [
                'status'       => $u['status'] ?? null,
                'unit_key'     => $u['unit_key'] ?? null,
                'slug'         => $u['slug'] ?? null,
                'price_public' => $u['price_public'] ?? null,
                'price'        => $u['price'] ?? null,
            ];
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
