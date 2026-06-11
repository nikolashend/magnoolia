<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Phase 28 — Logo integration tests
 * Verifies Magnoolia and Estlanda logos are properly integrated.
 */
class MagnooliaPhase28LogoIntegrationTest extends TestCase
{
    public function test_header_uses_real_logo_image(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $html = $response->getContent();

        // Header should use the real logo image if the file exists
        if (file_exists(public_path('assets/magnoolia/logos/magnoolia-light.webp'))) {
            $this->assertStringContainsString('magnoolia-light', $html,
                'Header must use real Magnoolia logo image when file exists');
        } else {
            // Fallback to text is acceptable if logo file missing
            $this->assertStringContainsString('Magnoolia', $html,
                'Header must at minimum show Magnoolia text/brand');
        }
    }

    public function test_footer_estlanda_logo_rendered(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $html = $response->getContent();

        if (file_exists(public_path('assets/magnoolia/logos/estlanda-2.webp'))) {
            $this->assertStringContainsString('estlanda', strtolower($html),
                'Footer must reference Estlanda logo when file exists');
        }
    }

    public function test_logo_has_alt_text(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $html = $response->getContent();

        if (str_contains($html, 'magnoolia-light.webp') || str_contains($html, 'magnoolia-dark.webp')) {
            $this->assertStringContainsString('alt="Magnoolia Kodud"', $html,
                'Logo image must have descriptive alt text');
        }
    }

    public function test_logo_has_width_and_height(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $html = $response->getContent();

        if (str_contains($html, 'magnoolia-light.webp') || str_contains($html, 'magnoolia-dark.webp')) {
            $this->assertMatchesRegularExpression('/magnoolia-(light|dark)\.webp[^>]*width="\d+"/',
                $html, 'Logo img must have width attribute');
        }
    }

    public function test_footer_has_magnoolia_brand(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $html = $response->getContent();

        // Footer must have Magnoolia brand (either logo or text)
        $footerContent = '';
        if (preg_match('/<footer[^>]*>(.*?)<\/footer>/si', $html, $m)) {
            $footerContent = $m[1];
        }

        $hasMagnooliaLogo = str_contains($footerContent, 'magnoolia-dark');
        $hasMagnooliaText = str_contains($footerContent, 'Magnoolia');
        $this->assertTrue($hasMagnooliaLogo || $hasMagnooliaText,
            'Footer must contain Magnoolia brand (logo or text)');
    }

    public function test_no_text_only_fallback_when_logo_exists(): void
    {
        // If logo file exists, the header must NOT show only the text fallback
        if (!file_exists(public_path('assets/magnoolia/logos/magnoolia-light.webp'))) {
            $this->markTestSkipped('Logo file not found - skipping');
        }

        $response = $this->get('/');
        $response->assertStatus(200);
        $html = $response->getContent();

        // The old text-only fallback span with the specific style
        $this->assertStringNotContainsString(
            'font-size:22px;font-weight:700;color:#1E1F24;letter-spacing:0.05em',
            $html,
            'When real logo exists, text-only fallback span must not be used'
        );
    }
}
