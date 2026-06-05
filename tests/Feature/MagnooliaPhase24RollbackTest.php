<?php

namespace Tests\Feature;

use App\Models\MagnooliaAuditLog;
use App\Models\MagnooliaPublication;
use App\Models\MagnooliaUnit;
use App\Models\User;
use App\Services\Magnoolia\MagnooliaPublicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesMagnooliaTestUnits;

class MagnooliaPhase24RollbackTest extends TestCase
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

        // Create canonical 19 units and publish v1
        $this->create19TestUnits();
        $this->service->publish($this->admin->id, 'v1 initial');

        // Change price and publish v2
        MagnooliaUnit::where('unit_key', 'B1-S1')->update(['price_cents' => 200000]);
        $this->service->publish($this->admin->id, 'v2 price increase');

        // Change price and publish v3 (current active)
        MagnooliaUnit::where('unit_key', 'B1-S1')->update(['price_cents' => 250000]);
        $this->service->publish($this->admin->id, 'v3 price increase');
    }

    private function getVersion(int $v): ?MagnooliaPublication
    {
        return MagnooliaPublication::where('version', $v)->first();
    }

    /**
     * @test
     * Rollback creates a new publication version
     */
    public function rollback_creates_new_publication_version()
    {
        $v2 = $this->getVersion(2);
        $countBefore = MagnooliaPublication::count();

        $result = $this->service->rollback($this->admin->id, $v2->id, 'Found pricing error');

        $this->assertTrue($result['ok']);
        $this->assertGreaterThan($countBefore, MagnooliaPublication::count());
    }

    /**
     * @test
     * Rollback new version is active, previous v3 becomes inactive
     */
    public function rollback_deactivates_previous_version()
    {
        $v2 = $this->getVersion(2);
        $v3 = $this->getVersion(3);
        $this->assertEquals('active', $v3->status);

        $result = $this->service->rollback($this->admin->id, $v2->id, 'Rollback test');
        $this->assertTrue($result['ok']);

        $v3->refresh();
        $this->assertEquals('inactive', $v3->status);

        // New rollback publication is active
        $this->assertEquals('active', $result['publication']->status);
    }

    /**
     * @test
     * Rollback does not mutate original source publication
     */
    public function rollback_does_not_mutate_original()
    {
        $v2 = $this->getVersion(2);
        $originalPayload = $v2->public_payload_json;
        $originalNote = $v2->publication_note;

        $this->service->rollback($this->admin->id, $v2->id, 'Immutability test');

        $v2->refresh();
        $this->assertEquals($originalPayload, $v2->public_payload_json);
        $this->assertEquals($originalNote, $v2->publication_note);
    }

    /**
     * @test
     * Rollback tracks source via rolled_back_from_id
     */
    public function rollback_audit_trail_shows_source()
    {
        $v2 = $this->getVersion(2);

        $result = $this->service->rollback($this->admin->id, $v2->id, 'Audit test');
        $this->assertTrue($result['ok']);

        $rollbackPub = $result['publication'];
        $this->assertEquals($v2->id, $rollbackPub->rolled_back_from_id);
    }

    /**
     * @test
     * Rollback reason stored in publication note
     */
    public function rollback_reason_recorded()
    {
        $v2 = $this->getVersion(2);

        $result = $this->service->rollback($this->admin->id, $v2->id, 'Found pricing error in v3');
        $this->assertTrue($result['ok']);

        $this->assertStringContainsString('Found pricing error in v3', $result['publication']->publication_note);
    }

    /**
     * @test
     * Only admin (not editor) can initiate rollback via HTTP route
     */
    public function non_admin_cannot_rollback_via_http()
    {
        $v2 = $this->getVersion(2);

        $response = $this->actingAs($this->editor)->post("/admin/magnoolia/publications/{$v2->id}/rollback", [
            'reason' => 'Test rollback'
        ]);

        $response->assertForbidden();
    }

    /**
     * @test
     * Admin can access rollback form
     */
    public function admin_can_access_rollback_form()
    {
        $v2 = $this->getVersion(2);

        $response = $this->actingAs($this->admin)->get("/admin/magnoolia/publications/{$v2->id}/rollback");
        $response->assertSuccessful();
        $this->assertStringContainsString('rollback', strtolower($response->getContent()));
    }

    /**
     * @test
     * Rollback author is recorded
     */
    public function rollback_author_recorded()
    {
        $v2 = $this->getVersion(2);

        $result = $this->service->rollback($this->admin->id, $v2->id, 'Author test');
        $this->assertTrue($result['ok']);

        $this->assertEquals($this->admin->id, $result['publication']->published_by);
    }

    /**
     * @test
     * Rollback restores draft units to source snapshot prices
     */
    public function rollback_updates_draft_units()
    {
        $v1 = $this->getVersion(1);

        // v1 had price 150000 (B1-S1 = 150000*1 + 10000*1 = 160000), current has 250000
        $currentUnit = MagnooliaUnit::where('unit_key', 'B1-S1')->first();
        $this->assertEquals(250000, $currentUnit->price_cents);

        $result = $this->service->rollback($this->admin->id, $v1->id, 'Restore v1 prices');
        $this->assertTrue($result['ok']);

        // Draft unit should be restored to v1 price
        $currentUnit->refresh();
        $this->assertEquals(160000, $currentUnit->price_cents);
    }

    /**
     * @test
     * All original versions remain after rollback (append-only)
     */
    public function rollback_chain_is_append_only()
    {
        $v1 = $this->getVersion(1);

        $result = $this->service->rollback($this->admin->id, $v1->id, 'Rollback to v1');
        $this->assertTrue($result['ok']);

        // All 4 versions must exist (v1, v2, v3, v4-rollback)
        $this->assertEquals(4, MagnooliaPublication::count());

        // Original versions untouched
        $this->assertNotNull($this->getVersion(1));
        $this->assertNotNull($this->getVersion(2));
        $this->assertNotNull($this->getVersion(3));
    }

    /**
     * @test
     * Rollback creates audit log entry
     */
    public function rollback_creates_audit_log()
    {
        $v2 = $this->getVersion(2);

        $auditsBefore = MagnooliaAuditLog::where('action', 'publication_rolled_back')->count();
        $this->service->rollback($this->admin->id, $v2->id, 'Audit log test');
        $auditsAfter = MagnooliaAuditLog::where('action', 'publication_rolled_back')->count();

        $this->assertGreaterThan($auditsBefore, $auditsAfter);
    }
}
