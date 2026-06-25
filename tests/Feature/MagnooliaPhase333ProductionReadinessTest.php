<?php

namespace Tests\Feature;

use App\Models\MagnooliaContentBlock;
use App\Models\MagnooliaMediaItem;
use App\Models\MagnooliaPublication;
use App\Models\MagnooliaUnit;
use App\Models\User;
use App\Services\Magnoolia\MagnooliaPublicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Phase 33.3 — production readiness: after the standard deploy seed commands, the
 * data the client-facing admin depends on must exist (this is what an empty
 * production environment is missing). Mirrors the deploy script's verify step.
 */
class MagnooliaPhase333ProductionReadinessTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::firstOrCreate(['email' => 'admin333@magnoolia.ee'], [
            'name' => 'Admin', 'password' => bcrypt('secret123'),
            'role' => 'magnoolia_admin', 'email_verified_at' => now(),
        ]);
    }

    private function seedAll(): void
    {
        $this->artisan('magnoolia:seed-units')->assertSuccessful();
        $this->artisan('magnoolia:seed-content', ['--force' => true])->assertSuccessful();
        $this->artisan('magnoolia:seed-gallery')->assertSuccessful();
    }

    public function test_seed_produces_production_ready_counts(): void
    {
        if (! is_dir(public_path('assets/magnoolia/gallery'))) {
            $this->markTestSkipped('Public gallery assets not present in this environment.');
        }
        $this->seedAll();

        $this->assertSame(19, MagnooliaUnit::count(), 'must have 19 homes');
        // A fresh seed-gallery imports the 29 public gallery renders (the "31" in the
        // 33.2 report additionally counted 2 pre-existing local manual uploads that
        // the seed does not create). 29 real items = populated, not empty.
        $this->assertGreaterThanOrEqual(29, MagnooliaMediaItem::count(), 'media >= 29 (populated)');
        $this->assertGreaterThanOrEqual(29, MagnooliaMediaItem::where('category', 'gallery')->count(), 'gallery >= 29');
        $this->assertGreaterThanOrEqual(34, MagnooliaContentBlock::count(), 'content blocks >= 34');
    }

    public function test_approved_status_distribution_and_active_publication(): void
    {
        $this->seedAll();
        app(MagnooliaPublicationService::class)->publish($this->admin()->id, 'readiness publish');

        $active = MagnooliaPublication::where('status', 'active')->orderByDesc('version')->first();
        $this->assertNotNull($active, 'an active publication must exist');

        $dist = MagnooliaUnit::selectRaw('status, count(*) c')->groupBy('status')->pluck('c', 'status')->all();
        $this->assertSame(14, $dist['available'] ?? 0);
        $this->assertSame(4, $dist['reserved'] ?? 0);
        $this->assertSame(1, $dist['sold'] ?? 0);
    }

    public function test_admin_sections_show_no_unresolved_empty_messages(): void
    {
        $this->seedAll();
        $admin = $this->admin();

        $this->actingAs($admin)->get('/admin/magnoolia/content')
            ->assertOk()->assertDontSee('php artisan', false)->assertDontSee('not initialized', false);

        $this->actingAs($admin)->get('/admin/magnoolia/media')
            ->assertOk()->assertDontSee('No media yet', false);
    }
}
