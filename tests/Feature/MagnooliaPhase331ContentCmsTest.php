<?php

namespace Tests\Feature;

use App\Models\MagnooliaContentBlock;
use App\Models\User;
use App\Services\Magnoolia\MagnooliaPublicationService;
use App\Services\Magnoolia\MagnooliaValidationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class MagnooliaPhase331ContentCmsTest extends TestCase
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

    private function seedAll(): void
    {
        $this->artisan('magnoolia:seed-units')->assertSuccessful();
        $this->artisan('magnoolia:seed-content')->assertSuccessful();
    }

    public function test_seed_content_creates_blocks(): void
    {
        $this->artisan('magnoolia:seed-content')->assertSuccessful();
        $this->assertSame(6, MagnooliaContentBlock::count());
        $this->assertNotNull(MagnooliaContentBlock::where('key', 'page.kodudjahinnad.note')->value('et'));
    }

    public function test_content_index_reachable_for_admin(): void
    {
        $this->seedAll();
        $this->actingAs($this->admin())->get('/admin/magnoolia/content')->assertOk()->assertSee('Page Texts');
    }

    public function test_mg_text_falls_back_to_lang_when_no_override(): void
    {
        // No content blocks, no publication → mg_text returns the lang value.
        $this->assertSame(__('magnoolia.page.kodudjahinnad.note'), mg_text('page.kodudjahinnad.note'));
    }

    public function test_editing_content_is_draft_only_until_publish(): void
    {
        $admin = $this->admin();
        $this->seedAll();
        app(MagnooliaPublicationService::class)->publish($admin->id, 'v1'); // publish baseline (content = lang values)

        $block = MagnooliaContentBlock::where('key', 'page.kodudjahinnad.note')->first();
        $marker = 'ZZ-DRAFT-NOTICE-ZZ';

        $this->actingAs($admin)->patch('/admin/magnoolia/content/' . $block->id, [
            'et' => $marker, 'ru' => $block->ru, 'en' => $block->en, 'is_active' => '1',
        ])->assertRedirect();

        // Public still shows the published value, not the draft marker.
        $this->get('/kodud-ja-hinnad')->assertOk()->assertDontSee($marker);

        // Publish → public now shows the edited text.
        Cache::forget('magnoolia.public.payload');
        app(MagnooliaPublicationService::class)->publish($admin->id, 'v2 content edit');
        Cache::forget('magnoolia.public.payload');
        $this->get('/kodud-ja-hinnad')->assertOk()->assertSee($marker);

        $this->assertDatabaseHas('magnoolia_audit_logs', ['action' => 'content_updated']);
    }

    public function test_active_block_missing_et_blocks_publish(): void
    {
        $this->seedAll();
        MagnooliaContentBlock::where('key', 'page.kontakt.lead')->update(['et' => null, 'is_active' => true]);
        $v = app(MagnooliaValidationService::class)->validateDraft();
        $this->assertNotEmpty(array_filter($v['blockers'], fn ($b) => str_contains($b, 'Estonian')), 'missing ET must block');
    }
}
