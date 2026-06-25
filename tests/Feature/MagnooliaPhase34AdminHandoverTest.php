<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Phase 34 — admin client-handover gate. The client role can do all daily work +
 * publishing and clearly find where to click; it cannot reach advanced/system or
 * Filament sections. No developer-only messages reach the client.
 */
class MagnooliaPhase34AdminHandoverTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $role): User
    {
        return User::create([
            'name' => ucfirst($role), 'email' => $role . '34@magnoolia.ee',
            'password' => bcrypt('secret123'), 'role' => $role, 'email_verified_at' => now(),
        ]);
    }

    private function seedData(): void
    {
        $this->artisan('magnoolia:seed-units')->assertSuccessful();
        $this->artisan('magnoolia:seed-content', ['--force' => true])->assertSuccessful();
    }

    public function test_client_can_reach_all_daily_routes(): void
    {
        $this->seedData();
        $client = $this->user('magnoolia_client_admin');
        foreach ([
            '/admin/magnoolia', '/admin/magnoolia/site-map', '/admin/magnoolia/units',
            '/admin/magnoolia/content', '/admin/magnoolia/media', '/admin/magnoolia/campaign',
            '/admin/magnoolia/leads', '/admin/magnoolia/changes', '/admin/magnoolia/preview',
            '/admin/magnoolia/validate', '/admin/magnoolia/publish', '/admin/magnoolia/publications',
            '/admin/magnoolia/help',
        ] as $url) {
            $this->actingAs($client)->get($url)->assertOk();
        }
    }

    public function test_client_blocked_from_advanced_and_filament(): void
    {
        $client = $this->user('magnoolia_client_admin');
        $this->actingAs($client)->get('/admin/magnoolia/audit')->assertForbidden();
        $this->actingAs($client)->get('/admin')->assertForbidden();
        $this->actingAs($client)->get('/admin/translation-manager')->assertForbidden();
        $this->actingAs($client)->get('/admin/language-settings')->assertForbidden();
    }

    public function test_full_admin_keeps_advanced_access(): void
    {
        $admin = $this->user('magnoolia_admin');
        $this->actingAs($admin)->get('/admin/magnoolia/audit')->assertOk();
        $this->actingAs($admin)->get('/admin/translation-manager')->assertOk();
    }

    public function test_dashboard_and_page_map_guide_the_client(): void
    {
        $this->seedData();
        $admin = $this->user('magnoolia_admin');

        $dash = $this->actingAs($admin)->get('/admin/magnoolia');
        $dash->assertOk()->assertSee('Mida soovid muuta?', false);
        $dash->assertDontSee('php artisan', false);

        $map = $this->actingAs($admin)->get('/admin/magnoolia/site-map');
        $map->assertOk()->assertSee('Veebilehe kaart', false);
    }

    public function test_admin_pages_have_no_laravel_demo_or_artisan_text(): void
    {
        $this->seedData();
        $admin = $this->user('magnoolia_admin');
        foreach (['/admin/magnoolia/content', '/admin/magnoolia/media', '/admin/magnoolia/units'] as $url) {
            $this->actingAs($admin)->get($url)->assertOk()->assertDontSee('php artisan', false);
        }
    }
}
