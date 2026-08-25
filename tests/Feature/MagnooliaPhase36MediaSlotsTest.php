<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\MagnooliaMediaItem;
use App\Models\MagnooliaMediaSlot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Phase 36, Module B — assignable image slots.
 *
 * The safety property that matters most: an unassigned slot must render exactly
 * what the template shipped with. If that breaks, pictures vanish from the live
 * site the moment this ships — a far worse outcome than pictures being hard to
 * change, which is the problem we are solving.
 */
class MagnooliaPhase36MediaSlotsTest extends TestCase
{
    use RefreshDatabase;

    public function test_unbound_slot_falls_back_to_the_shipped_file(): void
    {
        $slot = mg_slot('home.intro.image');

        $this->assertFalse($slot['bound']);
        $this->assertStringContainsString('Cam020.0000.jpg', $slot['src']);
        $this->assertNotSame('', $slot['alt'], 'A fallback slot must still carry alt text.');
    }

    public function test_unknown_slot_never_throws(): void
    {
        $slot = mg_slot('this.slot.does.not.exist');

        $this->assertFalse($slot['bound']);
        $this->assertIsString($slot['src']);
    }

    public function test_every_registered_slot_has_a_working_fallback(): void
    {
        // Guards against registering a slot whose default file was renamed away.
        foreach (array_keys(config('magnoolia_slots', [])) as $key) {
            $slot = mg_slot($key);
            $this->assertNotSame(asset(''), $slot['src'], "Slot {$key} has no fallback image.");
            $this->assertNotSame('', $slot['alt'], "Slot {$key} has no fallback alt text.");
        }
    }

    public function test_a_published_binding_replaces_the_image_and_alt(): void
    {
        $this->publishSlots([
            'home.intro.image' => [
                'src' => 'assets/magnoolia/media/new-render.webp',
                'alt_et' => 'Uus renderdus',
                'alt_ru' => 'Новый рендер',
                'alt_en' => 'New render',
                'width' => 1600,
                'height' => 900,
            ],
        ]);

        app()->setLocale('et');
        $slot = mg_slot('home.intro.image');

        $this->assertTrue($slot['bound']);
        $this->assertStringContainsString('new-render.webp', $slot['src']);
        $this->assertSame('Uus renderdus', $slot['alt']);
        $this->assertSame(1600, $slot['width']);
    }

    public function test_alt_text_follows_the_page_locale(): void
    {
        $this->publishSlots([
            'home.intro.image' => ['src' => 'a.webp', 'alt_et' => 'ET', 'alt_ru' => 'RU', 'alt_en' => 'EN'],
        ]);

        app()->setLocale('ru');
        $this->assertSame('RU', mg_slot('home.intro.image')['alt']);

        app()->setLocale('en');
        $this->assertSame('EN', mg_slot('home.intro.image')['alt']);
    }

    public function test_binding_without_a_file_is_ignored(): void
    {
        // A media row that never produced a public asset must not blank the picture.
        $this->publishSlots(['home.intro.image' => ['src' => '', 'alt_et' => 'x']]);

        $slot = mg_slot('home.intro.image');

        $this->assertFalse($slot['bound']);
        $this->assertStringContainsString('Cam020.0000.jpg', $slot['src']);
    }

    public function test_the_page_still_renders_an_image_with_no_bindings(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('Cam020.0000', $html, 'The Tutvustus picture must survive an empty slot table.');
    }

    public function test_no_template_uses_the_removed_short_php_directive(): void
    {
        // This Laravel dropped `@php(...)`. It does not error — it compiles to a
        // bare "<?php(" with no semicolon and no close tag, so the remainder of the
        // template becomes raw PHP and the page 500s on a stray @endif far below.
        // Wiring the four page headers hit exactly this, hence the guard.
        $offenders = [];
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(resource_path('views'), \FilesystemIterator::SKIP_DOTS)
        );
        foreach ($files as $file) {
            if (! str_ends_with($file->getFilename(), '.blade.php')) {
                continue;
            }
            if (preg_match('/@php\s*\(/', (string) file_get_contents($file->getPathname()))) {
                $offenders[] = str_replace(resource_path('views') . DIRECTORY_SEPARATOR, '', $file->getPathname());
            }
        }

