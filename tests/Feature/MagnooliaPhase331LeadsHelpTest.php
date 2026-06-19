<?php

namespace Tests\Feature;

use App\Models\MagnooliaLead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MagnooliaPhase331LeadsHelpTest extends TestCase
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

    private function lead(array $over = []): MagnooliaLead
    {
        return MagnooliaLead::create(array_merge([
            'name' => 'Mari Maasikas', 'email' => 'mari@example.com', 'phone' => '+372 5000 0000',
            'unit_address' => 'Magnoolia tee 3/2', 'locale' => 'et', 'source_component' => 'inquiry_drawer',
            'mail_status' => 'sent',
        ], $over));
    }

    public function test_leads_index_reachable_and_lists_inquiries(): void
    {
        $this->lead();
        $this->actingAs($this->admin())->get('/admin/magnoolia/leads')
            ->assertOk()->assertSee('Mari Maasikas')->assertSee('mari@example.com');
    }

    public function test_lead_status_update_is_audited(): void
    {
        $admin = $this->admin();
        $lead = $this->lead();
        $this->actingAs($admin)->patch("/admin/magnoolia/leads/{$lead->id}/status", ['lead_status' => 'contacted'])->assertRedirect();
        $this->assertSame('contacted', $lead->fresh()->lead_status);
        $this->assertDatabaseHas('magnoolia_audit_logs', ['action' => 'lead_status_changed', 'entity_id' => (string) $lead->id]);
    }

    public function test_leads_export_csv(): void
    {
        $this->lead();
        $res = $this->actingAs($this->admin())->get('/admin/magnoolia/leads/export')->assertOk();
        $body = $res->getContent() ?: '';
        $this->assertStringContainsString('mari@example.com', $body);
        $this->assertStringContainsString('lead_status', $body);
    }

    public function test_help_page_reachable(): void
    {
        $this->actingAs($this->admin())->get('/admin/magnoolia/help')
            ->assertOk()->assertSee('How the Magnoolia Control Center works');
    }
}
