<?php

namespace Tests\Feature;

use App\Models\MagnooliaMediaItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

/**
 * Phase 34 — security & no-leak gate. Public pages never leak internal data,
 * SVG uploads are rejected (stored-XSS vector), and the media delete guard
 * protects published media.
 */
class MagnooliaPhase34SecurityNoLeakTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::create([
            'name' => 'Admin', 'email' => 'sec34@magnoolia.ee',
            'password' => bcrypt('secret123'), 'role' => 'magnoolia_admin', 'email_verified_at' => now(),
        ]);
    }

    public function test_public_pages_do_not_leak_internal_data(): void
    {
        foreach (['et', 'ru', 'en'] as $loc) {
            foreach (['', '/kodud-ja-hinnad', '/galerii'] as $p) {
                $url = $loc === 'et' ? ($p ?: '/') : '/' . $loc . $p;
                $html = $this->get($url)->assertOk()->getContent();
                foreach (['price_cents', '/source/', '.pptx', '1drv.ms', 'storage/app'] as $needle) {
                    $this->assertStringNotContainsString($needle, $html, "{$needle} leaked on {$url}");
                }
            }
        }
    }

    public function test_svg_upload_is_rejected(): void
    {
        $admin = $this->admin();
        $svg = UploadedFile::fake()->createWithContent('logo.svg', '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>');
        $res = $this->actingAs($admin)->post('/admin/magnoolia/media', [
            'file' => $svg,
            'category' => 'gallery',
        ]);
        $res->assertSessionHasErrors('file');
        $this->assertSame(0, MagnooliaMediaItem::where('original_name', 'logo.svg')->count());
    }

    public function test_raster_upload_is_accepted(): void
    {
        $admin = $this->admin();
        $png = UploadedFile::fake()->image('ok.png', 800, 600);
        $res = $this->actingAs($admin)->post('/admin/magnoolia/media', [
            'file' => $png,
            'category' => 'gallery',
        ]);
        $res->assertSessionHasNoErrors();
        // cleanup created files
        foreach (MagnooliaMediaItem::all() as $m) {
            foreach ([public_path((string) $m->public_path), public_path((string) $m->thumb_path)] as $f) {
                if ($f && is_file($f)) @unlink($f);
            }
        }
    }

    public function test_admin_requires_auth(): void
    {
        $this->get('/admin/magnoolia')->assertRedirect();
        $this->get('/admin/magnoolia/units')->assertRedirect();
    }
}
