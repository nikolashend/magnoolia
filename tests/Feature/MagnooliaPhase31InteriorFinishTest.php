<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Phase 31 — /ehitusinfo interior finish & equipment standard section.
 */
class MagnooliaPhase31InteriorFinishTest extends TestCase
{
    private function html(string $url = '/ehitusinfo'): string
    {
        return $this->get($url)->assertStatus(200)->getContent();
    }

    public function test_section_and_five_categories_render(): void
    {
        $html = $this->html();
        $this->assertStringContainsString('id="siseviimistlus"', $html);
        $this->assertSame(5, substr_count($html, 'mg-if-card__summary'), 'Expected 5 category cards');
        foreach (['Elektri- ja nutiseadmed', 'Sanitaartehnika', 'Plaadid ja vannitoa viimistlus', 'Siseviimistlus', 'Lisavalikud lisatasu eest'] as $t) {
            $this->assertStringContainsString($t, $html, "Missing category: {$t}");
        }
    }

    public function test_key_product_names_present(): void
    {
        $html = $this->html();
        foreach ([
            'Jung LS 990', 'Damixa Core', 'Balteco Onyx 40', 'RAK Resort', 'ACO plaaditud',
            'Tikkurila Symphony Opus II', 'Swedoor', 'Pure Alt Bazalt', 'Freedust Grey',
            'BETA SLIM', 'Samsung', 'kalasabaparkett',
        ] as $name) {
            $this->assertStringContainsString($name, $html, "Missing product: {$name}");
        }
    }

    public function test_paid_options_and_disclaimer_and_ai_block(): void
    {
        $html = $this->html();
        $this->assertStringContainsString('Lisatasu eest', $html, 'Paid-option badge/label must show');
        $this->assertStringContainsString('mg-if-item__badge--paid', $html);
        $this->assertStringContainsString('asendada veebilehel näidatud tooted samaväärsete', $html, 'Equivalence disclaimer must show');
        $this->assertStringContainsString('Milline siseviimistlus on Magnoolia kodudes planeeritud?', $html, 'AI answer block must show');
    }

    public function test_optimised_webp_used_no_raw_jpg(): void
    {
        $html = $this->html();
        $this->assertMatchesRegularExpression('#assets/magnoolia/siseviimistlus/[a-z0-9-]+\.webp#', $html);
        $this->assertFileExists(public_path('assets/magnoolia/siseviimistlus/interior-living-day.webp'));
        $this->assertFileExists(public_path('assets/magnoolia/siseviimistlus/electrical-overview.webp'));
        // no raw Phase-31 source JPG referenced publicly
        $this->assertStringNotContainsString('materials/phase31', $html);
        $this->assertStringNotContainsString('valmis tuba', strtolower($html));
    }

    public function test_cta_reuses_inquiry_drawer_with_analytics(): void
    {
        $html = $this->html();
        $this->assertStringContainsString('data-mg-analytics="ehitusinfo-siseviimistlus-cta"', $html);
        $this->assertStringContainsString('data-source-component="ehitusinfo_siseviimistlus"', $html);
        $this->assertStringContainsString('data-mg-inquiry-open', $html);
        $this->assertStringNotContainsString('price_cents', $html);
    }

    public function test_ru_and_en_render(): void
    {
        $this->assertStringContainsString('Внутренняя отделка и стандарт оснащения', $this->html('/ru/ehitusinfo'));
        $this->assertStringContainsString('Interior finish and equipment standard', $this->html('/en/ehitusinfo'));
        // no unresolved keys
        $this->assertStringNotContainsString('magnoolia.interior.', $this->html());
    }
}
