<?php

namespace Tests\Feature;

use App\Models\MagnooliaSetting;
use App\Models\User;
use App\Services\Magnoolia\MagnooliaValidationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MagnooliaPhase331CampaignTest extends TestCase
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

    public function test_campaign_editor_reachable_and_saves_euros_as_cents(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->get('/admin/magnoolia/campaign')->assertOk()->assertSee('Discount amount');

        $this->actingAs($admin)->post('/admin/magnoolia/campaign', [
            'campaign_active' => '1',
            'campaign_discount_type' => 'fixed',
            'campaign_discount_eur' => '5000',
            'campaign_deadline' => now()->addMonth()->toDateString(),
            'campaign_note_et' => 'Soodustus',
            'campaign_cta_label' => 'Küsi pakkumist',
        ])->assertRedirect();

        $s = MagnooliaSetting::first();
        $this->assertSame(500000, (int) $s->campaign_discount_cents, '5000 € stored as 500000 cents');
        $this->assertSame('fixed', $s->campaign_discount_type);
    }

    public function test_text_only_campaign_stores_no_discount(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->post('/admin/magnoolia/campaign', [
            'campaign_active' => '1',
            'campaign_discount_type' => 'text',
            'campaign_discount_eur' => '999',
            'campaign_deadline' => now()->addMonth()->toDateString(),
            'campaign_note_et' => 'Kevadkampaania',
            'campaign_cta_label' => 'Vaata',
        ])->assertRedirect();

        $this->assertNull(MagnooliaSetting::first()->campaign_discount_cents, 'text-only ignores the € amount');
    }

    public function test_active_campaign_without_text_blocks_publish(): void
    {
        MagnooliaSetting::create([
            'campaign_active' => true,
            'campaign_discount_type' => 'text',
            'campaign_deadline' => now()->addMonth(),
            'campaign_note_et' => null,
        ]);
        $v = app(MagnooliaValidationService::class)->validateDraft();
        $this->assertNotEmpty(array_filter($v['blockers'], fn ($b) => str_contains($b, 'public text')));
    }

    public function test_text_campaign_does_not_require_discount(): void
    {
        MagnooliaSetting::create([
            'campaign_active' => true,
            'campaign_discount_type' => 'text',
            'campaign_deadline' => now()->addMonth(),
            'campaign_note_et' => 'Tekst',
            'campaign_cta_label' => 'CTA',
        ]);
        $v = app(MagnooliaValidationService::class)->validateDraft();
        $this->assertEmpty(array_filter($v['blockers'], fn ($b) => str_contains($b, 'discount')), 'text campaign needs no discount');
    }

    public function test_fixed_campaign_with_zero_discount_blocks(): void
    {
        MagnooliaSetting::create([
            'campaign_active' => true,
            'campaign_discount_type' => 'fixed',
            'campaign_discount_cents' => 0,
            'campaign_deadline' => now()->addMonth(),
            'campaign_note_et' => 'Tekst',
            'campaign_cta_label' => 'CTA',
        ]);
        $v = app(MagnooliaValidationService::class)->validateDraft();
        $this->assertNotEmpty(array_filter($v['blockers'], fn ($b) => str_contains($b, 'discount')));
    }
}
