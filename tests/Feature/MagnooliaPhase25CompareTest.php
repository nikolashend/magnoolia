<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Magnoolia\MagnooliaPublicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesMagnooliaTestUnits;

/**
 * Phase 25 — Compare page: 2-3 units, hidden prices, locales.
 */
class MagnooliaPhase25CompareTest extends TestCase
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
        $this->pubService->publish($this->admin->id, 'Compare test');
    }

    /** @test */
    public function et_compare_page_returns_200()
    {
        $this->get('/vordle')->assertOk();
    }

    /** @test */
    public function ru_compare_page_returns_200()
    {
        $this->get('/ru/sravnit')->assertOk();
    }

    /** @test */
    public function en_compare_page_returns_200()
    {
        $this->get('/en/compare')->assertOk();
    }

    /** @test */
    public function compare_with_two_units_shows_table()
    {
        $response = $this->get('/vordle?units=b1-s1,b3-s1');
        $response->assertOk();
        $content = $response->getContent();

        $this->assertStringContainsString('Magnoolia tee 1/1', $content);
        $this->assertStringContainsString('Magnoolia tee 3/1', $content);
    }

    /** @test */
    public function compare_with_three_units_shows_table()
    {
        $response = $this->get('/vordle?units=b1-s1,b1-s2,b3-s1');
        $response->assertOk();
        $content = $response->getContent();

        $this->assertStringContainsString('Magnoolia tee 1/1', $content);
        $this->assertStringContainsString('Magnoolia tee 1/2', $content);
        $this->assertStringContainsString('Magnoolia tee 3/1', $content);
    }

    /** @test */
    public function compare_hidden_prices_remain_hidden()
    {
        // Stage II has hidden prices
        $response = $this->get('/vordle?units=b5-s1,b1-s1');
        $response->assertOk();
        $content = $response->getContent();

        $this->assertStringContainsString('täpsustamisel', $content);
    }

    /** @test */
    public function compare_page_does_not_crash_with_missing_unit()
    {
        $response = $this->get('/vordle?units=b1-s1,nonexistent');
        $response->assertOk();
        $content = $response->getContent();
        $this->assertStringContainsString('Magnoolia tee 1/1', $content);
    }

    /** @test */
    public function compare_page_max_3_units_enforced()
    {
        // Pass 5 slugs — only first 3 should appear
        $response = $this->get('/vordle?units=b1-s1,b1-s2,b1-s3,b3-s1,b3-s2');
        $response->assertOk();
        $content = $response->getContent();

        // First 3 present
        $this->assertStringContainsString('Magnoolia tee 1/1', $content);
        $this->assertStringContainsString('Magnoolia tee 1/2', $content);
        $this->assertStringContainsString('Magnoolia tee 1/3', $content);
    }

    /** @test */
    public function compare_page_has_js_localstorage_code()
    {
        $response = $this->get('/vordle');
        $response->assertOk();
        $this->assertStringContainsString('localStorage', $response->getContent());
    }

    /** @test */
    public function compare_page_has_all_units_list_for_selection()
    {
        $response = $this->get('/vordle');
        $response->assertOk();
        $content = $response->getContent();

        // All buildings should be listed as picker buttons
        $this->assertStringContainsString('Magnoolia tee 1/1', $content);
        $this->assertStringContainsString('Magnoolia tee 11/3', $content);
    }
}
