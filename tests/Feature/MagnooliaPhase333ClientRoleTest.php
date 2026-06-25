<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Phase 33.3 — client role hardening. `magnoolia_client_admin` does daily work +
 * publishing in the control center but cannot reach the Filament panel or the
 * advanced (Translations / Languages / Navigation / Audit) sections.
 */
class MagnooliaPhase333ClientRoleTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $role): User
    {
        return User::create([
            'name' => ucfirst($role), 'email' => $role . '@magnoolia.ee',
            'password' => bcrypt('secret123'), 'role' => $role, 'email_verified_at' => now(),
        ]);
    }

    private function seedUnits(): void
    {
        $this->artisan('magnoolia:seed-units')->assertSuccessful();
    }

    public function test_client_role_can_use_daily_control_center(): void
    {
        $this->seedUnits();
        $client = $this->user('magnoolia_client_admin');

        foreach ([
            '/admin/magnoolia',
            '/admin/magnoolia/site-map',
            '/admin/magnoolia/units',
            '/admin/magnoolia/content',
            '/admin/magnoolia/media',
            '/admin/magnoolia/leads',
            '/admin/magnoolia/campaign',
            '/admin/magnoolia/preview',
            '/admin/magnoolia/validate',
            '/admin/magnoolia/publish',
            '/admin/magnoolia/publications',
            '/admin/magnoolia/help',
        ] as $url) {
            $this->actingAs($client)->get($url)->assertOk();
        }
    }

    public function test_client_role_cannot_reach_advanced_or_filament(): void
    {
        $client = $this->user('magnoolia_client_admin');

        // Advanced control-center section (audit) — system admin only.
        $this->actingAs($client)->get('/admin/magnoolia/audit')->assertForbidden();

        // Whole Filament panel (root + advanced pages) — denied for client.
        $this->actingAs($client)->get('/admin')->assertForbidden();
        $this->actingAs($client)->get('/admin/translation-manager')->assertForbidden();
        $this->actingAs($client)->get('/admin/language-settings')->assertForbidden();
    }

    public function test_full_admin_retains_advanced_access(): void
    {
        $admin = $this->user('magnoolia_admin');
        $this->actingAs($admin)->get('/admin/magnoolia/audit')->assertOk();
        $this->actingAs($admin)->get('/admin/translation-manager')->assertOk();
        $this->actingAs($admin)->get('/admin/language-settings')->assertOk();
    }

    public function test_nav_hides_advanced_from_client(): void
    {
        $client = $this->user('magnoolia_client_admin');
        $res = $this->actingAs($client)->get('/admin/magnoolia');
        $res->assertOk();
        $res->assertDontSee('Advanced — ADME only', false);
        $res->assertDontSee('/admin/translation-manager', false);
    }
}
