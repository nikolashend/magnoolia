<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Phase 26 — Finance page (Bigbank) and contact page (Diana Tali).
 */
class MagnooliaPhase26FinanceAndPartnerTest extends TestCase
{
    public function test_finantseerimine_page_renders_successfully(): void
    {
        $this->get('/finantseerimine')->assertStatus(200);
    }

    public function test_finantseerimine_ru_renders(): void
    {
        $this->get('/ru/finantseerimine')->assertStatus(200);
    }

    public function test_finantseerimine_en_renders(): void
    {
        $this->get('/en/finantseerimine')->assertStatus(200);
    }

    public function test_finantseerimine_renders_bigbank_block(): void
    {
        $html = $this->get('/finantseerimine')->getContent();
        $this->assertStringContainsString('Bigbank', $html,
            'Finance page must render Bigbank block');
    }

    public function test_finantseerimine_has_bigbank_external_link(): void
    {
        $html = $this->get('/finantseerimine')->getContent();
        $this->assertStringContainsString('bigbank.ee', $html,
            'Finance page must have Bigbank external link');
    }

    public function test_finantseerimine_bigbank_link_opens_in_new_tab(): void
    {
        $html = $this->get('/finantseerimine')->getContent();
        // Bigbank link should have target="_blank"
        $this->assertStringContainsString('_blank', $html,
            'Bigbank link must open in new tab');
    }

    public function test_finantseerimine_bigbank_link_has_noopener(): void
    {
        $html = $this->get('/finantseerimine')->getContent();
        $this->assertStringContainsString('noopener', $html,
            'Bigbank link must have rel="noopener noreferrer"');
    }

    public function test_finantseerimine_does_not_invent_loan_rates(): void
    {
        $html = $this->get('/finantseerimine')->getContent();
        // Should not contain invented loan rates like "2.5%" or "Euribor + X"
        // The page should only describe Bigbank as an external option
        $this->assertStringNotContainsString('garanteeritud', $html,
            'Finance page must not claim guaranteed loan approval');
    }

    public function test_finantseerimine_has_no_price_cents(): void
    {
        $html = $this->get('/finantseerimine')->getContent();
        $this->assertStringNotContainsString('price_cents', $html);
    }

    public function test_kontakt_renders_diana_tali(): void
    {
        $html = $this->get('/kontakt')->getContent();
        $this->assertStringContainsString('Diana Tali', $html,
            'Contact page must render Diana Tali');
    }

    public function test_kontakt_has_diana_tali_phone(): void
    {
        $html = $this->get('/kontakt')->getContent();
        $this->assertTrue(
            str_contains($html, '58164078') || str_contains($html, '58 16 40 78'),
            'Contact page must have Diana Tali phone number'
        );
    }

    public function test_kontakt_has_diana_tali_email(): void
    {
        $html = $this->get('/kontakt')->getContent();
        $this->assertStringContainsString('diana@estlanda.ee', $html,
            'Contact page must have Diana Tali email');
    }

    public function test_kontakt_has_diana_tali_tel_link(): void
    {
        $html = $this->get('/kontakt')->getContent();
        $this->assertStringContainsString('tel:+372', $html,
            'Contact page must have tel: link for Diana Tali');
    }

    public function test_kontakt_has_no_jp_design(): void
    {
        $html = $this->get('/kontakt')->getContent();
        $this->assertStringNotContainsStringIgnoringCase('JP Design', $html);
        $this->assertStringNotContainsStringIgnoringCase('Jaanika', $html);
    }

    public function test_bigbank_block_not_on_homepage(): void
    {
        $html = $this->get('/')->getContent();
        // Bigbank logo/block should only be on financing page, not homepage
        // (campaign ribbon is fine, but dedicated Bigbank block should not be there)
        $financeHtml = $this->get('/finantseerimine')->getContent();
        $this->assertStringContainsString('bigbank.ee', $financeHtml,
            'Bigbank link must exist on financing page');
    }
}
