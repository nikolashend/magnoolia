<?php

namespace Tests\Feature;

use App\Services\Magnoolia\RowhouseSelectionService;
use Tests\TestCase;

/**
 * Phase 32 — Public website final hardening gate.
 *
 * Locks in the Phase-32 fixes so they cannot silently regress:
 *  - every major public route renders (ET/RU/EN) with no unresolved i18n keys,
 *    no price_cents leak and no raw source-asset paths;
 *  - the canonical fallback always exposes 19 homes, never 0;
 *  - statuses are consistent and match the approved list (14 available,
 *    4 reserved, 1 sold) on every surface that reads RowhouseSelectionService.
 */
class MagnooliaPhase32PublicHardeningTest extends TestCase
{
    /** ET (no prefix), RU and EN public routes that must always be healthy. */
    private const PUBLIC_PATHS = [
        '/', '/kodud-ja-hinnad', '/asendiplaan', '/asukoht', '/ehitusinfo',
        '/sisedisain', '/arhitektuur-ja-valisdisain', '/galerii',
        '/ostuprotsess', '/finantseerimine', '/kkk', '/kontakt',
    ];

    public static function localePathProvider(): array
    {
        $cases = [];
        foreach (['', 'ru', 'en'] as $loc) {
            foreach (self::PUBLIC_PATHS as $path) {
                $url = $loc === '' ? $path : '/' . $loc . ($path === '/' ? '' : $path);
                $cases[($loc ?: 'et') . ' ' . $path] = [$url];
            }
        }
        return $cases;
    }

    /**
     * @dataProvider localePathProvider
     */
    public function test_public_route_is_healthy(string $url): void
    {
        $html = $this->get($url)->assertStatus(200)->getContent();

        $this->assertStringContainsString('<footer', $html, "$url must render the footer");

        // No unresolved translation keys leaking to the page (e.g. magnoolia.nav.about).
        $this->assertDoesNotMatchRegularExpression(
            '/\bmagnoolia\.[a-z0-9_]+\.[a-z0-9_]+\b/',
            $html,
            "$url must not render unresolved magnoolia.* translation keys"
        );

        // Never expose the hidden integer price field.
        $this->assertStringNotContainsString('price_cents', $html, "$url must not expose price_cents");

        // Never expose internal source/working asset paths.
        foreach (['materials/', '/source/', '.pptx', 'OneDrive', '2a.png', '4a.png'] as $needle) {
            $this->assertStringNotContainsString($needle, $html, "$url must not expose internal path '$needle'");
        }
    }

    public function test_canonical_fallback_always_has_19_homes(): void
    {
        $homes = app(RowhouseSelectionService::class)->allHomes();
        $this->assertCount(19, $homes, 'Canonical fallback must always expose exactly 19 homes (never 0)');
    }

    public function test_status_distribution_matches_approved_list(): void
    {
        $homes = app(RowhouseSelectionService::class)->allHomes();
        $counts = ['available' => 0, 'reserved' => 0, 'sold' => 0, 'tbc' => 0];
        foreach ($homes as $h) {
            $counts[$h['status']] = ($counts[$h['status']] ?? 0) + 1;
        }

        $this->assertSame(4, $counts['reserved'], 'Exactly 4 homes must be reserved');
        $this->assertSame(1, $counts['sold'], 'Exactly 1 home must be sold');
        $this->assertSame(14, $counts['available'], 'Exactly 14 homes must be available');
    }

    public function test_specific_reserved_and_sold_homes(): void
    {
        $rhs = app(RowhouseSelectionService::class);
        $byKey = [];
        foreach ($rhs->allHomes() as $h) {
            $byKey[$h['asset_key']] = $h['status'];
        }

        foreach (['tee-1-3', 'tee-3-2', 'tee-7-2', 'tee-9-2'] as $reserved) {
            $this->assertSame('reserved', $byKey[$reserved] ?? null, "$reserved must be reserved");
        }
        $this->assertSame('sold', $byKey['tee-5-1'] ?? null, 'tee-5-1 must be sold');
    }

    public function test_no_home_exposes_a_public_price(): void
    {
        // Prices are unconfirmed for public display in this phase — all withheld.
        foreach (app(RowhouseSelectionService::class)->allHomes() as $h) {
            $this->assertFalse((bool) $h['price_public'], "{$h['asset_key']} must not expose a public price yet");
        }
    }

    public function test_nav_about_resolves_in_all_locales(): void
    {
        foreach (['/', '/ru', '/en'] as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            $this->assertStringNotContainsString('magnoolia.nav.about', $html, "$url nav.about must resolve");
        }
    }
}
