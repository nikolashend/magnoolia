<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Phase 28 — Homepage availability board tests
 * Verifies that the compact availability board renders correctly on the homepage.
 */
class MagnooliaPhase28HomepageAvailabilityBoardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createPublishedSnapshot();
    }

    private function createPublishedSnapshot(): void
    {
        if (method_exists($this, 'createMagnooliaPublicSnapshot')) {
            $this->createMagnooliaPublicSnapshot();
        } elseif (method_exists($this, 'create19TestUnits')) {
            $this->create19TestUnits();
        }
    }

    public function test_homepage_contains_19_homes_in_availability_board(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $html = $response->getContent();

        $this->assertStringContainsString('saadavus', strtolower($html),
            'Homepage should contain availability board section');
    }

    public function test_availability_board_has_stage_sections(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $html = $response->getContent();

        $this->assertStringContainsString('I etapp', $html, 'Availability board must show Stage I');
        $this->assertStringContainsString('II etapp', $html, 'Availability board must show Stage II');
    }

    public function test_availability_board_ctas_have_inquiry_open(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $html = $response->getContent();

        // The board has CTAs with data-mg-inquiry-open for available/reserved units
        $this->assertStringContainsString('data-mg-inquiry-open', $html,
            'Availability board must have inquiry CTAs with data-mg-inquiry-open');
    }

    public function test_availability_board_has_analytics_attribute(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $html = $response->getContent();

        $this->assertStringContainsString('magnoolia_home_availability_click', $html,
            'Availability board must have magnoolia_home_availability_click analytics attribute');
    }

    public function test_availability_board_cta_to_full_table(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $html = $response->getContent();

        $this->assertStringContainsString('kodud-ja-hinnad', $html,
            'Homepage must link to full homes & prices table');
    }

    public function test_no_price_cents_on_homepage(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $html = $response->getContent();

        $this->assertStringNotContainsString('price_cents', $html,
            'price_cents must never appear in rendered HTML');
    }

    public function test_homepage_ru_shows_russian_stage_labels(): void
    {
        $response = $this->get('/ru');
        $response->assertStatus(200);
        $html = $response->getContent();

        $this->assertStringContainsString('этап', $html,
            'RU homepage must show Russian stage label "этап" in availability board');
    }

    public function test_homepage_en_shows_english_stage_labels(): void
    {
        $response = $this->get('/en');
        $response->assertStatus(200);
        $html = $response->getContent();

        $this->assertStringContainsString('Phase', $html,
            'EN homepage must show English "Phase" label in availability board');
    }
}
