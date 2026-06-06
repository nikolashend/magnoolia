<?php

namespace App\Services\Magnoolia;

/**
 * MagnooliaUnitDiscoveryService — Phase 25
 *
 * Single access point for all public unit queries.
 * Reads from the published snapshot via MagnooliaPublicDataRepository.
 * All returned data is already public-safe (no hidden prices).
 */
class MagnooliaUnitDiscoveryService
{
    public function __construct(
        private readonly MagnooliaPublicDataRepository $repository,
    ) {
    }

    /** Get all visible public units. */
    public function allUnits(): array
    {
        $payload = $this->repository->getPublicPayload();
        return array_values(array_filter(
            $payload['units'] ?? [],
            fn (array $u) => ($u['public_page_visible'] ?? true) !== false,
        ));
    }

    /** Find a unit by slug or unit_key. Returns null if not found. */
    public function findBySlug(string $slug): ?array
    {
        foreach ($this->allUnits() as $unit) {
            if (($unit['slug'] ?? '') === $slug || ($unit['unit_key'] ?? '') === $slug) {
                return $unit;
            }
        }
        return null;
    }

    /** Get units grouped by building name. */
    public function byBuilding(): array
    {
        $grouped = [];
        foreach ($this->allUnits() as $unit) {
            $building = $unit['building'] ?? 'Unknown';
            $grouped[$building][] = $unit;
        }
        return $grouped;
    }

    /** Get units grouped by stage. */
    public function byStage(): array
    {
        $grouped = [1 => [], 2 => []];
        foreach ($this->allUnits() as $unit) {
            $stage = (int) ($unit['stage'] ?? 1);
            $grouped[$stage][] = $unit;
        }
        return $grouped;
    }

    /**
     * Get similar units for a given unit.
     * Priority: same plan_type + same stage + available/reserved first.
     * Excludes the unit itself. Returns max $limit items.
     */
    public function similar(array $unit, int $limit = 4): array
    {
        $candidates = array_filter($this->allUnits(), function (array $u) use ($unit) {
            return ($u['unit_key'] ?? '') !== ($unit['unit_key'] ?? '');
        });

        usort($candidates, function (array $a, array $b) use ($unit) {
            return $this->similarityScore($b, $unit) <=> $this->similarityScore($a, $unit);
        });

        return array_values(array_slice($candidates, 0, $limit));
    }

    private function similarityScore(array $candidate, array $target): int
    {
        $score = 0;
        if (($candidate['stage'] ?? 0) === ($target['stage'] ?? 0)) {
            $score += 3;
        }
        if (($candidate['plan_type'] ?? '') === ($target['plan_type'] ?? '')) {
            $score += 2;
        }
        if (in_array($candidate['status'] ?? '', ['available', 'reserved'], true)) {
            $score += 1;
        }
        return $score;
    }

    /**
     * Get previous/next unit in sorted order by building+section.
     * Returns ['prev' => unit|null, 'next' => unit|null].
     */
    public function adjacent(array $unit): array
    {
        $all = $this->allUnits();
        $currentIndex = null;

        foreach ($all as $i => $u) {
            if (($u['unit_key'] ?? '') === ($unit['unit_key'] ?? '')) {
                $currentIndex = $i;
                break;
            }
        }

        if ($currentIndex === null) {
            return ['prev' => null, 'next' => null];
        }

        return [
            'prev' => $currentIndex > 0 ? $all[$currentIndex - 1] : null,
            'next' => $currentIndex < count($all) - 1 ? $all[$currentIndex + 1] : null,
        ];
    }

    /**
     * Get compare payload for 2-3 units by their slugs.
     * Hidden prices are enforced: if price_public=false, price is null.
     */
    public function comparePayload(array $slugs): array
    {
        $units = [];
        foreach ($slugs as $slug) {
            $unit = $this->findBySlug(trim($slug));
            if ($unit !== null) {
                $units[] = $unit;
            }
            if (count($units) >= 3) {
                break;
            }
        }

        return $units;
    }

    /**
     * Build CTA context for a unit (used in lead forms and events).
     * No hidden prices in the returned array.
     */
    public function ctaContext(array $unit, string $sourceComponent = 'unknown'): array
    {
        $payload = $this->repository->getPublicPayload();

        return [
            'unit_key'         => $unit['unit_key'] ?? null,
            'unit_slug'        => $unit['slug'] ?? null,
            'address'          => $unit['address'] ?? null,
            'stage'            => $unit['stage'] ?? null,
            'status'           => $unit['status'] ?? null,
            'price_public'     => $unit['price_public'] ?? false,
            'source_component' => $sourceComponent,
            'published_version'=> $payload['meta']['version'] ?? null,
        ];
    }

    /**
     * Plan type label: 'A' for type-a, 'B' for type-b, null if unknown.
     */
    public static function planLabel(?string $planType): ?string
    {
        return match ($planType) {
            'type-a' => 'A',
            'type-b' => 'B',
            default  => null,
        };
    }

    /**
     * Get formatted price string. Returns null if price is hidden.
     * Price in payload is already in euros.
     */
    public static function formatPrice(?int $priceEur, bool $pricePublic): ?string
    {
        if (!$pricePublic || $priceEur === null) {
            return null;
        }
        return number_format($priceEur, 0, '.', ' ') . ' €';
    }

    /**
     * Build unit page URL for a given locale.
     */
    public static function unitPageUrl(array $unit, string $locale = 'et'): string
    {
        $slug = $unit['slug'] ?? $unit['unit_key'];
        return match ($locale) {
            'ru'    => url("/ru/kodud/{$slug}"),
            'en'    => url("/en/homes/{$slug}"),
            default => url("/kodud/{$slug}"),
        };
    }

    /**
     * Get localized unit page route name.
     */
    public static function unitPageRouteName(string $locale = 'et'): string
    {
        return match ($locale) {
            'ru'    => 'ru.magnoolia.unit',
            'en'    => 'en.magnoolia.unit',
            default => 'magnoolia.unit',
        };
    }
}
