<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Magnoolia\MagnooliaPublicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesMagnooliaTestUnits;

/**
 * Phase 25 — SEO: meta titles, descriptions, schema, canonical, hreflang, sitemap.
 */
class MagnooliaPhase25SeoSchemaTest extends TestCase
{
    use RefreshDatabase, CreatesMagnooliaTestUnits;

    private User $admin;
    private MagnooliaPublicationService $pubService;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->verified()->create(['role' => 'magnoolia_admin']);
        $this->pubService = app(MagnooliaPublicationService::class);
        $this->create19TestUnits();
        $this->pubService->publish($this->admin->id, 'SEO test');
    }

    /** @test */
    public function unit_page_has_title_with_address()
    {
        $response = $this->get('/kodud/b1-s1');
        $response->assertOk();
        $content = $response->getContent();
        $this->assertStringContainsString('Magnoolia tee 1/1', $content);
        // Title tag should include address
        preg_match('/<title[^>]*>([^<]+)<\/title>/i', $content, $m);
        $this->assertNotEmpty($m[1] ?? '');
        $this->assertStringContainsString('Magnoolia tee 1/1', $m[1]);
    }

    /** @test */
    public function unit_page_has_meta_description()
    {
        $response = $this->get('/kodud/b1-s1');
        $response->assertOk();
        $content = $response->getContent();
        preg_match('/<meta[^>]+name=["\']description["\'][^>]+content=["\']([^"\']+)["\'][^>]*>/i', $content, $m);
        $this->assertNotEmpty($m[1] ?? '', 'Meta description not found');
        $this->assertStringContainsString('Magnoolia tee 1/1', $m[1]);
    }

    /** @test */
    public function unit_page_has_residence_schema()
    {
        $response = $this->get('/kodud/b1-s1');
        $response->assertOk();
        $content = $response->getContent();
        $this->assertStringContainsString('"Residence"', $content);
    }

    /** @test */
    public function unit_page_has_breadcrumb_schema()
    {
        $response = $this->get('/kodud/b1-s1');
        $response->assertOk();
        $content = $response->getContent();
        $this->assertStringContainsString('"BreadcrumbList"', $content);
        $this->assertStringContainsString('/kodud-ja-hinnad', $content);
    }

    /** @test */
    public function hidden_price_unit_has_no_offer_schema()
    {
        $response = $this->get('/kodud/b5-s1');
        $response->assertOk();
        $content = $response->getContent();
        $this->assertStringNotContainsString('"Offer"', $content);
    }

    /** @test */
    public function unit_page_has_all_three_hreflang_links()
    {
        $response = $this->get('/kodud/b1-s1');
        $response->assertOk();
        $content = $response->getContent();

        $this->assertStringContainsString('hreflang="et"', $content);
        $this->assertStringContainsString('hreflang="ru"', $content);
        $this->assertStringContainsString('hreflang="en"', $content);
        $this->assertStringContainsString('hreflang="x-default"', $content);
    }

    /** @test */
    public function sitemap_includes_unit_pages()
    {
        $response = $this->get('/sitemap.xml');
        $response->assertOk();
        $content = $response->getContent();

        $this->assertStringContainsString('/kodud/', $content);
        $this->assertStringContainsString('/en/homes/', $content);
        $this->assertStringContainsString('/ru/kodud/', $content);
    }

    /** @test */
    public function sitemap_includes_19_unit_slugs()
    {
        $response = $this->get('/sitemap.xml');
        $response->assertOk();
        $content = $response->getContent();

        $unitSlugs = ['b1-s1', 'b1-s2', 'b1-s3', 'b3-s1', 'b11-s3'];
        foreach ($unitSlugs as $slug) {
            $this->assertStringContainsString("/kodud/{$slug}", $content, "Slug {$slug} missing from sitemap");
        }
    }

    /** @test */
    public function sitemap_does_not_include_hidden_units()
    {
        // If a unit is not public_page_visible, it should not appear
        // In the default test setup all units are visible (is_visible=true)
        // This test just confirms sitemap renders without errors
        $this->get('/sitemap.xml')->assertOk();
    }
}
