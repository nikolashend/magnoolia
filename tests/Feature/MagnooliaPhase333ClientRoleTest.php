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

    /**
     * Phase 36 supersedes part of this rule.
     *
     * Phase 33.3 denied the client the whole Filament panel. That could not stand:
     * the Filament login is the only login there is, so the ban meant the role
     * could not sign in at all and every screen built for it was unreachable. The
     * panel root is now open — it lands on a dashboard whose only content is a link
     * into the Magnoolia control centre — and the Translation Manager was opened
     * deliberately (Phase 36, stage 1).
     *
     * What still must hold: the audit log and the language settings stay with the
     * system admin, and the template's own resources stay hidden.
     */
    public function test_client_role_cannot_reach_advanced_screens(): void
    {
        $client = $this->user('magnoolia_client_admin');

        $this->actingAs($client)->get('/admin/magnoolia/audit')->assertForbidden();
        $this->actingAs($client)->get('/admin/language-settings')->assertForbidden();

        $this->actingAs($client);
        $this->assertFalse(\App\Filament\Resources\BlogPostResource::canViewAny());
        $this->assertFalse(\App\Filament\Resources\ApartmentResource::canViewAny());
    }

    public function test_client_role_can_sign_in_and_reach_the_texts_editor(): void
    {
        $client = $this->user('magnoolia_client_admin');

        $this->assertTrue($client->canAccessPanel(\Filament\Facades\Filament::getPanel('admin')),
            'Blocking the panel blocks the only login form, and with it the whole control centre.');
        $this->actingAs($client)->get('/admin/translation-manager')->assertOk();
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
