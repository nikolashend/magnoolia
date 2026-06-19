<?php

namespace Tests\Feature;

use App\Models\MagnooliaUnit;
use App\Models\User;
use App\Services\Magnoolia\MagnooliaPublicationService;
use App\Services\Magnoolia\MagnooliaPublicDataRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class MagnooliaPhase331BulkActionsTest extends TestCase
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

    public function test_bulk_status_change_updates_selected_draft_and_audits(): void
    {
        $admin = $this->admin();
        $this->artisan('magnoolia:seed-units')->assertSuccessful();

        $this->actingAs($admin)->post('/admin/magnoolia/units/bulk', [
            'units' => ['tee-3-1', 'tee-3-3'],
            'bulk_action' => 'status_reserved',
        ])->assertRedirect();

        $this->assertSame('reserved', MagnooliaUnit::where('unit_key', 'tee-3-1')->value('status'));
        $this->assertSame('reserved', MagnooliaUnit::where('unit_key', 'tee-3-3')->value('status'));
        // untouched
        $this->assertSame('available', MagnooliaUnit::where('unit_key', 'tee-5-2')->value('status'));
        $this->assertDatabaseHas('magnoolia_audit_logs', ['action' => 'unit_updated', 'entity_id' => 'tee-3-1', 'reason' => 'Bulk: status_reserved']);
    }

    public function test_bulk_price_visibility_is_draft_only_until_publish(): void
    {
        $admin = $this->admin();
        $this->artisan('magnoolia:seed-units')->assertSuccessful();
        app(MagnooliaPublicationService::class)->publish($admin->id, 'v1');

        $this->actingAs($admin)->post('/admin/magnoolia/units/bulk', [
            'units' => ['tee-1-1'],
            'bulk_action' => 'price_public',
        ])->assertRedirect();

        $this->assertTrue((bool) MagnooliaUnit::where('unit_key', 'tee-1-1')->value('price_public'), 'draft updated');
        // public snapshot still hides the price (not republished)
        Cache::forget('magnoolia.public.payload');
        $pub = collect(app(MagnooliaPublicDataRepository::class)->getPublicPayload()['units'])->firstWhere('unit_key', 'tee-1-1');
        $this->assertFalse((bool) $pub['price_public'], 'public unchanged until publish');
    }

    public function test_bulk_requires_units_and_valid_action(): void
    {
        $admin = $this->admin();
        $this->artisan('magnoolia:seed-units')->assertSuccessful();
        $this->actingAs($admin)->post('/admin/magnoolia/units/bulk', ['bulk_action' => 'status_sold'])->assertSessionHasErrors('units');
        $this->actingAs($admin)->post('/admin/magnoolia/units/bulk', ['units' => ['tee-1-1'], 'bulk_action' => 'delete'])->assertSessionHasErrors('bulk_action');
    }
}
