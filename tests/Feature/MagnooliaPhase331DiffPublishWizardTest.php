<?php

namespace Tests\Feature;

use App\Models\MagnooliaUnit;
use App\Models\User;
use App\Services\Magnoolia\MagnooliaDiffService;
use App\Services\Magnoolia\MagnooliaPublicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MagnooliaPhase331DiffPublishWizardTest extends TestCase
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

    public function test_first_publish_flag_when_no_active_publication(): void
    {
        $this->artisan('magnoolia:seed-units')->assertSuccessful();
        $diff = app(MagnooliaDiffService::class)->diff();
        $this->assertTrue($diff['first_publish']);
    }

    public function test_no_changes_after_publish(): void
    {
        $admin = $this->admin();
        $this->artisan('magnoolia:seed-units')->assertSuccessful();
        app(MagnooliaPublicationService::class)->publish($admin->id, 'v1');

        $diff = app(MagnooliaDiffService::class)->diff();
        $this->assertFalse($diff['first_publish']);
        $this->assertFalse($diff['has_changes'], 'draft matches live right after publish');
    }

    public function test_diff_detects_a_status_change(): void
    {
        $admin = $this->admin();
        $this->artisan('magnoolia:seed-units')->assertSuccessful();
        app(MagnooliaPublicationService::class)->publish($admin->id, 'v1');

        MagnooliaUnit::where('unit_key', 'tee-3-1')->update(['status' => 'reserved']);

        $diff = app(MagnooliaDiffService::class)->diff();
        $this->assertTrue($diff['has_changes']);
        $unit = collect($diff['units'])->firstWhere('unit_key', 'tee-3-1');
        $this->assertNotNull($unit);
        $this->assertNotEmpty(array_filter($unit['rows'], fn ($r) => $r['label'] === 'Status'));
    }

    public function test_changes_page_reachable(): void
    {
        $this->artisan('magnoolia:seed-units')->assertSuccessful();
        $this->actingAs($this->admin())->get('/admin/magnoolia/changes')->assertOk()->assertSee('Changes since last publish');
    }

    public function test_publish_requires_confirmation_checkbox(): void
    {
        $admin = $this->admin();
        $this->artisan('magnoolia:seed-units')->assertSuccessful();

        // Without confirm_publish → rejected.
        $this->actingAs($admin)->post('/admin/magnoolia/publish', [
            'publication_note' => 'try',
        ])->assertSessionHasErrors('confirm_publish');
        $this->assertSame(0, \App\Models\MagnooliaPublication::count());

        // With confirmation → publishes.
        $this->actingAs($admin)->post('/admin/magnoolia/publish', [
            'publication_note' => 'go live',
            'confirm_publish' => '1',
        ])->assertRedirect();
        $this->assertSame(1, \App\Models\MagnooliaPublication::where('status', 'active')->count());
    }
}
