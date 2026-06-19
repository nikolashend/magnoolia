<?php

namespace Tests\Feature;

use App\Models\MagnooliaMediaItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MagnooliaPhase331GalleryTest extends TestCase
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

    public function test_seed_gallery_imports_existing_gallery_images(): void
    {
        $this->artisan('magnoolia:seed-gallery')->assertSuccessful();
        $count = MagnooliaMediaItem::where('category', 'gallery')->count();
        $this->assertGreaterThan(0, $count, 'existing public gallery images imported as managed media');
    }

    public function test_seed_gallery_is_idempotent(): void
    {
        $this->artisan('magnoolia:seed-gallery')->assertSuccessful();
        $first = MagnooliaMediaItem::where('category', 'gallery')->count();
        $this->artisan('magnoolia:seed-gallery')->assertSuccessful();
        $this->assertSame($first, MagnooliaMediaItem::where('category', 'gallery')->count(), 'no duplicates on re-run');
    }

    public function test_gallery_images_visible_in_media_library_filter(): void
    {
        $this->artisan('magnoolia:seed-gallery')->assertSuccessful();
        $this->actingAs($this->admin())
            ->get('/admin/magnoolia/media?category=gallery')
            ->assertOk()
            ->assertSee('Exterior', false);
    }

    public function test_public_gallery_uses_published_media_after_publish_else_fallback(): void
    {
        $admin = User::create([
            'name' => 'Admin', 'email' => 'a2@magnoolia.ee', 'password' => bcrypt('x'),
            'role' => 'magnoolia_admin', 'email_verified_at' => now(),
        ]);
        $this->artisan('magnoolia:seed-units')->assertSuccessful();

        // Before any publish: /galerii renders (built-in fallback list) without error.
        $this->get('/galerii')->assertOk();

        // Seed gallery + publish → public payload carries the managed gallery.
        $this->artisan('magnoolia:seed-gallery')->assertSuccessful();
        \Illuminate\Support\Facades\Cache::forget('magnoolia.public.payload');
        app(\App\Services\Magnoolia\MagnooliaPublicationService::class)->publish($admin->id, 'with gallery');
        \Illuminate\Support\Facades\Cache::forget('magnoolia.public.payload');

        $payload = app(\App\Services\Magnoolia\MagnooliaPublicDataRepository::class)->getPublicPayload();
        $this->assertNotEmpty($payload['gallery'] ?? [], 'publication carries managed gallery');
        $this->get('/galerii')->assertOk();
    }
}