        $this->assertSame([], $offenders, 'Use `@php $x = ...; @endphp` — the short form silently breaks the page.');
    }

    public function test_a_slot_default_with_a_space_in_the_file_name_is_url_safe(): void
    {
        // "Interior 1.jpg" is a real shipped render; the registry stores the on-disk
        // name, so the URL must come out encoded.
        $this->assertStringContainsString('Interior%201.jpg', mg_slot('header.sisedisain')['src']);
        $this->assertStringNotContainsString('Interior 1.jpg', mg_slot('header.sisedisain')['src']);
    }

    public function test_every_slot_default_points_at_a_file_that_exists(): void
    {
        foreach (config('magnoolia_slots', []) as $key => $definition) {
            $this->assertFileExists(
                public_path($definition['default']),
                "Slot {$key} falls back to a file that is not in the repository."
            );
        }
    }

    public function test_slot_definitions_are_readable_for_the_admin_screen(): void
    {
        $definitions = MagnooliaMediaSlot::definitions();

        $this->assertNotEmpty($definitions);
        foreach ($definitions as $key => $definition) {
            $this->assertArrayHasKey('label', $definition, "Slot {$key} needs a client-facing label.");
            $this->assertArrayHasKey('page', $definition);
            $this->assertArrayHasKey('default', $definition);
        }
    }

    public function test_a_slot_row_can_be_bound_to_a_media_item(): void
    {
        $media = MagnooliaMediaItem::query()->create([
            'title' => 'Test render',
            'category' => 'exterior',
            'public_path' => 'assets/magnoolia/media/test.webp',
            'alt_et' => 'Test',
        ]);

        $slot = MagnooliaMediaSlot::query()->create([
            'slot_key' => 'home.intro.image',
            'media_item_id' => $media->id,
        ]);

        $this->assertSame('assets/magnoolia/media/test.webp', $slot->fresh()->mediaItem->public_path);
    }


    public function test_the_admin_slots_screen_renders(): void
    {
        $admin = \App\Models\User::factory()->create(['role' => 'magnoolia_client_admin', 'email_verified_at' => now()]);

        $res = $this->actingAs($admin)->get('/admin/magnoolia/media-slots');

        $res->assertOk();
        $res->assertSee('Piltide asukohad lehel', false);
        // Labels the client recognises, and the promise that nothing goes live early.
        $res->assertSee('Tutvustuse ploki foto', false);
        $res->assertSee('Vaikimisi pilt', false);
    }

    public function test_assigning_a_slot_does_not_change_the_public_page_before_publish(): void
    {
        $media = MagnooliaMediaItem::query()->create([
            'title' => 'Uus render', 'category' => 'exterior',
            'public_path' => 'assets/magnoolia/media/uus.webp', 'alt_et' => 'Uus',
        ]);
        $admin = \App\Models\User::factory()->create(['role' => 'magnoolia_client_admin', 'email_verified_at' => now()]);

        $this->actingAs($admin)->patch('/admin/magnoolia/media-slots/home.intro.image', [
            'media_item_id' => $media->id,
        ])->assertRedirect();

        $this->assertDatabaseHas('magnoolia_media_slots', ['slot_key' => 'home.intro.image', 'media_item_id' => $media->id]);

        // Draft only (Phase 36 decision 2): the live page keeps the old picture.
        $this->assertFalse(mg_slot('home.intro.image')['bound']);
    }

    public function test_an_unknown_slot_key_cannot_be_assigned(): void
    {
        $admin = \App\Models\User::factory()->create(['role' => 'magnoolia_client_admin', 'email_verified_at' => now()]);

        $this->actingAs($admin)
            ->patch('/admin/magnoolia/media-slots/made.up.slot', ['media_item_id' => null])
            ->assertNotFound();
    }

    /** @param array<string, array<string, mixed>> $slots */
    private function publishSlots(array $slots): void
    {
        $repo = \Mockery::mock(\App\Services\Magnoolia\MagnooliaPublicDataRepository::class)->makePartial();
        $repo->shouldReceive('getPublicPayload')->andReturn(['slots' => $slots, 'content' => [], 'units' => [], 'settings' => []]);
        $repo->shouldReceive('getSettings')->andReturn([]);
        $repo->shouldReceive('getUnits')->andReturn([]);
        $this->app->instance(\App\Services\Magnoolia\MagnooliaPublicDataRepository::class, $repo);
    }
}
