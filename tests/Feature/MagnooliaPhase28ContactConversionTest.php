<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Phase 28 — Contact page conversion test
 * Verifies the contact page has proper elements for conversion.
 */
class MagnooliaPhase28ContactConversionTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_page_has_phone_link(): void
    {
        $response = $this->get('/kontakt');
        $response->assertStatus(200);
        $html = $response->getContent();

        $this->assertStringContainsString('tel:+37258164078', $html,
            'Contact page must have Diana phone link');
        $this->assertStringContainsString('58 16 40 78', $html,
            'Contact page must show Diana phone number visibly');
    }

    public function test_contact_page_has_email(): void
    {
        $response = $this->get('/kontakt');
        $response->assertStatus(200);
        $html = $response->getContent();

        $this->assertStringContainsString('diana@estlanda.ee', $html,
            'Contact page must show Diana email address');
    }

    public function test_contact_page_has_form(): void
    {
        $response = $this->get('/kontakt');
        $response->assertStatus(200);
        $html = $response->getContent();

        $this->assertStringContainsString('<form', $html,
            'Contact page must have an inquiry form');
    }

    public function test_contact_form_submit_label_is_localized_et(): void
    {
        $response = $this->get('/kontakt');
        $response->assertStatus(200);
        $html = $response->getContent();

        // Should have "Saada päring" in ET
        $this->assertStringContainsString('Saada p', $html,
            'ET contact page form submit button must say "Saada päring"');
    }

    public function test_contact_form_submit_label_is_localized_ru(): void
    {
        $response = $this->get('/ru/kontakt');
        $response->assertStatus(200);
        $html = $response->getContent();

        // Should have Russian submit text
        $this->assertStringContainsString('запрос', strtolower($html),
            'RU contact page form submit button must have Russian text');
    }

    public function test_contact_page_has_no_diana_placeholder(): void
    {
        $response = $this->get('/kontakt');
        $response->assertStatus(200);
        $html = $response->getContent();

        // No generic "JP" or "Jaanika" as placeholder person name
        $textContent = strip_tags($html);
        $this->assertStringNotContainsString(' JP ', $textContent,
            'Contact page must not show "JP" as placeholder initials');
    }

    public function test_contact_page_has_diana_name(): void
    {
        $response = $this->get('/kontakt');
        $response->assertStatus(200);
        $html = $response->getContent();

        $this->assertStringContainsString('Diana', $html,
            'Contact page must show Diana Tali name');
    }
}
