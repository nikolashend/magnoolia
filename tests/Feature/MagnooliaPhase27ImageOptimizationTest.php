<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Magnoolia\MagnooliaPublicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesMagnooliaTestUnits;

/**
 * Phase 27 — Image optimization and HTML quality audit.
 * Uses internal loops to avoid slow setUp repetition.
 */
class MagnooliaPhase27ImageOptimizationTest extends TestCase
{
    use RefreshDatabase, CreatesMagnooliaTestUnits;

    private array $pages;

    protected function setUp(): void
    {
        parent::setUp();
        $admin = User::factory()->verified()->create(['role' => 'magnoolia_admin']);
        $this->create19TestUnits();
        app(MagnooliaPublicationService::class)->publish($admin->id, 'Phase 27 image test');

        $this->pages = [
            '/', '/kodud-ja-hinnad', '/asendiplaan',
            '/asukoht', '/galerii', '/sisedisain', '/kontakt',
        ];
    }

    public function test_no_onedrive_paths_in_any_page(): void
    {
        foreach ($this->pages as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            $this->assertStringNotContainsString('OneDrive', $html, "Page $url must not contain OneDrive path");
        }
    }

    public function test_no_local_source_paths_in_any_page(): void
    {
        foreach ($this->pages as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            $this->assertStringNotContainsString('resources/source-assets', $html);
            $this->assertStringNotContainsString('storage/app/source-assets', $html);
        }
    }

    public function test_no_pptx_iframe_embeds(): void
    {
        foreach ($this->pages as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            $this->assertStringNotContainsString('.pptx', $html, "Page $url must not embed .pptx files");
        }
    }

    public function test_no_broken_src_undefined(): void
    {
        foreach ($this->pages as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            $this->assertStringNotContainsString('src="undefined"', $html, "Page $url: no undefined src");
        }
    }

    public function test_hero_images_are_public_assets(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        preg_match_all('/src="([^"]+\.(?:jpg|jpeg|png|webp|gif))"/i', $html, $matches);
        foreach ($matches[1] ?? [] as $src) {
            if (str_starts_with($src, 'http')) continue;
            $this->assertStringNotContainsString('storage/app', $src, "Image src must not expose storage path: $src");
            $this->assertStringNotContainsString('resources/', $src, "Image src must not expose resources path: $src");
        }
    }

    public function test_images_have_width_attribute(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        $this->assertMatchesRegularExpression(
            '/width="\d+"/',
            $html,
            'Homepage must have at least one image with width attribute'
        );
    }

    public function test_gallery_uses_lazy_loading(): void
    {
        $html = $this->get('/galerii')->assertStatus(200)->getContent();
        $this->assertStringContainsString('loading="lazy"', $html, 'Gallery must use lazy loading');
    }

    public function test_no_nan_or_null_values(): void
    {
        foreach (['/', '/kodud-ja-hinnad', '/asendiplaan'] as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            $this->assertStringNotContainsString('NaN €', $html, "Page $url: no NaN €");
            $this->assertStringNotContainsString('null €', $html, "Page $url: no null €");
            $this->assertStringNotContainsString('[object Object]', $html, "Page $url: no [object Object]");
        }
    }
}
