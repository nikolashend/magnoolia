<?php

namespace Tests\Feature;

use App\Models\MagnooliaUnit;
use App\Models\User;
use App\Services\Magnoolia\MagnooliaPublicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesMagnooliaTestUnits;

/**
 * Phase 25 — Hidden price must NEVER appear in HTML, meta, schema, or events.
 */
class MagnooliaPhase25HiddenPriceLeakTest extends TestCase
{
    use RefreshDatabase, CreatesMagnooliaTestUnits;

    private User $admin;
    private MagnooliaPublicationService $pubService;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->verified()->create(['role' => 'magnoolia_admin']);
        $this->pubService = app(MagnooliaPublicationService::class);
        $this->create19TestUnits();

        // Stage II units have hidden prices (set in create19TestUnits via price_public = stage===1)
        // Ensure a specific unit has a hidden price
        MagnooliaUnit::where('unit_key', 'B5-S1')->update([
            'price_cents' => 48000000,   // 480 000 €
            'price_public' => false,
        ]);

        $this->pubService->publish($this->admin->id, 'Hidden price leak test');
    }

    /** @test */
    public function hidden_price_unit_page_does_not_show_price_in_html()
    {
        $response = $this->get('/kodud/b5-s1');
        $response->assertOk();
        $content = $response->getContent();

        // Must not contain the price value
        $this->assertStringNotContainsString('480 000', $content);
        $this->assertStringNotContainsString('48000000', $content);
        $this->assertStringNotContainsString('480000', $content);
    }

    /** @test */
    public function hidden_price_unit_page_shows_hind_tapsustamisel()
    {
        $response = $this->get('/kodud/b5-s1');
        $response->assertOk();
        $response->assertSee('täpsustamisel', false);
    }

    /** @test */
    public function hidden_price_unit_page_has_no_price_in_meta_description()
    {
        $response = $this->get('/kodud/b5-s1');
        $response->assertOk();
        $content = $response->getContent();

        // Meta description must not mention the price
        preg_match_all('/<meta[^>]+name=["\']description["\'][^>]*>/i', $content, $matches);
        foreach ($matches[0] as $metaTag) {
            $this->assertStringNotContainsString('480', $metaTag);
        }
    }

    /** @test */
    public function hidden_price_unit_page_has_no_offer_schema()
    {
        $response = $this->get('/kodud/b5-s1');
        $response->assertOk();
        $content = $response->getContent();

        // No Offer with price
        $this->assertStringNotContainsString('"Offer"', $content);
    }

    /** @test */
    public function hidden_price_not_in_analytics_js_payload()
    {
        $response = $this->get('/kodud/b5-s1');
        $response->assertOk();
        $content = $response->getContent();

        // Analytics must not push the hidden price value
        $this->assertStringNotContainsString('480000', $content);
        $this->assertStringNotContainsString('48000000', $content);
    }

    /** @test */
    public function visible_price_unit_page_shows_price()
    {
        // Stage I units have visible prices
        $response = $this->get('/kodud/b1-s1');
        $response->assertOk();
        $content = $response->getContent();
        // Price should be visible (price in test = 150000 * 1 + 10000 * 1 = 160000 cents = 1600 €)
        $this->assertStringContainsString('1 600', $content);
    }

    /** @test */
    public function compare_page_does_not_show_hidden_price()
    {
        $response = $this->get('/vordle?units=b5-s1,b1-s1');
        $response->assertOk();
        $content = $response->getContent();

        // Hidden price unit must show 'täpsustamisel'
        $this->assertStringContainsString('täpsustamisel', $content);

        // Must not show the actual hidden price value
        $this->assertStringNotContainsString('480 000', $content);
        $this->assertStringNotContainsString('48000000', $content);
    }

    /** @test */
    public function hidden_price_not_in_asendiplaan_js_unit_data()
    {
        $response = $this->get('/asendiplaan');
        $response->assertOk();
        $content = $response->getContent();

        // JS UNITS array must not contain the hidden price value
        $this->assertStringNotContainsString('48000000', $content);
        $this->assertStringNotContainsString('480000', $content);
    }
}
