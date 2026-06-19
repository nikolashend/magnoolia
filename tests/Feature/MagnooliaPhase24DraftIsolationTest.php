<?php

namespace Tests\Feature;

use App\Models\MagnooliaPublication;
use App\Models\MagnooliaUnit;
use App\Models\User;
use App\Services\Magnoolia\MagnooliaPublicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesMagnooliaTestUnits;

class MagnooliaPhase24DraftIsolationTest extends TestCase
{
    use RefreshDatabase, CreatesMagnooliaTestUnits;

    protected User $admin;
    protected User $editor;
    protected MagnooliaPublicationService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->verified()->create(['role' => 'magnoolia_admin']);
        $this->editor = User::factory()->verified()->create(['role' => 'magnoolia_editor']);
        $this->service = app(MagnooliaPublicationService::class);
        $this->create19TestUnits();
    }

    /** @test */
    public function draft_changes_do_not_leak_to_public_pages()
    {
        $r1 = $this->service->publish($this->admin->id, 'v1');
        $this->assertTrue($r1['ok']);
        MagnooliaUnit::where('unit_key', 'B1-S1')->update(['price_cents' => 999999, 'price_public' => true]);
        $response = $this->get('/kodud-ja-hinnad');
        $response->assertSuccessful();
        $this->assertStringNotContainsString('999999', $response->getContent());
    }

    /** @test */
    public function admin_can_preview_draft_before_publishing()
    {
        $response = $this->actingAs($this->editor)->get('/admin/magnoolia/preview');
        $response->assertSuccessful();
        $this->assertStringContainsString('Magnoolia tee', $response->getContent());
    }

    /** @test */
    public function preview_page_has_noindex_meta()
    {
        $response = $this->actingAs($this->editor)->get('/admin/magnoolia/preview');
        $response->assertSuccessful();
        $this->assertStringContainsString('noindex', $response->getContent());
        $this->assertStringContainsString('EELVAADE', $response->getContent());
    }

    /** @test */
    public function draft_changes_persist_in_units_table()
    {
        $this->assertDatabaseHas('magnoolia_units', ['unit_key' => 'B1-S1']);
        MagnooliaUnit::where('unit_key', 'B1-S1')->update(['price_cents' => 650000]);
        $this->assertDatabaseHas('magnoolia_units', ['unit_key' => 'B1-S1', 'price_cents' => 650000]);
    }

    /** @test */
    public function editor_cannot_publish_draft()
    {
        $response = $this->actingAs($this->editor)->post('/admin/magnoolia/publish', ['notes' => 'Editor attempt']);
        $response->assertForbidden();
    }

    /** @test */
    public function admin_can_publish_via_http()
    {
        $before = MagnooliaPublication::count();
        $response = $this->actingAs($this->admin)->post('/admin/magnoolia/publish', ['publication_note' => 'Admin HTTP publish', 'confirm_warnings' => '1', 'confirm_publish' => '1']);
        $this->assertContains($response->getStatusCode(), [200, 302, 422]);
        $this->assertGreaterThan($before, MagnooliaPublication::count());
    }

    /** @test */
    public function published_data_available_on_all_locales()
    {
        $result = $this->service->publish($this->admin->id, 'All locales test');
        $this->assertTrue($result['ok']);
        $this->get('/kodud-ja-hinnad')->assertSuccessful();
        $this->get('/ru/kodud-ja-hinnad')->assertSuccessful();
        $this->get('/en/kodud-ja-hinnad')->assertSuccessful();
    }

    /** @test */
    public function critical_field_update_requires_reason()
    {
        $response = $this->actingAs($this->admin)->put('/admin/magnoolia/units/B1-S1', [
            'status' => 'sold', 'is_visible' => '1', 'price_cents' => 500000,
            'price_public' => '1', 'rooms' => 2, 'net_area' => 65.0,
            'terrace_area' => '', 'balcony_area' => '', 'storage_area' => '',
            'private_yard_area' => '', 'parking_spaces' => 1,
            'completion_key' => 'Q3-2026',
            'floorplan_floor_1' => 'assets/magnoolia/floorplans/b1/s1-floor1.jpg',
            'floorplan_floor_2' => 'assets/magnoolia/floorplans/b1/s1-floor2.jpg', 'asendiplaan_key' => '', 'featured' => '',
            'sort_order' => 1, 'internal_note' => '', 'change_reason' => '',
        ]);
        $response->assertRedirect();
        $response->assertSessionHasErrors(['change_reason']);
    }
}
