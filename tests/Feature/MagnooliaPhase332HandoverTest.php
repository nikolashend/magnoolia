<?php

namespace Tests\Feature;

use App\Models\MagnooliaContentBlock;
use App\Models\MagnooliaMediaItem;
use App\Models\User;
use App\Services\Magnoolia\MagnooliaPublicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * Phase 33.2 — admin client-handover verification. Confirms the previously
 * empty/misleading admin sections are now populated and client-ready, that
 * editing is draft-only until publish, and that no developer-only messages
 * leak to the client.
 */
class MagnooliaPhase332HandoverTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::firstOrCreate(
            ['email' => 'admin33-2@magnoolia.ee'],
            [
                'name' => 'Admin', 'password' => bcrypt('secret123'),
                'role' => 'magnoolia_admin', 'email_verified_at' => now(),
            ]
        );
    }

    private function seedAll(): void
    {
        $this->artisan('magnoolia:seed-units')->assertSuccessful();
        $this->artisan('magnoolia:seed-content')->assertSuccessful();
        $this->artisan('magnoolia:seed-gallery')->assertSuccessful();
    }

    public function test_seed_units_uses_approved_status_baseline_14_4_1(): void
    {
        $this->artisan('magnoolia:seed-units')->assertSuccessful();
        $dist = \App\Models\MagnooliaUnit::query()
            ->selectRaw('status, count(*) c')->groupBy('status')->pluck('c', 'status')->all();
        $this->assertSame(14, $dist['available'] ?? 0);
        $this->assertSame(4, $dist['reserved'] ?? 0);
        $this->assertSame(1, $dist['sold'] ?? 0);
        // Approved reserved/sold homes.
        $this->assertSame('reserved', \App\Models\MagnooliaUnit::where('unit_key', 'tee-1-3')->value('status'));
        $this->assertSame('sold', \App\Models\MagnooliaUnit::where('unit_key', 'tee-5-1')->value('status'));
    }

    public function test_content_blocks_cover_all_pages_and_admin_shows_them(): void
    {
        $this->seedAll();
        $this->assertSame(34, MagnooliaContentBlock::count());

        $res = $this->actingAs($this->admin())->get('/admin/magnoolia/content');
        $res->assertOk()->assertSee('Page Texts');
        // No developer-only artisan instruction is shown to the client.
        $res->assertDontSee('php artisan', false);
        $res->assertDontSee('No content blocks yet', false);
        // Real page labels render.
        $res->assertSee('Avaleht (Homepage)', false);
        $res->assertSee('Galerii', false);
    }

    public function test_seed_gallery_populates_media_library_with_real_items(): void
    {
        if (! is_dir(public_path('assets/magnoolia/gallery'))) {
            $this->markTestSkipped('Public gallery assets not present in this environment.');
        }
        $this->seedAll();
        $this->assertGreaterThanOrEqual(20, MagnooliaMediaItem::count());
        $this->assertGreaterThan(0, MagnooliaMediaItem::where('category', 'gallery')->count());

        $res = $this->actingAs($this->admin())->get('/admin/magnoolia/media');
        $res->assertOk()->assertSee('Upload and manage images', false);
        $res->assertDontSee('No media yet', false);

        // Gallery filter shows seeded gallery items.
        $this->actingAs($this->admin())->get('/admin/magnoolia/media?category=gallery')
            ->assertOk();
    }

    public function test_editing_a_new_page_text_is_draft_only_then_publishes(): void
    {
        $admin = $this->admin();
        $this->seedAll();
        app(MagnooliaPublicationService::class)->publish($admin->id, 'baseline');

        // Edit the GALERII heading (newly wired in 33.2).
        $block = MagnooliaContentBlock::where('key', 'page.galerii.page_h1')->firstOrFail();
        $marker = 'ZZ-GALERII-HEADING-ZZ';
        $this->actingAs($admin)->patch('/admin/magnoolia/content/' . $block->id, [
            'et' => $marker, 'ru' => $block->ru, 'en' => $block->en, 'is_active' => '1',
        ])->assertRedirect();

        // Draft only — public unchanged.
        Cache::forget('magnoolia.public.payload');
        $this->get('/galerii')->assertOk()->assertDontSee($marker);

        // Publish → public now shows the edited heading.
        app(MagnooliaPublicationService::class)->publish($admin->id, 'galerii heading edit');
        Cache::forget('magnoolia.public.payload');
        $this->get('/galerii')->assertOk()->assertSee($marker);
    }

    public function test_mg_text_falls_back_to_lang_for_wired_pages(): void
    {
        // With no override, each wired page heading returns its lang value.
        foreach (['page.galerii.page_h1', 'page.asukoht.page_h1', 'page.ehitusinfo.page_h1', 'footer.tagline'] as $key) {
            $this->assertSame(__('magnoolia.' . $key), mg_text($key));
        }
    }

    public function test_translation_manager_renders_editable_values(): void
    {
        $res = $this->actingAs($this->admin())->get('/admin/translation-manager');
        $res->assertOk();
        // The Page-Texts guidance and at least one editable field are present.
        $res->assertSee('Page Texts', false);
        $res->assertSee('wire:model="entries.', false);
        // A real nav value is pre-filled into an input.
        $res->assertSee(__('magnoolia.nav.home'), false);
    }

    public function test_translation_manager_loads_values_into_indexed_entries(): void
    {
        // Indexed entries (not dotted keys) are what make Livewire wire:model bind
        // correctly — assert the real value is actually loaded, not blank.
        $component = \Livewire\Livewire::actingAs($this->admin())
            ->test(\App\Filament\Pages\TranslationManager::class)
            ->assertSet('section', 'nav')
            ->assertSeeHtml(__('magnoolia.nav.home'));

        $entries = $component->get('entries');
        $this->assertNotEmpty($entries, 'entries must be populated');
        $this->assertSame('nav.home', $entries[0]['key']);
        $this->assertNotSame('', (string) $entries[0]['value'], 'value must not be blank');
    }

    public function test_admin_dashboard_has_no_laravel_or_artisan_leak(): void
    {
        $this->seedAll();
        $res = $this->actingAs($this->admin())->get('/admin/magnoolia');
        $res->assertOk();
        $res->assertDontSee('php artisan', false);
    }
}
