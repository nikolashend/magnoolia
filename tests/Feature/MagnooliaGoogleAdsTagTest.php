<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Google Ads tag in <head> of every public page, switchable off for local/staging. */
class MagnooliaGoogleAdsTagTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_tag_is_in_the_head_of_public_pages_in_every_language(): void
    {
        config(['magnoolia.google_ads_id' => 'AW-16711711243']);

        foreach (['/', '/kodud-ja-hinnad', '/ru/kontakt', '/en/asukoht'] as $uri) {
            $html = $this->get($uri)->assertOk()->getContent();
            $head = substr($html, 0, strpos($html, '</head>'));

            $this->assertStringContainsString(
                'https://www.googletagmanager.com/gtag/js?id=AW-16711711243', $head, "{$uri}: gtag.js is not loaded in <head>."
            );
            $this->assertStringContainsString("gtag('config', 'AW-16711711243');", $head, "{$uri}: gtag config call is missing.");
        }
    }

    public function test_an_empty_id_leaves_the_page_without_any_google_tag(): void
    {
        config(['magnoolia.google_ads_id' => '']);

        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringNotContainsString('googletagmanager.com', $html);
        $this->assertStringNotContainsString('gtag(', $html);
    }

}
