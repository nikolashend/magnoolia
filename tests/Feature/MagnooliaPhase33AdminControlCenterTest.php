<?php

namespace Tests\Feature;

use App\Models\MagnooliaPublication;
use App\Models\MagnooliaSetting;
use App\Models\MagnooliaUnit;
use App\Models\User;
use App\Services\Magnoolia\MagnooliaPublicationService;
use App\Services\Magnoolia\MagnooliaPublicDataRepository;
use App\Services\Magnoolia\MagnooliaValidationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * Phase 33 — Admin Control Center: data source-of-truth, seeding, draft/publish
 * separation, validation, publish/rollback, audit, CSV, and admin security.
 */
class MagnooliaPhase33AdminControlCenterTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::create([
            'name' => 'Admin', 'email' => 'a@magnoolia.ee',
            'password' => bcrypt('secret123'), 'role' => 'magnoolia_admin',
            'email_verified_at' => now(),
        ]);
    }

    private function editor(): User
    {
        return User::create([
            'name' => 'Editor', 'email' => 'e@magnoolia.ee',
            'password' => bcrypt('secret123'), 'role' => 'magnoolia_editor',
            'email_verified_at' => now(),
        ]);
    }

    private function plainUser(): User
    {
        return User::create([
            'name' => 'User', 'email' => 'u@magnoolia.ee',
            'password' => bcrypt('secret123'), 'role' => 'user',
            'email_verified_at' => now(),
        ]);
    }

    private function seedUnits(): void
    {
        $this->artisan('magnoolia:seed-units')->assertSuccessful();
    }

    private function repo(): MagnooliaPublicDataRepository
    {
        Cache::forget('magnoolia.public.payload');
        return app(MagnooliaPublicDataRepository::class);
    }

    private function publish(User $admin, string $note = 'test'): array
    {
        return app(MagnooliaPublicationService::class)->publish($admin->id, $note);
    }

    // ---------------------------------------------------------------- seeding

    public function test_seed_creates_exactly_19_homes_with_correct_distribution(): void
    {
        $this->seedUnits();
        $this->assertSame(19, MagnooliaUnit::count());

        $byStatus = MagnooliaUnit::query()->selectRaw('status, count(*) c')->groupBy('status')->pluck('c', 'status')->all();
        $this->assertSame(14, $byStatus['available'] ?? 0);
        $this->assertSame(4, $byStatus['reserved'] ?? 0);
        $this->assertSame(1, $byStatus['sold'] ?? 0);
    }

    public function test_seed_sets_known_yards_and_required_reserved_sold(): void
    {
        $this->seedUnits();
        $this->assertEqualsWithDelta(756.4, (float) MagnooliaUnit::where('unit_key', 'tee-3-4')->value('private_yard_area'), 0.01);
        $this->assertEqualsWithDelta(513.3, (float) MagnooliaUnit::where('unit_key', 'tee-1-2')->value('private_yard_area'), 0.01);

        foreach (['tee-1-3', 'tee-3-2', 'tee-7-2', 'tee-9-2'] as $r) {
            $this->assertSame('reserved', MagnooliaUnit::where('unit_key', $r)->value('status'), "$r reserved");
        }
        $this->assertSame('sold', MagnooliaUnit::where('unit_key', 'tee-5-1')->value('status'));
    }

    public function test_seed_is_idempotent_and_hides_prices(): void
    {
        $this->seedUnits();
        $this->seedUnits(); // second run must not duplicate
        $this->assertSame(19, MagnooliaUnit::count());
        $this->assertSame(0, MagnooliaUnit::where('price_public', true)->count(), 'no public prices by default');
    }

    // ------------------------------------------------ draft / publish source

    public function test_no_active_publication_falls_back_to_config_19_homes(): void
    {
        $this->seedUnits(); // draft DB has 19, but nothing published yet
        $payload = $this->repo()->getPublicPayload();
        $this->assertCount(19, $payload['units'], 'config fallback always shows 19');
    }

    public function test_editing_draft_does_not_change_public_until_publish(): void
    {
        $this->seedUnits();
        $admin = $this->admin();
        $this->publish($admin);

        $publishedStatus = collect($this->repo()->getPublicPayload()['units'])->firstWhere('unit_key', 'tee-3-1')['status'];

        // Edit the draft after publishing.
        MagnooliaUnit::where('unit_key', 'tee-3-1')->update(['status' => 'sold', 'lock_version' => 99]);

        $afterEdit = collect($this->repo()->getPublicPayload()['units'])->firstWhere('unit_key', 'tee-3-1')['status'];
        $this->assertSame($publishedStatus, $afterEdit, 'draft edit must NOT leak to public before publish');

        // Re-publish → now public reflects the draft.
        $this->publish($admin, 'after edit');
        $afterPublish = collect($this->repo()->getPublicPayload()['units'])->firstWhere('unit_key', 'tee-3-1')['status'];
        $this->assertSame('sold', $afterPublish, 'publish must propagate draft to public');
    }

    public function test_public_prefers_active_publication_over_config(): void
    {
        $this->seedUnits();
        $admin = $this->admin();
        // Make a draft status differ from the canonical config value, then publish.
        MagnooliaUnit::where('unit_key', 'tee-11-3')->update(['status' => 'reserved']);
        $this->publish($admin);

        $payload = $this->repo()->getPublicPayload();
        $this->assertArrayHasKey('version', $payload['meta'], 'payload comes from a publication');
        $status = collect($payload['units'])->firstWhere('unit_key', 'tee-11-3')['status'];
        $this->assertSame('reserved', $status, 'active publication overrides config');
    }

    public function test_public_payload_never_exposes_price_cents_when_hidden(): void
    {
        $this->seedUnits();
        $admin = $this->admin();
        $this->publish($admin);
        $json = json_encode($this->repo()->getPublicPayload());
        foreach ($this->repo()->getPublicPayload()['units'] as $u) {
            $this->assertNull($u['price_cents'], "{$u['unit_key']} must not expose price_cents while hidden");
            $this->assertFalse((bool) $u['price_public']);
        }
    }

    // ---------------------------------------------------------- validation

    public function test_validation_blocks_when_count_not_19(): void
    {
        $this->seedUnits();
        MagnooliaUnit::where('unit_key', 'tee-11-3')->delete();
        $v = app(MagnooliaValidationService::class)->validateDraft();
        $this->assertNotEmpty($v['blockers'], 'count != 19 must block');
    }

    public function test_validation_blocks_invalid_status_and_negative_area(): void
    {
        $this->seedUnits();
        MagnooliaUnit::where('unit_key', 'tee-1-1')->update(['status' => 'banana', 'net_area' => -5]);
        $v = app(MagnooliaValidationService::class)->validateDraft();
        $this->assertNotEmpty($v['blockers']);
    }

    public function test_seeded_draft_validates_clean(): void
    {
        $this->seedUnits();
        $v = app(MagnooliaValidationService::class)->validateDraft();
        $this->assertEmpty($v['blockers'], 'seeded canonical draft has no blockers');
    }

    // ----------------------------------------------------- publish/rollback

    public function test_publish_creates_versioned_snapshot_and_single_active(): void
    {
        $this->seedUnits();
        $admin = $this->admin();
        $this->publish($admin, 'v1');
        MagnooliaUnit::where('unit_key', 'tee-7-1')->update(['status' => 'reserved']);
        $this->publish($admin, 'v2');

        $this->assertSame(2, MagnooliaPublication::count());
        $this->assertSame(1, MagnooliaPublication::where('status', 'active')->count(), 'only one active publication');
        $this->assertSame(2, (int) MagnooliaPublication::where('status', 'active')->value('version'));
    }

    public function test_rollback_restores_previous_snapshot_and_audits(): void
    {
        $this->seedUnits();
        $admin = $this->admin();
        $r1 = $this->publish($admin, 'v1');
        $v1Id = $r1['publication']->id;

        MagnooliaUnit::where('unit_key', 'tee-7-1')->update(['status' => 'sold']);
        $this->publish($admin, 'v2');
        $this->assertSame('sold', collect($this->repo()->getPublicPayload()['units'])->firstWhere('unit_key', 'tee-7-1')['status']);

        $rb = app(MagnooliaPublicationService::class)->rollback($admin->id, $v1Id, 'mistake');
        $this->assertTrue($rb['ok']);
        $this->assertSame('available', collect($this->repo()->getPublicPayload()['units'])->firstWhere('unit_key', 'tee-7-1')['status'], 'rollback restored v1 status');

        $this->assertDatabaseHas('magnoolia_audit_logs', ['action' => 'publication_created']);
        $this->assertDatabaseHas('magnoolia_audit_logs', ['action' => 'publication_rolled_back']);
    }

    // ------------------------------------------------------------- security

    public function test_guest_cannot_access_admin(): void
    {
        $this->get('/admin/magnoolia')->assertRedirect();
    }

    public function test_plain_user_cannot_access_admin(): void
    {
        $this->seedUnits();
        $this->actingAs($this->plainUser())->get('/admin/magnoolia')->assertForbidden();
    }

    public function test_admin_can_access_dashboard_and_units(): void
    {
        $this->seedUnits();
        $admin = $this->admin();
        $this->actingAs($admin)->get('/admin/magnoolia')->assertOk()->assertSee('19');
        $this->actingAs($admin)->get('/admin/magnoolia/units')->assertOk()->assertSee('Magnoolia tee');
    }

    public function test_quick_status_edit_changes_draft_and_audits_not_public(): void
    {
        $this->seedUnits();
        $admin = $this->admin();
        $this->publish($admin);
        $publicBefore = collect($this->repo()->getPublicPayload()['units'])->firstWhere('unit_key', 'tee-9-3')['status'];

        $this->actingAs($admin)->patch('/admin/magnoolia/units/tee-9-3/quick', [
            'field' => 'status', 'status' => 'reserved',
        ])->assertRedirect();

        $this->assertSame('reserved', MagnooliaUnit::where('unit_key', 'tee-9-3')->value('status'), 'draft updated');
        $publicAfter = collect($this->repo()->getPublicPayload()['units'])->firstWhere('unit_key', 'tee-9-3')['status'];
        $this->assertSame($publicBefore, $publicAfter, 'quick edit is draft-only until publish');
        $this->assertDatabaseHas('magnoolia_audit_logs', ['action' => 'unit_updated', 'entity_id' => 'tee-9-3']);
    }

    // ------------------------------------------------------------------ CSV

    public function test_csv_export_returns_19_homes(): void
    {
        $this->seedUnits();
        $admin = $this->admin();
        $res = $this->actingAs($admin)->get('/admin/magnoolia/export/csv')->assertOk();
        $body = $res->getContent() ?: (method_exists($res, 'streamedContent') ? $res->streamedContent() : '');
        // header + 19 rows
        $lines = array_values(array_filter(explode("\n", trim($body))));
        $this->assertSame(20, count($lines), 'CSV has header + 19 rows');
    }
}
