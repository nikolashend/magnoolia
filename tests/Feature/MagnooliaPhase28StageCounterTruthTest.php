<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Phase 28 — Stage counter truth test
 * Asserts that no public page contains "0 kodu", "0 homes" or empty stage counters.
 */
class MagnooliaPhase28StageCounterTruthTest extends TestCase
{
    use RefreshDatabase;

    private array $pages = ['/', '/kodud-ja-hinnad', '/asendiplaan'];

    public function test_no_zero_kodu_on_public_pages(): void
    {
        foreach ($this->pages as $url) {
            $response = $this->get($url);
            $response->assertStatus(200);
            $html = $response->getContent();

            // Forbid "0 kodu" as visible content
            $this->assertStringNotContainsString('>0 kodu<', $html,
                "Page {$url} must not show '0 kodu' as visible text");

            // Forbid stage group labels showing "0 kodu"
            $this->assertDoesNotMatch('/etapp[^<]{0,60}>\s*0\s*kodu/i', $html,
                "Page {$url} must not show stage with 0 kodu");
        }
    }

    public function test_no_zero_homes_on_public_pages(): void
    {
        foreach ($this->pages as $url) {
            $response = $this->get($url);
            $response->assertStatus(200);
            $html = $response->getContent();

            // Forbid "0 homes" as visible content (on EN pages too)
            $this->assertStringNotContainsString('>0 homes<', $html,
                "Page {$url} must not show '0 homes' as visible text");
        }
    }

    public function test_stage_counts_are_correct_on_homepage(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $html = $response->getContent();

        // Homepage should mention 19 total homes
        $this->assertStringContainsString('19', $html,
            'Homepage must contain "19" as total home count');
    }

    public function test_asendiplaan_has_stage_info(): void
    {
        $response = $this->get('/asendiplaan');
        $response->assertStatus(200);
        $html = $response->getContent();

        // Asendiplaan must show stage info
        $this->assertStringContainsString('etapp', strtolower($html),
            'Asendiplaan page must show stage information');
    }

    public function test_homepage_ru_has_correct_stage_labels(): void
    {
        $response = $this->get('/ru');
        $response->assertStatus(200);
        $html = $response->getContent();

        // RU homepage should not show Estonian-only "etapp" but Russian "этап"
        $this->assertStringContainsString('этап', $html,
            'RU homepage must show Russian "этап" stage label');
    }

    private function assertDoesNotMatch(string $pattern, string $subject, string $message = ''): void
    {
        $this->assertSame(0, preg_match($pattern, $subject), $message);
    }
}
