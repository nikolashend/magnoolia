<?php

namespace Tests\Feature;

use App\Models\MagnooliaAuditLog;
use App\Models\MagnooliaUnit;
use App\Models\User;
use App\Services\Magnoolia\MagnooliaAuditService;
use App\Services\Magnoolia\MagnooliaPublicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesMagnooliaTestUnits;

class MagnooliaPhase24AuditLogsTest extends TestCase
{
    use RefreshDatabase, CreatesMagnooliaTestUnits;

    protected User $admin;
    protected User $editor;
    protected MagnooliaAuditService $auditService;
    protected MagnooliaPublicationService $pubService;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->verified()->create(['role' => 'magnoolia_admin']);
        $this->editor = User::factory()->verified()->create(['role' => 'magnoolia_editor']);
        $this->auditService = app(MagnooliaAuditService::class);
        $this->pubService = app(MagnooliaPublicationService::class);
    }

    private function logAction(string $action = 'unit_updated', ?int $userId = null): MagnooliaAuditLog
    {
        return MagnooliaAuditLog::create([
            'admin_user_id' => $userId ?? $this->admin->id,
            'action' => $action,
            'entity_type' => 'MagnooliaUnit',
            'entity_id' => 1,
            'before_json' => json_encode(['price_cents' => 500000]),
            'after_json' => json_encode(['price_cents' => 550000]),
            'reason' => 'Test action',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'TestBrowser/1.0',
        ]);
    }

    /** @test */
    public function audit_logs_can_be_created()
    {
        $log = $this->logAction();
        $this->assertDatabaseHas('magnoolia_audit_logs', [
            'id' => $log->id,
            'action' => 'unit_updated',
            'admin_user_id' => $this->admin->id,
        ]);
    }

    /** @test */
    public function audit_log_records_before_after_state()
    {
        $log = $this->logAction('price_changed');
        $this->assertDatabaseHas('magnoolia_audit_logs', [
            'id' => $log->id,
            'action' => 'price_changed',
            'before_json' => json_encode(['price_cents' => 500000]),
            'after_json' => json_encode(['price_cents' => 550000]),
        ]);
    }

    /** @test */
    public function publication_creates_audit_entry()
    {
        $this->create19TestUnits();
        $logsBefore = MagnooliaAuditLog::where('action', 'publication_created')->count();
        $result = $this->pubService->publish($this->admin->id, 'Audit test publication');
        $this->assertTrue($result['ok'], 'Publish failed');
        $logsAfter = MagnooliaAuditLog::where('action', 'publication_created')->count();
        $this->assertGreaterThan($logsBefore, $logsAfter);
    }

    /** @test */
    public function rollback_creates_audit_entry()
    {
        $this->create19TestUnits();
        $r1 = $this->pubService->publish($this->admin->id, 'v1');
        $this->assertTrue($r1['ok']);
        $pub = $r1['publication'];
        $r2 = $this->pubService->rollback($this->admin->id, $pub->id, 'rollback reason');
        $this->assertTrue($r2['ok']);
        $rollbackLog = MagnooliaAuditLog::where('action', 'publication_rolled_back')->latest()->first();
        $this->assertNotNull($rollbackLog);
    }

    /** @test */
    public function audit_log_shows_user_and_action()
    {
        $this->logAction('price_changed');
        $response = $this->actingAs($this->admin)->get('/admin/magnoolia/audit');
        $response->assertSuccessful();
        $this->assertStringContainsString($this->admin->name, $response->getContent());
    }

    /** @test */
    public function audit_log_tracks_ip_address()
    {
        $log = MagnooliaAuditLog::create([
            'admin_user_id' => $this->admin->id,
            'action' => 'ip_test',
            'entity_type' => 'Test',
            'entity_id' => 1,
            'before_json' => json_encode([]),
            'after_json' => json_encode([]),
            'reason' => 'IP tracking',
            'ip_address' => '203.0.113.42',
            'user_agent' => 'Test',
        ]);
        $this->assertDatabaseHas('magnoolia_audit_logs', ['id' => $log->id, 'ip_address' => '203.0.113.42']);
    }

    /** @test */
    public function editor_cannot_view_audit_logs()
    {
        $response = $this->actingAs($this->editor)->get('/admin/magnoolia/audit');
        $response->assertForbidden();
    }

    /** @test */
    public function admin_can_view_audit_logs()
    {
        $this->logAction();
        $response = $this->actingAs($this->admin)->get('/admin/magnoolia/audit');
        $response->assertSuccessful();
    }

    /** @test */
    public function audit_logs_support_filtering()
    {
        $this->logAction('price_changed');
        $this->logAction('unit_created');
        $this->logAction('price_changed');
        $this->assertEquals(2, MagnooliaAuditLog::where('action', 'price_changed')->count());
        $this->assertEquals(1, MagnooliaAuditLog::where('action', 'unit_created')->count());
    }

    /** @test */
    public function audit_logs_have_no_delete_endpoint()
    {
        $log = $this->logAction();
        $response = $this->actingAs($this->admin)->delete("/admin/magnoolia/audit/{$log->id}");
        $response->assertNotFound();
    }

    /** @test */
    public function audit_service_logs_actions()
    {
        $countBefore = MagnooliaAuditLog::count();
        $this->auditService->log('price_changed', $this->admin->id, 'MagnooliaUnit', '5', ['price_cents' => 500000], ['price_cents' => 600000], 'Market adjustment');
        $this->assertGreaterThan($countBefore, MagnooliaAuditLog::count());
    }
}
