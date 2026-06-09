<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Magnoolia\MagnooliaPublicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesMagnooliaTestUnits;

/**
 * Phase 27 — No placeholder, skeleton, or template residue in rendered HTML.
 * Uses internal page loops (not data providers) to avoid slow setUp repetition.
 */
class MagnooliaPhase27NoPlaceholderVisualResidueTest extends TestCase
{
    use RefreshDatabase, CreatesMagnooliaTestUnits;

    private array $pages;

    protected function setUp(): void
    {
        parent::setUp();
        $admin = User::factory()->verified()->create(['role' => 'magnoolia_admin']);
        $this->create19TestUnits();
        app(MagnooliaPublicationService::class)->publish($admin->id, 'Phase 27 placeholder test');

        $this->pages = [
            '/', '/kodud-ja-hinnad', '/asendiplaan', '/asukoht',
            '/galerii', '/sisedisain', '/ehitusinfo',
            '/arhitektuur-ja-valisdisain', '/finantseerimine',
            '/ostuprotsess', '/kkk', '/kontakt',
        ];
    }

    public function test_no_lorem_ipsum_on_any_page(): void
    {
        foreach ($this->pages as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            $this->assertStringNotContainsString('Lorem ipsum', $html, "Page $url: no Lorem ipsum");
        }
    }

    public function test_no_jp_design_residue_on_any_page(): void
    {
        foreach ($this->pages as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            $this->assertStringNotContainsString(' JP ', $html, "Page $url: no JP design residue");
            $this->assertStringNotContainsString('Jaanika', $html, "Page $url: no Jaanika residue");
        }
    }

    public function test_no_price_cents_leakage_on_any_page(): void
    {
        foreach ($this->pages as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            $this->assertStringNotContainsString('price_cents', $html, "Page $url: price_cents must not leak");
        }
    }

    public function test_no_undefined_output_on_any_page(): void
    {
        foreach ($this->pages as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            $this->assertStringNotContainsString('undefined', $html, "Page $url: no 'undefined' output");
            $this->assertStringNotContainsString('[object Object]', $html, "Page $url: no [object Object]");
        }
    }

    public function test_no_onedrive_paths_on_any_page(): void
    {
        foreach ($this->pages as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            $this->assertStringNotContainsString('OneDrive', $html, "Page $url: no OneDrive path");
        }
    }

    public function test_no_source_assets_paths_on_any_page(): void
    {
        foreach ($this->pages as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            $this->assertStringNotContainsString('resources/source-assets', $html, "Page $url: no source-assets");
            $this->assertStringNotContainsString('storage/app/source-assets', $html, "Page $url: no source storage path");
        }
    }

    public function test_no_pptx_embeds_on_any_page(): void
    {
        foreach ($this->pages as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            $this->assertStringNotContainsString('.pptx', $html, "Page $url: no PPTX embed");
        }
    }

    public function test_no_translation_key_bleed_as_visible_text(): void
    {
        foreach ($this->pages as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            // Check for unresolved translation keys appearing as visible text content (between tags)
            // Pattern: >magnoolia.page.xxx< appearing as rendered text node
            $this->assertStringNotContainsString(
                '>magnoolia.page.',
                $html,
                "Page $url: unresolved page translation key must not appear as visible text"
            );
            $this->assertStringNotContainsString(
                '>magnoolia.nav.',
                $html,
                "Page $url: unresolved nav translation key must not appear as visible text"
            );
        }
    }

    public function test_contact_page_has_diana_tali_name(): void
    {
        $html = $this->get('/kontakt')->assertStatus(200)->getContent();
        $this->assertStringContainsString('Diana', $html, 'Contact page must mention Diana Tali');
    }

    public function test_sisedisain_has_no_gray_placeholder_blocks(): void
    {
        $html = $this->get('/sisedisain')->assertStatus(200)->getContent();
        $this->assertStringNotContainsString(
            'Aet Piel foto tuleb',
            $html,
            'Sisedisain must not have placeholder text for Aet Piel photo'
        );
    }
}
