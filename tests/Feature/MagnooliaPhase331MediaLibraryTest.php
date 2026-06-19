<?php

namespace Tests\Feature;

use App\Models\MagnooliaMediaItem;
use App\Models\MagnooliaUnit;
use App\Models\User;
use App\Services\Magnoolia\MagnooliaMediaService;
use App\Services\Magnoolia\MagnooliaPublicationService;
use App\Services\Magnoolia\MagnooliaPublicDataRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class MagnooliaPhase331MediaLibraryTest extends TestCase
{
    use RefreshDatabase;

    private array $cleanup = [];

    private function admin(): User
    {
        return User::create([
            'name' => 'Admin', 'email' => 'admin@magnoolia.ee',
            'password' => bcrypt('secret123'), 'role' => 'magnoolia_admin',
            'email_verified_at' => now(),
        ]);
    }

    protected function tearDown(): void
    {
        // Remove any public/private files created during the test run.
        foreach (MagnooliaMediaItem::all() as $m) {
            foreach ([public_path((string) $m->public_path), public_path((string) $m->thumb_path), storage_path('app/private/' . $m->original_path)] as $f) {
                if ($f && is_file($f)) {
                    @unlink($f);
                }
            }
        }
        parent::tearDown();
    }

    public function test_media_index_reachable_for_admin(): void
    {
        $this->actingAs($this->admin())->get('/admin/magnoolia/media')->assertOk()->assertSee('Images');
    }

    public function test_upload_valid_image_creates_optimized_webp_and_thumbnail(): void
    {
        $admin = $this->admin();
        $res = $this->actingAs($admin)->post('/admin/magnoolia/media', [
            'file' => UploadedFile::fake()->image('hero.jpg', 1200, 800),
            'title' => 'Hero front view',
            'category' => 'hero',
            'alt_et' => 'Esivaade',
        ]);
        $res->assertRedirect();

        $item = MagnooliaMediaItem::first();
        $this->assertNotNull($item);
        $this->assertSame('hero', $item->category);
        $this->assertNotNull($item->public_path);
        $this->assertNotNull($item->thumb_path);
        $this->assertTrue(is_file(public_path($item->public_path)), 'optimized webp written');
        $this->assertTrue(is_file(public_path($item->thumb_path)), 'thumbnail written');
        $this->assertSame(1200, $item->width);
        $this->assertDatabaseHas('magnoolia_audit_logs', ['action' => 'media_uploaded']);
    }

    public function test_invalid_file_type_is_rejected(): void
    {
        $admin = $this->admin();
        $res = $this->actingAs($admin)->post('/admin/magnoolia/media', [
            'file' => UploadedFile::fake()->create('virus.txt', 50, 'text/plain'),
            'category' => 'other',
        ]);
        $res->assertSessionHasErrors('file');
        $this->assertSame(0, MagnooliaMediaItem::count());
    }

    public function test_alt_text_can_be_saved(): void
    {
        $admin = $this->admin();
        $item = app(MagnooliaMediaService::class)->store(UploadedFile::fake()->image('a.png', 600, 400), [
            'title' => 'X', 'category' => 'gallery', 'uploaded_by' => $admin->id,
        ]);
        $this->actingAs($admin)->patch('/admin/magnoolia/media/' . $item->id, [
            'title' => 'X', 'category' => 'gallery', 'alt_et' => 'ET alt', 'alt_ru' => 'RU alt', 'alt_en' => 'EN alt',
        ])->assertRedirect();
        $item->refresh();
        $this->assertSame('ET alt', $item->alt_et);
        $this->assertFalse($item->alt_missing);
    }

    public function test_assigning_media_to_unit_floorplan_updates_draft_not_public_until_publish(): void
    {
        $admin = $this->admin();
        $this->artisan('magnoolia:seed-units')->assertSuccessful();
        app(MagnooliaPublicationService::class)->publish($admin->id, 'v1');

        $item = app(MagnooliaMediaService::class)->store(UploadedFile::fake()->image('plan.png', 800, 600), [
            'title' => 'Plan', 'category' => 'floorplan', 'uploaded_by' => $admin->id,
        ]);

        // Public floor-plan for tee-3-1 before assignment.
        Cache::forget('magnoolia.public.payload');
        $repo = app(MagnooliaPublicDataRepository::class);
        $publicBefore = collect($repo->getPublicPayload()['units'])->firstWhere('unit_key', 'tee-3-1')['floorplan_1_pdf'] ?? null;

        $this->actingAs($admin)->patch('/admin/magnoolia/media/' . $item->id, [
            'title' => 'Plan', 'category' => 'floorplan', 'assignment_target' => 'unit:tee-3-1:floor1',
        ])->assertRedirect();

        // Draft unit now points at the media; public still shows the old value (not published).
        $this->assertSame($item->public_path, MagnooliaUnit::where('unit_key', 'tee-3-1')->value('floorplan_floor_1'));
        Cache::forget('magnoolia.public.payload');
        $publicAfter = collect($repo->getPublicPayload()['units'])->firstWhere('unit_key', 'tee-3-1')['floorplan_1_pdf'] ?? null;
        $this->assertSame($publicBefore, $publicAfter, 'assignment is draft-only until publish');
    }

    public function test_deleting_media_used_in_active_publication_requires_confirmation(): void
    {
        $admin = $this->admin();
        $this->artisan('magnoolia:seed-units')->assertSuccessful();

        $item = app(MagnooliaMediaService::class)->store(UploadedFile::fake()->image('plan.png', 800, 600), [
            'title' => 'Plan', 'category' => 'floorplan', 'uploaded_by' => $admin->id,
        ]);
        // Assign + publish so the media path lives in the active publication.
        $this->actingAs($admin)->patch('/admin/magnoolia/media/' . $item->id, [
            'title' => 'Plan', 'category' => 'floorplan', 'assignment_target' => 'unit:tee-3-1:floor1',
        ]);
        app(MagnooliaPublicationService::class)->publish($admin->id, 'v-with-media');

        // Delete without confirm → blocked.
        $this->actingAs($admin)->delete('/admin/magnoolia/media/' . $item->id)->assertSessionHasErrors('media');
        $this->assertDatabaseHas('magnoolia_media_items', ['id' => $item->id]);

        // Delete with confirm → removed.
        $this->actingAs($admin)->delete('/admin/magnoolia/media/' . $item->id, ['confirm_used' => '1'])->assertRedirect();
        $this->assertDatabaseMissing('magnoolia_media_items', ['id' => $item->id]);
    }
}
