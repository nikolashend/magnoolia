<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Phase 26 — Sisedisain (interior finish) page: Prestige content + Aet Piel.
 */
class MagnooliaPhase26SisedisainPageTest extends TestCase
{
    public function test_sisedisain_page_renders_successfully(): void
    {
        $this->get('/sisedisain')->assertStatus(200);
    }

    public function test_sisedisain_ru_page_renders(): void
    {
        $this->get('/ru/sisedisain')->assertStatus(200);
    }

    public function test_sisedisain_en_page_renders(): void
    {
        $this->get('/en/sisedisain')->assertStatus(200);
    }

    public function test_sisedisain_page_renders_aet_piel_block(): void
    {
        $html = $this->get('/sisedisain')->getContent();
        $this->assertStringContainsString('Aet Piel', $html,
            'Sisedisain page must render the Aet Piel block');
    }

    public function test_sisedisain_page_renders_aet_piel_contact(): void
    {
        $html = $this->get('/sisedisain')->getContent();
        $this->assertStringContainsString('aet@piel.ee', $html,
            'Sisedisain page must show Aet Piel email');
        $this->assertStringContainsString('555 38 586', $html,
            'Sisedisain page must show Aet Piel phone');
    }

    public function test_sisedisain_page_does_not_iframe_pptx(): void
    {
        $html = $this->get('/sisedisain')->getContent();
        $this->assertStringNotContainsStringIgnoringCase('.pptx', $html,
            'Sisedisain page must not embed or link to PPTX as primary content');
        $this->assertStringNotContainsStringIgnoringCase('<iframe', $html,
            'Sisedisain page must not use iframe to display content');
        $this->assertStringNotContainsStringIgnoringCase('<object', $html,
            'Sisedisain page must not use object tag for PPTX');
    }

    public function test_sisedisain_page_renders_prestige_content(): void
    {
        $html = $this->get('/sisedisain')->getContent();
        // Should contain Prestige package content (sanitary or materials)
        $this->assertTrue(
            str_contains($html, 'Prestige') || str_contains($html, 'sanitaar') || str_contains($html, 'Tikkurila'),
            'Sisedisain page must render Prestige package HTML content'
        );
    }

    public function test_sisedisain_page_renders_developer_disclaimer(): void
    {
        $html = $this->get('/sisedisain')->getContent();
        $this->assertTrue(
            str_contains($html, 'asendada') || str_contains($html, 'samaväärset'),
            'Sisedisain page must include developer replacement disclaimer'
        );
    }

    public function test_sisedisain_has_no_onedrive_links(): void
    {
        $html = $this->get('/sisedisain')->getContent();
        $this->assertStringNotContainsStringIgnoringCase('onedrive.live.com', $html);
        $this->assertStringNotContainsStringIgnoringCase('sharepoint.com', $html);
    }

    public function test_sisedisain_has_no_source_paths(): void
    {
        $html = $this->get('/sisedisain')->getContent();
        $this->assertStringNotContainsString('resources/source-assets', $html);
    }

    public function test_sisedisain_has_no_price_cents(): void
    {
        $html = $this->get('/sisedisain')->getContent();
        $this->assertStringNotContainsString('price_cents', $html);
    }

    public function test_sisedisain_no_jp_design_strings(): void
    {
        $html = $this->get('/sisedisain')->getContent();
        $this->assertStringNotContainsStringIgnoringCase('JP Design', $html);
        $this->assertStringNotContainsStringIgnoringCase('Jaanika', $html);
    }
}
