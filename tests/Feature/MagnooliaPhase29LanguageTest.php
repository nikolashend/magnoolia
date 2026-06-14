<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesMagnooliaTestUnits;

/**
 * Phase 29 — ET/RU/EN rendering, no missing keys, no mixed-language leaks.
 */
class MagnooliaPhase29LanguageTest extends TestCase
{
    use RefreshDatabase;
    use CreatesMagnooliaTestUnits;

    protected function setUp(): void
    {
        parent::setUp();
        $this->create19TestUnits();
    }

    public function test_et_selection_title(): void
    {
        $html = $this->get('/asendiplaan')->assertStatus(200)->getContent();
        $this->assertStringContainsString('Vali Magnoolia kodu asukoht', $html);
    }

    public function test_ru_selection_title(): void
    {
        $html = $this->get('/ru/asendiplaan')->assertStatus(200)->getContent();
        $this->assertStringContainsString('Выберите расположение дома Magnoolia', $html);
    }

    public function test_en_selection_title(): void
    {
        $html = $this->get('/en/asendiplaan')->assertStatus(200)->getContent();
        $this->assertStringContainsString('Choose your Magnoolia home location', $html);
    }

    public function test_no_unresolved_translation_keys(): void
    {
        foreach (['/asendiplaan', '/ru/asendiplaan', '/en/asendiplaan', '/kodud-ja-hinnad', '/ehitusinfo'] as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            $this->assertStringNotContainsString('magnoolia.rowhouse.', $html,
                "Unresolved translation key rendered on {$url}");
        }
    }
}
