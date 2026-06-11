<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Phase 28 — Language purity strict test
 * Ensures no accidental cross-language UI text leaks on ET/RU/EN pages.
 * Brand names (Magnoolia, Estlanda, Bigbank, Aet Piel, Diana Tali) are allowed.
 */
class MagnooliaPhase28LanguagePurityStrictTest extends TestCase
{
    use RefreshDatabase;

    private array $etPages = ['/', '/kodud-ja-hinnad', '/asendiplaan', '/ehitusinfo', '/sisedisain', '/kontakt'];
    private array $ruPages = ['/ru', '/ru/kodud-ja-hinnad', '/ru/kontakt'];
    private array $enPages = ['/en', '/en/kodud-ja-hinnad', '/en/kontakt'];

    /** ET pages must not contain accidental English UI strings */
    public function test_et_pages_have_no_english_ui_text(): void
    {
        $forbidden = [
            'Price to be confirmed',
            'Prices and availability are being finalised',
            'Showing all',
            'Tip: first select',
            'Choose a home',
            'Ready kevad',
            'Phase I',
            'Phase II',
            'Available',
            'Reserved',
            'Sold',
        ];

        // These phrases are ALLOWED (appear in EN values inside JSON-LD schema or meta)
        // We'll check visible text context specifically for the UI phrases

        foreach ($this->etPages as $url) {
            $response = $this->get($url);
            $response->assertStatus(200);
            $html = $response->getContent();

            foreach ($forbidden as $phrase) {
                // Allow it if it only appears inside JSON-LD or HTML attributes (not visible UI text)
                $visibleOccurrences = $this->countVisibleTextOccurrences($html, $phrase);
                $this->assertSame(0, $visibleOccurrences,
                    "ET page {$url} must not contain English UI phrase '{$phrase}' as visible text");
            }
        }
    }

    /** RU pages must not contain accidental ET/EN UI text */
    public function test_ru_pages_have_no_estonian_ui_text(): void
    {
        $forbidden = [
            'Hind täpsustamisel',
            'Küsi pakkumist',
            'Näitan',
            'Valmib',
            'Broneeritud',
            'Müüdud',
            'Showing all',
            'Price to be confirmed',
        ];

        foreach ($this->ruPages as $url) {
            $response = $this->get($url);
            $response->assertStatus(200);
            $html = $response->getContent();

            foreach ($forbidden as $phrase) {
                $visibleOccurrences = $this->countVisibleTextOccurrences($html, $phrase);
                $this->assertSame(0, $visibleOccurrences,
                    "RU page {$url} must not contain ET/EN phrase '{$phrase}' as visible text");
            }
        }
    }

    /** EN pages must not contain accidental ET/RU UI text */
    public function test_en_pages_have_no_estonian_ui_text(): void
    {
        $forbidden = [
            'Hind täpsustamisel',
            'Näitan',
            'Küsi pakkumist',
            'Valmib kevadel',
            'Broneeritud',
            'Müüdud',
        ];

        foreach ($this->enPages as $url) {
            $response = $this->get($url);
            $response->assertStatus(200);
            $html = $response->getContent();

            foreach ($forbidden as $phrase) {
                $visibleOccurrences = $this->countVisibleTextOccurrences($html, $phrase);
                $this->assertSame(0, $visibleOccurrences,
                    "EN page {$url} must not contain ET phrase '{$phrase}' as visible text");
            }
        }
    }

    /** ET pages must use Estonian count label (Näitan X kodu), not English */
    public function test_et_homepage_count_label_is_estonian(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $html = $response->getContent();

        // If the filter count is rendered, it should use Estonian text
        if (str_contains($html, 'mg-filter-count') || str_contains($html, 'Näitan')) {
            $this->assertStringNotContainsString('Showing all 19', $html,
                'ET homepage must not show English "Showing all 19" in visible text');
        }
    }

    /** ET page completion date should not say "Ready" */
    public function test_et_pages_use_estonian_completion_label(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $html = $response->getContent();

        // "Ready" as a visible label before a year - this is the English "Ready 2027" issue
        $this->assertStringNotContainsString('>Ready ', $html,
            'ET homepage must not contain English "Ready" as visible label');
    }

    /**
     * Count occurrences of a phrase that appear as visible text content
     * (not inside script blocks, style blocks, JSON-LD, HTML attributes, or comments)
     */
    private function countVisibleTextOccurrences(string $html, string $phrase): int
    {
        // Remove ALL script blocks (including JavaScript fallback strings)
        $stripped = preg_replace('/<script[^>]*>.*?<\/script>/si', '', $html);
        // Remove style blocks
        $stripped = preg_replace('/<style[^>]*>.*?<\/style>/si', '', $stripped ?? '');
        // Remove HTML comments
        $stripped = preg_replace('/<!--.*?-->/s', '', $stripped ?? '');
        // Remove all HTML tags (keep text content only)
        $textOnly = strip_tags($stripped ?? '');
        // Normalize whitespace
        $textOnly = preg_replace('/\s+/', ' ', $textOnly) ?? '';
        return substr_count($textOnly, $phrase);
    }
}
