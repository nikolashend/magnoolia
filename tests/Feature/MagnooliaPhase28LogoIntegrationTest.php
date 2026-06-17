<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Logo integration tests.
 *
 * Phase 32: updated to assert the CURRENT intended logo implementation.
 * The header renders the real wordmark `magnoolia-logo2.webp` (desktop) with a
 * dedicated mobile source; the footer renders `magnoolia-footer.webp` plus the
 * Estlanda developer logo. The previous `magnoolia-light` filename is legacy and
 * no longer used in the header — the old assertion was stale, not a real bug.
 */
class MagnooliaPhase28LogoIntegrationTest extends TestCase
{
    private const HEADER_LOGO = 'assets/magnoolia/logos/magnoolia-logo2.webp';

    public function test_header_uses_real_logo_image(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $html = $response->getContent();

        if (file_exists(public_path(self::HEADER_LOGO))) {
            $this->assertStringContainsString('magnoolia-logo2', $html,
                'Header must use the real Magnoolia wordmark (magnoolia-logo2.webp) when the file exists');
        } else {
            $this->assertStringContainsString('Magnoolia', $html,
                'Header must at minimum show the Magnoolia text/brand');
        }
    }

    public function test_footer_estlanda_logo_rendered(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $html = $response->getContent();

        if (file_exists(public_path('assets/magnoolia/logos/estlanda-2.webp'))) {
            $this->assertStringContainsString('estlanda', strtolower($html),
                'Footer must reference the Estlanda developer logo when the file exists');
        }
    }

    public function test_logo_has_alt_text(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $html = $response->getContent();

        if (str_contains($html, 'magnoolia-logo2')) {
            $this->assertStringContainsString('alt="Magnoolia Kodud"', $html,
                'Header logo image must have descriptive alt text');
        }
    }

    public function test_logo_has_width_and_height(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $html = $response->getContent();

        if (str_contains($html, 'magnoolia-logo2.webp')) {
            // The <img> carries explicit width/height to prevent layout shift (CLS).
            $this->assertMatchesRegularExpression('/magnoolia-logo2\.webp"[^>]*\n?[^>]*alt="Magnoolia Kodud"[^>]*\n?[^>]*width="\d+"\s+height="\d+"/',
                $html, 'Header logo <img> must carry explicit width and height attributes');
        }
    }

    public function test_footer_has_magnoolia_brand(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $html = $response->getContent();

        $footerContent = '';
        if (preg_match('/<footer[^>]*>(.*?)<\/footer>/si', $html, $m)) {
            $footerContent = $m[1];
        }

        $hasMagnooliaLogo = str_contains($footerContent, 'magnoolia-footer') || str_contains($footerContent, 'magnoolia-logo2') || str_contains($footerContent, 'magnoolia-dark');
        $hasMagnooliaText = str_contains($footerContent, 'Magnoolia');
        $this->assertTrue($hasMagnooliaLogo || $hasMagnooliaText,
            'Footer must contain the Magnoolia brand (logo or text)');
    }

    public function test_no_text_only_fallback_when_logo_exists(): void
    {
        if (!file_exists(public_path(self::HEADER_LOGO))) {
            $this->markTestSkipped('Header logo file not found - skipping');
        }

        $response = $this->get('/');
        $response->assertStatus(200);
        $html = $response->getContent();

        // The text-only fallback span must NOT be emitted when the real logo exists.
        $this->assertStringNotContainsString(
            'font-size:22px;font-weight:700;color:#1E1F24;letter-spacing:0.05em',
            $html,
            'When the real logo exists, the text-only fallback span must not be used'
        );
    }
}
