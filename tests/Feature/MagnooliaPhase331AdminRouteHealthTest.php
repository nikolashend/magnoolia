<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Phase 33.1 — admin route health.
 * Guards the regression where /admin/nav-items/{create,edit} threw a 500
 * ("Filament\Forms\Components\Section not found") under Filament v5, and that
 * the Magnoolia admin pages stay reachable for an admin user.
 */
class MagnooliaPhase331AdminRouteHealthTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::create([
            'name' => 'Admin', 'email' => 'admin@magnoolia.ee',
            'password' => bcrypt('secret123'), 'role' => 'magnoolia_admin',
            'email_verified_at' => now(),
        ]);
    }

    public function test_nav_items_create_does_not_500(): void
    {
        $res = $this->actingAs($this->admin())->get('/admin/nav-items/create');
        // Must not be a server error (was 500 before the Section namespace fix).
        $this->assertLessThan(500, $res->getStatusCode(), 'nav-items create must not 500');
    }

    public function test_nav_items_index_reachable(): void
    {
        $res = $this->actingAs($this->admin())->get('/admin/nav-items');
        $this->assertLessThan(500, $res->getStatusCode());
    }

    public function test_filament_dashboard_reachable_for_admin(): void
    {
        $res = $this->actingAs($this->admin())->get('/admin');
        $this->assertLessThan(500, $res->getStatusCode());
    }

    public function test_magnoolia_admin_dashboard_ok(): void
    {
        $this->artisan('magnoolia:seed-units')->assertSuccessful();
        $this->actingAs($this->admin())->get('/admin/magnoolia')->assertOk();
    }

    public function test_legacy_resources_hidden_from_navigation(): void
    {
        // shouldRegisterNavigation() must be false for the legacy template resources.
        foreach ([
            \App\Filament\Resources\Apartments\ApartmentResource::class,
            \App\Filament\Resources\BlogPostResource::class,
            \App\Filament\Resources\ServiceResource::class,
            \App\Filament\Resources\GalleryImageResource::class,
        ] as $resource) {
            $this->assertFalse($resource::shouldRegisterNavigation(), "$resource must be hidden from client nav");
        }
    }
}
