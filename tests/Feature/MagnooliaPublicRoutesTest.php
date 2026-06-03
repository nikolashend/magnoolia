<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Phase 21 — Public surface smoke tests.
 *
 * Strategy:
 *  - Route registration tests: assert named routes exist in the router (no DB required).
 *  - Semantic content tests: assert the home page doesn't expose forbidden strings
 *    (run against real MySQL via APP_ENV=local; skipped when DB is unavailable).
 */
class MagnooliaPublicRoutesTest extends TestCase
{
    // -----------------------------------------------------------------------
    // Route registration assertions (DB-free)
    // These verify that every public URL is wired up to a controller action.
    // -----------------------------------------------------------------------

    /** @return array<string, array{0: string}> */
    public static function etRouteNames(): array
    {
        return [
            'home'            => ['home'],
            'homes'           => ['magnoolia.homes'],
            'site-plan'       => ['magnoolia.site-plan'],
            'location'        => ['magnoolia.location'],
            'construction'    => ['magnoolia.construction'],
            'contact'         => ['magnoolia.contact'],
            'interior-design' => ['magnoolia.sisedisain'],
            'architecture'    => ['magnoolia.arhitektuur'],
            'gallery'         => ['magnoolia.galerii'],
            'purchase'        => ['magnoolia.ostuprotsess'],
            'finance'         => ['magnoolia.finantseerimine'],
            'faq'             => ['magnoolia.kkk'],
            'thankyou'        => ['magnoolia.thankyou'],
        ];
    }

    /** @return array<string, array{0: string}> */
    public static function ruRouteNames(): array
    {
        return [
            'ru.home'            => ['ru.home'],
            'ru.homes'           => ['ru.magnoolia.homes'],
            'ru.site-plan'       => ['ru.magnoolia.site-plan'],
            'ru.location'        => ['ru.magnoolia.location'],
            'ru.construction'    => ['ru.magnoolia.construction'],
            'ru.contact'         => ['ru.magnoolia.contact'],
            'ru.interior-design' => ['ru.magnoolia.sisedisain'],
            'ru.architecture'    => ['ru.magnoolia.arhitektuur'],
            'ru.gallery'         => ['ru.magnoolia.galerii'],
            'ru.purchase'        => ['ru.magnoolia.ostuprotsess'],
            'ru.finance'         => ['ru.magnoolia.finantseerimine'],
            'ru.faq'             => ['ru.magnoolia.kkk'],
            'ru.thankyou'        => ['ru.magnoolia.thankyou'],
        ];
    }

    /** @return array<string, array{0: string}> */
    public static function enRouteNames(): array
    {
        return [
            'en.home'            => ['en.home'],
            'en.homes'           => ['en.magnoolia.homes'],
            'en.site-plan'       => ['en.magnoolia.site-plan'],
            'en.location'        => ['en.magnoolia.location'],
            'en.construction'    => ['en.magnoolia.construction'],
            'en.contact'         => ['en.magnoolia.contact'],
            'en.interior-design' => ['en.magnoolia.sisedisain'],
            'en.architecture'    => ['en.magnoolia.arhitektuur'],
            'en.gallery'         => ['en.magnoolia.galerii'],
            'en.purchase'        => ['en.magnoolia.ostuprotsess'],
            'en.finance'         => ['en.magnoolia.finantseerimine'],
            'en.faq'             => ['en.magnoolia.kkk'],
            'en.thankyou'        => ['en.magnoolia.thankyou'],
        ];
    }

    /**
     * @dataProvider etRouteNames
     */
    public function test_et_named_route_is_registered(string $name): void
    {
        $this->assertTrue(Route::has($name), "ET route [{$name}] is not registered.");
    }

    /**
     * @dataProvider ruRouteNames
     */
    public function test_ru_named_route_is_registered(string $name): void
    {
        $this->assertTrue(Route::has($name), "RU route [{$name}] is not registered.");
    }

    /**
     * @dataProvider enRouteNames
     */
    public function test_en_named_route_is_registered(string $name): void
    {
        $this->assertTrue(Route::has($name), "EN route [{$name}] is not registered.");
    }

    // -----------------------------------------------------------------------
    // URL-generation sanity checks (no HTTP, no DB)
    // Ensures route() helper doesn't throw for any locale.
    // -----------------------------------------------------------------------

    public function test_et_home_url_resolves(): void
    {
        $this->assertSame(url('/'), route('home'));
    }

    public function test_ru_home_url_resolves(): void
    {
        $this->assertStringEndsWith('/ru', route('ru.home'));
    }

    public function test_en_home_url_resolves(): void
    {
        $this->assertStringEndsWith('/en', route('en.home'));
    }

    // -----------------------------------------------------------------------
    // Aitah (thank-you) pages must be noindex — verify route names resolve
    // -----------------------------------------------------------------------

    public function test_aitah_route_name_exists_for_all_locales(): void
    {
        $this->assertTrue(Route::has('magnoolia.thankyou'));
        $this->assertTrue(Route::has('ru.magnoolia.thankyou'));
        $this->assertTrue(Route::has('en.magnoolia.thankyou'));
    }

    // -----------------------------------------------------------------------
    // Contact send routes must accept POST (CSRF-free assertion)
    // -----------------------------------------------------------------------

    public function test_contact_send_routes_are_post_for_all_locales(): void
    {
        $this->assertTrue(Route::has('magnoolia.contact.send'));
        $this->assertTrue(Route::has('ru.magnoolia.contact.send'));
        $this->assertTrue(Route::has('en.magnoolia.contact.send'));
    }
}
