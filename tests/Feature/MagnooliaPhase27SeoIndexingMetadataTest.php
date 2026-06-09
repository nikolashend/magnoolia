<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Magnoolia\MagnooliaPublicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesMagnooliaTestUnits;

/**
 * Phase 27 P0 — SEO metadata, indexing and canonical consistency.
 */
class MagnooliaPhase27SeoIndexingMetadataTest extends TestCase
{
    use RefreshDatabase, CreatesMagnooliaTestUnits;

    protected function setUp(): void
    {
        parent::setUp();
        $admin = User::factory()->verified()->create(['role' => 'magnoolia_admin']);
        $this->create19TestUnits();
        app(MagnooliaPublicationService::class)->publish($admin->id, 'Phase 27 SEO test');
    }

    public function test_no_page_has_metateave_title(): void
    {
        $pages = ['/', '/kodud-ja-hinnad', '/asendiplaan', '/asukoht', '/kontakt'];
        foreach ($pages as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            $this->assertStringNotContainsString(
                'Metateave — Magnoolia',
                $html,
                "Page $url must not have generic 'Metateave' title"
            );
        }
    }

    public function test_no_page_has_generic_description(): void
    {
        $pages = ['/', '/kodud-ja-hinnad', '/asendiplaan', '/asukoht', '/kontakt'];
        foreach ($pages as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            $this->assertStringNotContainsString(
                'Esmaklassilised korterid ja kinnisvara',
                $html,
                "Page $url must not have the generic placeholder description"
            );
        }
    }

    public function test_staging_mode_renders_noindex(): void
    {
        config(['magnoolia.seo.indexable' => false]);
        config(['magnoolia.seo.noindex' => true]);

        $html = $this->get('/')->assertStatus(200)->getContent();
        $this->assertStringContainsString('noindex,nofollow', $html);
        $this->assertStringNotContainsString('index,follow', $html);
    }

    public function test_indexable_mode_renders_index_follow(): void
    {
        config(['magnoolia.seo.indexable' => true]);
        config(['magnoolia.seo.noindex' => false]);

        $html = $this->get('/')->assertStatus(200)->getContent();
        $this->assertStringContainsString('index,follow', $html);
        $this->assertStringNotContainsString('noindex,nofollow', $html);
    }

    public function test_indexable_mode_includes_max_image_preview(): void
    {
        config(['magnoolia.seo.indexable' => true]);
        config(['magnoolia.seo.noindex' => false]);

        $html = $this->get('/')->assertStatus(200)->getContent();
        $this->assertStringContainsString('max-image-preview:large', $html);
    }

    public function test_canonical_present_on_every_page(): void
    {
        $pages = ['/', '/kodud-ja-hinnad', '/asendiplaan', '/asukoht'];
        foreach ($pages as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            $this->assertStringContainsString(
                '<link rel="canonical"',
                $html,
                "Page $url must have canonical link"
            );
        }
    }

    public function test_hreflang_present_on_homepage(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        $this->assertStringContainsString('hreflang="et"', $html);
        $this->assertStringContainsString('hreflang="ru"', $html);
        $this->assertStringContainsString('hreflang="en"', $html);
        $this->assertStringContainsString('hreflang="x-default"', $html);
    }

    public function test_og_tags_present_on_homepage(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        $this->assertStringContainsString('og:title', $html);
        $this->assertStringContainsString('og:description', $html);
        $this->assertStringContainsString('og:image', $html);
        $this->assertStringContainsString('og:url', $html);
    }

    public function test_each_public_page_has_exactly_one_h1(): void
    {
        $pages = ['/', '/kodud-ja-hinnad', '/asendiplaan', '/asukoht', '/kontakt'];
        foreach ($pages as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            $h1Count = substr_count(strtolower($html), '<h1');
            $this->assertEquals(1, $h1Count, "Page $url must have exactly one H1 (found $h1Count)");
        }
    }

    public function test_homepage_title_contains_magnoolia_brand(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        $this->assertMatchesRegularExpression('/<title>[^<]*Magnoolia[^<]*<\/title>/i', $html);
    }

    public function test_page_titles_are_descriptive(): void
    {
        $expected = [
            '/'                => 'Magnoolia',
            '/kodud-ja-hinnad' => 'kodud',
            '/asendiplaan'     => 'asendiplaan',
            '/asukoht'         => 'asukoht',
        ];

        foreach ($expected as $url => $keyword) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            preg_match('/<title>([^<]+)<\/title>/i', $html, $matches);
            $title = $matches[1] ?? '';
            $this->assertStringContainsString(
                strtolower($keyword),
                strtolower($title),
                "Page $url title '$title' should contain '$keyword'"
            );
        }
    }

    public function test_no_staging_domain_leaked_in_canonical_when_public_domain_set(): void
    {
        config(['magnoolia.seo.canonical_base' => 'https://magnoolia.ee']);
        config(['magnoolia.seo.indexable' => true]);
        config(['magnoolia.seo.noindex' => false]);

        $html = $this->get('/')->assertStatus(200)->getContent();
        // Canonical should not use staging domain
        $this->assertStringNotContainsString(
            'magnoolia.adme.ee',
            $html,
            'Canonical URL must not contain staging domain when public domain is configured'
        );
    }
}
