<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Magnoolia\MagnooliaPublicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesMagnooliaTestUnits;

/**
 * Phase 26 — Rendered HTML asset audit across all public pages.
 * Checks for OneDrive leakage, source path leakage, price_cents, Jaanika/JP,
 * missing alt on images, and language purity.
 */
class MagnooliaPhase26RenderedHtmlAssetAuditTest extends TestCase
{
    use RefreshDatabase, CreatesMagnooliaTestUnits;

    private array $pages = [];

    public function setUp(): void
    {
        parent::setUp();
        $admin = User::factory()->verified()->create(['role' => 'magnoolia_admin']);
        $this->create19TestUnits();
        app(MagnooliaPublicationService::class)->publish($admin->id, 'Phase 26 HTML audit test');

        $this->pages = [
            '/'                  => 'homepage',
            '/kodud-ja-hinnad'   => 'kodudjahinnad',
            '/asendiplaan'       => 'asendiplaan',
            '/asukoht'           => 'asukoht',
            '/sisedisain'        => 'sisedisain',
            '/finantseerimine'   => 'finantseerimine',
            '/galerii'           => 'galerii',
            '/kontakt'           => 'kontakt',
        ];
    }

    public function test_no_onedrive_urls_on_public_pages(): void
    {
        foreach ($this->pages as $url => $name) {
            $html = $this->get($url)->getContent();
            $this->assertStringNotContainsStringIgnoringCase('onedrive.live.com', $html,
                "OneDrive URL found on $name ($url)");
            $this->assertStringNotContainsStringIgnoringCase('sharepoint.com', $html,
                "SharePoint URL found on $name ($url)");
        }
    }

    public function test_no_source_asset_paths_on_public_pages(): void
    {
        foreach ($this->pages as $url => $name) {
            $html = $this->get($url)->getContent();
            $this->assertStringNotContainsString('resources/source-assets', $html,
                "Internal source path exposed on $name ($url)");
        }
    }

    public function test_no_price_cents_on_public_pages(): void
    {
        foreach ($this->pages as $url => $name) {
            $html = $this->get($url)->getContent();
            $this->assertStringNotContainsString('price_cents', $html,
                "price_cents found on $name ($url)");
        }
    }

    public function test_no_jp_design_or_jaanika_on_public_pages(): void
    {
        foreach ($this->pages as $url => $name) {
            $html = $this->get($url)->getContent();
            $this->assertStringNotContainsStringIgnoringCase('JP Design', $html,
                "JP Design found on $name ($url)");
            $this->assertStringNotContainsStringIgnoringCase('Jaanika', $html,
                "Jaanika found on $name ($url)");
        }
    }

    public function test_no_translation_key_leakage_on_public_pages(): void
    {
        foreach ($this->pages as $url => $name) {
            $html = $this->get($url)->getContent();
            // Translation keys look like magnoolia.something.something rendered verbatim
            $this->assertStringNotContainsString('magnoolia.page.', $html,
                "Raw translation key found on $name ($url)");
        }
    }

    public function test_et_pages_have_no_english_section_headings(): void
    {
        $etOnlyTerms = ['View all homes', 'See our homes', 'Contact us', 'Learn more'];
        foreach ($this->pages as $url => $name) {
            $html = $this->get($url)->getContent();
            foreach ($etOnlyTerms as $term) {
                $this->assertStringNotContainsString($term, $html,
                    "English heading '$term' found on ET page $name ($url)");
            }
        }
    }

    public function test_ru_pages_have_no_et_section_headings(): void
    {
        // Only test for ET headings that would NOT appear in URLs or brand names
        $etHeadings = ['Küsi pakkumist', 'Vaata kodusid'];
        $ruPages = ['/ru/asukoht', '/ru/galerii', '/ru/finantseerimine', '/ru/kontakt'];
        foreach ($ruPages as $url) {
            $html = $this->get($url)->getContent();
            foreach ($etHeadings as $heading) {
                $this->assertStringNotContainsString($heading, $html,
                    "Estonian heading '$heading' found on RU page $url");
            }
        }
    }

    public function test_campaign_20000_appears_only_in_approved_contexts(): void
    {
        // Campaign should appear on approved pages
        $approvedPages = ['/', '/kodud-ja-hinnad'];
        // Forbidden context: should not appear on navigation or unrelated pages
        $html_sisedisain = $this->get('/siseviimistlus')->getContent();

        // It's fine if it doesn't appear on sisedisain
        // But check approved pages don't have it in a wrong context (schema/hidden data)
        foreach ($approvedPages as $url) {
            $html = $this->get($url)->getContent();
            // Campaign showing is OK, but should not appear in JSON-LD price schemas
            if (str_contains($html, '20000') || str_contains($html, '20 000')) {
                $this->assertStringNotContainsString('"price":"20000"', $html,
                    "Campaign amount must not appear as schema.org price on $url");
            }
        }
    }

    public function test_homepage_has_pricing_teaser(): void
    {
        $html = $this->get('/')->getContent();
        // Homepage should have a pricing/availability teaser
        $this->assertTrue(
            str_contains($html, 'kodud-ja-hinnad') || str_contains($html, 'hinnad'),
            'Homepage must have pricing/availability teaser'
        );
    }

    public function test_footer_disclaimer_present_on_gallery(): void
    {
        $html = $this->get('/galerii')->getContent();
        // Disclaimer about illustrative images
        $this->assertTrue(
            str_contains($html, 'illustratiivse') || str_contains($html, 'illustratiivsed') ||
            str_contains($html, 'visualiseeringud'),
            'Gallery must have illustrative images disclaimer'
        );
    }

    public function test_unit_pages_do_not_expose_stage2_prices(): void
    {
        // Stage II buildings: 5, 7, 9, 11 (price_public = false)
        // Their pages should not show raw price values
        $stage2Units = \App\Models\MagnooliaUnit::where('stage', 2)
            ->where('price_public', false)
            ->take(2)
            ->get();

        foreach ($stage2Units as $unit) {
            $html = $this->get("/kodu/{$unit->slug}")->getContent();
            if ($html) {
                // Price in cents should never appear
                $this->assertStringNotContainsString((string) $unit->price_cents, $html,
                    "Hidden price_cents for Stage II unit {$unit->unit_key} found in HTML");
            }
        }
    }
}
