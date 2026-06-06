<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Magnoolia\MagnooliaPublicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesMagnooliaTestUnits;

/**
 * Phase 25 — Public surface: all Phase 25 routes return 200 in all locales,
 * no template residue, no forbidden strings.
 */
class MagnooliaPhase25MobilePublicSurfaceTest extends TestCase
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
        $this->pubService->publish($this->admin->id, 'Mobile surface test');
    }

    /**
     * All new Phase 25 public routes return 200.
     * @test
     */
    public function all_phase25_routes_return_200()
    {
        $routes = [
            '/kodud/b1-s1',
            '/ru/kodud/b1-s1',
            '/en/homes/b1-s1',
            '/vordle',
            '/ru/sravnit',
            '/en/compare',
            '/vordle?units=b1-s1,b3-s1',
        ];

        foreach ($routes as $url) {
            $this->get($url)->assertOk("Expected 200 for {$url}");
        }
    }

    /** @test */
    public function unit_page_has_no_template_residue()
    {
        $response = $this->get('/kodud/b1-s1');
        $response->assertOk();
        $content = $response->getContent();

        $forbidden = [
            '{{ ', '@php', '@if(', '@foreach',
            'undefined', 'NaN', 'null null',
            'Lorem ipsum',
        ]; // Note: '}}' excluded because it is valid inside nested JSON/JS objects

        foreach ($forbidden as $str) {
            $this->assertStringNotContainsString($str, $content, "Template residue found: {$str}");
        }
    }

    /** @test */
    public function compare_page_has_no_template_residue()
    {
        $response = $this->get('/vordle?units=b1-s1,b3-s1');
        $response->assertOk();
        $content = $response->getContent();

        $this->assertStringNotContainsString('{{ ', $content);
        // '}}' excluded — valid JSON/JS nested-object closing sequence
    }

    /** @test */
    public function unit_page_has_cta_above_fold_content()
    {
        $response = $this->get('/kodud/b1-s1');
        $response->assertOk();
        $content = $response->getContent();

        // Address and CTA must be present
        $this->assertStringContainsString('Magnoolia tee 1/1', $content);
        $this->assertStringContainsString('Küsi selle kodu kohta', $content);
    }

    /** @test */
    public function asendiplaan_has_no_broken_zero_units_display()
    {
        $response = $this->get('/asendiplaan');
        $response->assertOk();
        $content = $response->getContent();

        $this->assertStringNotContainsString('0 kodu', $content);
    }

    /** @test */
    public function similar_units_section_renders_without_error()
    {
        $response = $this->get('/kodud/b1-s1');
        $response->assertOk();
        // Just ensure no exception is thrown with similar units section
        $this->assertStringContainsString('Magnoolia tee', $response->getContent());
    }

    /** @test */
    public function unit_page_adjacent_navigation_renders()
    {
        $response = $this->get('/kodud/b1-s2');
        $response->assertOk();
        $content = $response->getContent();

        // Should have prev/next navigation
        $this->assertStringContainsString('Magnoolia tee 1/1', $content);  // prev
        $this->assertStringContainsString('Magnoolia tee 1/3', $content);  // next
    }

    /** @test */
    public function compare_page_empty_units_renders_gracefully()
    {
        $response = $this->get('/vordle?units=');
        $response->assertOk();
        // No exception, page renders
        $this->assertStringContainsString('Vali', $response->getContent());
    }
}
