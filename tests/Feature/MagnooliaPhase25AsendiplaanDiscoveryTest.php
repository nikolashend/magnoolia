<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Magnoolia\MagnooliaPublicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesMagnooliaTestUnits;

/**
 * Phase 25 — Asendiplaan shows correct unit counts and discovery UI.
 */
class MagnooliaPhase25AsendiplaanDiscoveryTest extends TestCase
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
        $this->pubService->publish($this->admin->id, 'Asendiplaan discovery test');
    }

    /** @test */
    public function asendiplaan_returns_200()
    {
        $this->get('/asendiplaan')->assertOk();
    }

    /** @test */
    public function asendiplaan_renders_19_units()
    {
        $response = $this->get('/asendiplaan');
        $response->assertOk();
        $content = $response->getContent();

        // All 19 unit addresses must be present
        $addresses = [
            'Magnoolia tee 1/1', 'Magnoolia tee 1/2', 'Magnoolia tee 1/3',
            'Magnoolia tee 3/1', 'Magnoolia tee 3/2', 'Magnoolia tee 3/3', 'Magnoolia tee 3/4',
            'Magnoolia tee 5/1',
        ];
        foreach ($addresses as $addr) {
            $this->assertStringContainsString($addr, $content, "Missing unit: {$addr}");
        }
    }

    /** @test */
    public function asendiplaan_stage_1_count_is_7()
    {
        $response = $this->get('/asendiplaan');
        $response->assertOk();
        $content = $response->getContent();
        // Stage 1 buildings
        $this->assertStringContainsString('Magnoolia tee 1', $content);
        $this->assertStringContainsString('Magnoolia tee 3', $content);
    }

    /** @test */
    public function asendiplaan_stage_2_count_is_12()
    {
        $response = $this->get('/asendiplaan');
        $response->assertOk();
        $content = $response->getContent();
        $this->assertStringContainsString('Magnoolia tee 5', $content);
        $this->assertStringContainsString('Magnoolia tee 7', $content);
        $this->assertStringContainsString('Magnoolia tee 9', $content);
        $this->assertStringContainsString('Magnoolia tee 11', $content);
    }

    /** @test */
    public function asendiplaan_has_no_zero_units_stage_display()
    {
        $response = $this->get('/asendiplaan');
        $response->assertOk();
        $content = $response->getContent();

        // Should NOT show "0 kodu" for any stage
        $this->assertStringNotContainsString('0 kodu', $content);
        $this->assertStringNotContainsString('>0<', $content);
    }

    /** @test */
    public function asendiplaan_has_unit_chips_with_data_attributes()
    {
        $response = $this->get('/asendiplaan');
        $response->assertOk();
        $content = $response->getContent();

        // Unit chips must have onclick handlers or aria-labels
        $this->assertStringContainsString('mgOpenUnit', $content);
    }

    /** @test */
    public function asendiplaan_has_side_panel_html()
    {
        $response = $this->get('/asendiplaan');
        $response->assertOk();
        $content = $response->getContent();

        // Side panel must exist
        $this->assertStringContainsString('mg-unit-panel', $content);
    }

    /** @test */
    public function asendiplaan_has_official_pdf_link()
    {
        $response = $this->get('/asendiplaan');
        $response->assertOk();
        $content = $response->getContent();

        // PDF download link must exist
        $this->assertStringContainsString('asendiplaan.pdf', $content);
    }

    /** @test */
    public function asendiplaan_has_hash_navigation_js()
    {
        $response = $this->get('/asendiplaan');
        $response->assertOk();
        $content = $response->getContent();

        // Hash-based init code must be present
        $this->assertStringContainsString('window.location.hash', $content);
    }

    /** @test */
    public function ru_asendiplaan_also_shows_units()
    {
        $this->get('/ru/asendiplaan')->assertOk();
    }

    /** @test */
    public function en_asendiplaan_also_shows_units()
    {
        $this->get('/en/asendiplaan')->assertOk();
    }
}
