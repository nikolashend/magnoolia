<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Phase 28 — No visual residue strict test
 * Verifies no forbidden strings appear in rendered public HTML.
 */
class MagnooliaPhase28NoVisualResidueStrictTest extends TestCase
{
    use RefreshDatabase;

    private array $publicPages = [
        '/', '/kodud-ja-hinnad', '/asendiplaan', '/asukoht',
        '/ehitusinfo', '/sisedisain', '/galerii', '/kkk',
        '/kontakt', '/ostuprotsess', '/finantseerimine',
    ];

    public function test_no_forbidden_technical_strings_in_html(): void
    {
        $forbidden = [
            'price_cents',
            'OneDrive',
            'C:\\Users\\',
            'resources/source-assets',
            'storage/app/source-assets',
        ];

        foreach ($this->publicPages as $url) {
            $response = $this->get($url);
            $response->assertStatus(200);
            $html = $response->getContent();

            foreach ($forbidden as $string) {
                $this->assertStringNotContainsString($string, $html,
                    "Page {$url} must not contain '{$string}'");
            }
        }
    }

    public function test_no_undefined_nan_null_in_visible_text(): void
    {
        $forbidden = ['undefined', 'NaN', 'null €', '[object Object]'];

        foreach ($this->publicPages as $url) {
            $response = $this->get($url);
            $response->assertStatus(200);
            $html = $response->getContent();

            foreach ($forbidden as $string) {
                // Check visible text only (strip tags)
                $textContent = strip_tags($html);
                $this->assertStringNotContainsString($string, $textContent,
                    "Page {$url} must not contain '{$string}' in visible text");
            }
        }
    }

    public function test_no_placeholder_text_visible(): void
    {
        foreach ($this->publicPages as $url) {
            $response = $this->get($url);
            $response->assertStatus(200);
            $html = $response->getContent();

            // Generic "lorem" check
            $this->assertStringNotContainsString('lorem ipsum', strtolower($html),
                "Page {$url} must not contain lorem ipsum");
        }
    }

    public function test_no_generic_meta_title_on_pages(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $html = $response->getContent();

        $this->assertStringNotContainsString('Metateave — Magnoolia', $html,
            'Homepage must not show generic "Metateave" meta title');
        $this->assertStringNotContainsString('Esmaklassilised korterid', $html,
            'Homepage must not show generic meta description "Esmaklassilised korterid"');
    }

    public function test_no_fake_phone_number(): void
    {
        foreach ($this->publicPages as $url) {
            $response = $this->get($url);
            $response->assertStatus(200);
            $textContent = strip_tags($response->getContent());

            $this->assertStringNotContainsString('+372 000', $textContent,
                "Page {$url} must not contain placeholder phone +372 000");
        }
    }

    public function test_no_placeholder_email_visible(): void
    {
        foreach ($this->publicPages as $url) {
            $response = $this->get($url);
            $response->assertStatus(200);
            $textContent = strip_tags($response->getContent());

            $this->assertStringNotContainsString('info@magnoolia.ee', $textContent,
                "Page {$url} must not show placeholder info@magnoolia.ee (correct is diana@estlanda.ee)");
        }
    }
}
