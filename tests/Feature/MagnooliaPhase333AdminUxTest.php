<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Phase 33.3 — client-obvious admin UX: the "where do I click?" surfaces must
 * render with human language (dashboard action cards, page map, page-text editor
 * human labels, advanced translation warning).
 */
class MagnooliaPhase333AdminUxTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::firstOrCreate(['email' => 'admin333ux@magnoolia.ee'], [
            'name' => 'Admin', 'password' => bcrypt('secret123'),
            'role' => 'magnoolia_admin', 'email_verified_at' => now(),
        ]);
    }

    private function seedAll(): void
    {
        $this->artisan('magnoolia:seed-units')->assertSuccessful();
        $this->artisan('magnoolia:seed-content', ['--force' => true])->assertSuccessful();
    }

    public function test_dashboard_shows_what_do_you_want_to_change_cards(): void
    {
        $this->seedAll();
        $res = $this->actingAs($this->admin())->get('/admin/magnoolia');
        $res->assertOk();
        $res->assertSee('Mida soovid muuta?', false);
        foreach (['Kodude staatused ja hinnad', 'Veebilehe tekstid', 'Pildid ja galerii', 'Kampaania', 'Päringud', 'Avaldamine'] as $card) {
            $res->assertSee($card, false);
        }
        // Cards must route to the real editors.
        $res->assertSee(route('admin.magnoolia.units.index'), false);
        $res->assertSee(route('admin.magnoolia.content.index'), false);
    }

    public function test_page_map_renders_all_public_pages(): void
    {
        $this->seedAll();
        $res = $this->actingAs($this->admin())->get('/admin/magnoolia/site-map');
        $res->assertOk();
        $res->assertSee('Veebilehe kaart', false);
        foreach (['Avaleht', 'Kodud ja hinnad', 'Asendiplaan', 'Asukoht', 'Ehitusinfo', 'Galerii', 'Kontakt'] as $page) {
            $res->assertSee($page, false);
        }
        // Deep-links into the content editor anchors.
        $res->assertSee('#page-galerii', false);
    }

    public function test_page_text_editor_uses_human_labels_not_raw_keys(): void
    {
        $this->seedAll();
        $res = $this->actingAs($this->admin())->get('/admin/magnoolia/content');
        $res->assertOk();
        // Human label shown as the primary heading…
        $res->assertSee('Homepage — hero headline (H1)', false);
        // …and page groups anchored for the page map.
        $res->assertSee('id="page-home"', false);
    }

    public function test_translation_manager_shows_advanced_warning(): void
    {
        $res = $this->actingAs($this->admin())->get('/admin/translation-manager');
        $res->assertOk();
        $res->assertSee('Page Texts', false);
        $res->assertSee('advanced labels and navigation', false);
    }

    public function test_help_shows_first_tasks_and_rules(): void
    {
        $res = $this->actingAs($this->admin())->get('/admin/magnoolia/help');
        $res->assertOk();
        $res->assertSee('Important rules', false);
        $res->assertSee('What not to touch', false);
        $res->assertSee('Before publishing', false);
    }
}
