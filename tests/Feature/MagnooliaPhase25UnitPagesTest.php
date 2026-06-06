<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Magnoolia\MagnooliaPublicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesMagnooliaTestUnits;

/**
 * Phase 25 — Unit pages: routes, content, hreflang, hidden price policy.
 */
class MagnooliaPhase25UnitPagesTest extends TestCase
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
        $this->pubService->publish($this->admin->id, 'Phase 25 unit page test');
    }

    /** @test */
    public function et_unit_page_returns_200()
    {
        $response = $this->get('/kodud/b1-s1');
        $response->assertOk();
    }

    /** @test */
    public function ru_unit_page_returns_200()
    {
        $response = $this->get('/ru/kodud/b1-s1');
        $response->assertOk();
    }

    /** @test */
    public function en_unit_page_returns_200()
    {
        $response = $this->get('/en/homes/b1-s1');
        $response->assertOk();
    }

    /** @test */
    public function unknown_unit_slug_returns_404()
    {
        $response = $this->get('/kodud/does-not-exist');
        $response->assertNotFound();
    }

    /** @test */
    public function unit_page_contains_address()
    {
        $response = $this->get('/kodud/b1-s1');
        $response->assertOk();
        $response->assertSee('Magnoolia tee 1/1');
    }

    /** @test */
    public function unit_page_contains_floorplan_links()
    {
        $response = $this->get('/kodud/b1-s1');
        $response->assertOk();
        // Floorplan section must exist
        $response->assertSee('korrus', false);
    }

    /** @test */
    public function unit_page_uses_published_snapshot()
    {
        // After publication, unit page should return data (not 404)
        $response = $this->get('/kodud/b3-s1');
        $response->assertOk();
        $response->assertSee('Magnoolia tee 3/1');
    }

    /** @test */
    public function all_19_unit_slugs_return_200()
    {
        $slugs = [
            'b1-s1', 'b1-s2', 'b1-s3',
            'b3-s1', 'b3-s2', 'b3-s3', 'b3-s4',
            'b5-s1', 'b5-s2', 'b5-s3',
            'b7-s1', 'b7-s2', 'b7-s3',
            'b9-s1', 'b9-s2', 'b9-s3',
            'b11-s1', 'b11-s2', 'b11-s3',
        ];

        foreach ($slugs as $slug) {
            $this->get('/kodud/' . $slug)->assertOk("Expected 200 for /kodud/{$slug}");
        }
    }

    /** @test */
    public function unit_page_has_hreflang_links()
    {
        $response = $this->get('/kodud/b1-s1');
        $response->assertOk();
        $content = $response->getContent();
        $this->assertStringContainsString('hreflang="et"', $content);
        $this->assertStringContainsString('hreflang="ru"', $content);
        $this->assertStringContainsString('hreflang="en"', $content);
    }

    /** @test */
    public function unit_page_has_breadcrumb_schema()
    {
        $response = $this->get('/kodud/b1-s1');
        $response->assertOk();
        $content = $response->getContent();
        $this->assertStringContainsString('BreadcrumbList', $content);
        $this->assertStringContainsString('Kodud ja hinnad', $content);
    }
}
