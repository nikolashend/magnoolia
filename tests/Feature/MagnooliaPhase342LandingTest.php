<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Phase 34.2 — SEO / Google Ads commercial landing pages.
 * Verifies every new landing page renders, is SEO-complete, links to the BOFU
 * pages, carries valid FAQ structured data, and that existing pages still work.
 */
class MagnooliaPhase342LandingTest extends TestCase
{
    use RefreshDatabase;

    /** All 14 landing pages: [path, lang]. */
    public static function landings(): array
    {
        return [
            ['/ridaelamud-harjumaa', 'et'],
            ['/ridamajad-harjumaa', 'et'],
            ['/uusarendus-kiili', 'et'],
            ['/uusarendus-harjumaa', 'et'],
            ['/uus-kodu-tallinna-lahedal', 'et'],
            ['/maja-muuk-harjumaa', 'et'],
            ['/a-energiaklassi-ridaelamud', 'et'],
            ['/perekodu-tallinna-lahedal', 'et'],
            ['/ridaelamu-oma-hooviga', 'et'],
            ['/ridaelamu-vaela-kula', 'et'],
            ['/en/new-townhouses-near-tallinn', 'en'],
            ['/en/terraced-houses-harju-county', 'en'],
            ['/ru/taunhaus-rjadom-s-tallinnom', 'ru'],
            ['/ru/novyj-dom-v-harjumaa', 'ru'],
            // Phase 34.3 — location hubs (ET)
            ['/asukoht/vaela-kula', 'et'],
            ['/asukoht/kiili-vald', 'et'],
            ['/asukoht/tallinna-lahedal', 'et'],
        ];
    }

    /**
     * @dataProvider landings
     */
    public function test_landing_page_is_seo_complete(string $path, string $lang): void
    {
        $html = $this->get($path)->assertOk()->getContent();

        // Exactly one H1.
        $this->assertSame(1, substr_count($html, '<h1'), $path . ' must have exactly one <h1>');

        // Title + description + self canonical.
        $this->assertMatchesRegularExpression('/<title>[^<]{15,}<\/title>/', $html, $path . ' missing a real <title>');
        $this->assertStringContainsString('name="description"', $html, $path . ' missing meta description');
        $this->assertStringContainsString('rel="canonical" href="https://magnoolia.ee' . $path . '"', $html, $path . ' missing self canonical');

        // hreflang: only the page locale + x-default (no fake alternates).
        $this->assertStringContainsString('hreflang="x-default"', $html, $path . ' missing x-default');
        $this->assertStringContainsString('hreflang="' . $lang . '"', $html, $path . ' missing own-locale hreflang');
        if ($lang === 'et') {
            $this->assertStringNotContainsString('hreflang="ru"', $html, $path . ' must not claim a ru alternate');
            $this->assertStringNotContainsString('hreflang="en"', $html, $path . ' must not claim an en alternate');
        }

        // BOFU internal links present.
        $this->assertStringContainsString('kodud-ja-hinnad', $html, $path . ' must link to Kodud ja hinnad');
        $this->assertStringContainsString('kontakt', $html, $path . ' must link to Kontakt');

        // Visible FAQ + FAQPage structured data.
        $this->assertStringContainsString('mg-faq-card__q', $html, $path . ' missing visible FAQ');
        $this->assertStringContainsString('FAQPage', $html, $path . ' missing FAQPage schema');

        // No leftover placeholder content.
        foreach (['lorem ipsum', 'PLACEHOLDER', 'TODO:', 'Lorem'] as $needle) {
            $this->assertStringNotContainsString($needle, $html, $path . ' contains placeholder text: ' . $needle);
        }

        // Never leak internal price cents.
        $this->assertStringNotContainsString('price_cents', $html, $path . ' leaks price_cents');
    }

    public function test_landings_are_in_sitemap(): void
    {
        $xml = $this->get('/sitemap.xml')->assertOk()->getContent();
        foreach (self::landings() as [$path, $lang]) {
            $this->assertStringContainsString('<loc>https://magnoolia.ee' . $path . '</loc>', $xml, $path . ' missing from sitemap');
        }
    }

    /** No-regression: the approved core pages still return 200. */
    public static function corePages(): array
    {
        return [
            ['/'], ['/kodud-ja-hinnad'], ['/asukoht'], ['/galerii'], ['/kontakt'],
            ['/ehitusinfo'], ['/sisedisain'], ['/arhitektuur-ja-valisdisain'],
            ['/arendajast'], ['/finantseerimine'], ['/ostuprotsess'], ['/kkk'],
            ['/ru'], ['/en'],
        ];
    }

    /**
     * @dataProvider corePages
     */
    public function test_existing_core_page_still_ok(string $path): void
    {
        $this->get($path)->assertOk();
    }
}
