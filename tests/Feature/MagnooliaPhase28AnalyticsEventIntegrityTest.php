<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Phase 28 — Analytics event integrity test
 * Verifies all key CTAs have proper analytics attributes.
 */
class MagnooliaPhase28AnalyticsEventIntegrityTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_has_cta_analytics_attributes(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $html = $response->getContent();

        $this->assertStringContainsString('data-mg-analytics', $html,
            'Homepage must have data-mg-analytics attributes');
        $this->assertStringContainsString('magnoolia_cta_click', $html,
            'Homepage must have magnoolia_cta_click event');
    }

    public function test_homepage_has_availability_board_analytics(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $html = $response->getContent();

        $this->assertStringContainsString('magnoolia_home_availability_click', $html,
            'Homepage availability board must have magnoolia_home_availability_click event');
    }

    public function test_contact_page_has_phone_analytics(): void
    {
        $response = $this->get('/kontakt');
        $response->assertStatus(200);
        $html = $response->getContent();

        $this->assertStringContainsString('magnoolia_phone_click', $html,
            'Contact page must have magnoolia_phone_click event on phone links');
    }

    public function test_contact_page_has_email_analytics(): void
    {
        $response = $this->get('/kontakt');
        $response->assertStatus(200);
        $html = $response->getContent();

        $this->assertStringContainsString('magnoolia_email_click', $html,
            'Contact page must have magnoolia_email_click event on email links');
    }

    public function test_header_cta_has_analytics(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $html = $response->getContent();

        $this->assertStringContainsString('data-source-component="header_cta"', $html,
            'Header CTA must have data-source-component="header_cta"');
    }

    public function test_inquiry_drawer_open_attributes_present(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $html = $response->getContent();

        $this->assertStringContainsString('data-mg-inquiry-open', $html,
            'Homepage must have at least one data-mg-inquiry-open CTA');
    }
}
