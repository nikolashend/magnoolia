<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Pages\TranslationManager;
use App\Models\MagnooliaLead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Phase 36 stage 1 — handing content control to the client.
 *
 * Three changes, each fixing something Phase 35.1 exposed:
 *   1. the client could not reach the translation editor at all;
 *   2. leaving a language blank kept the OLD translation, so an Estonian
 *      correction silently contradicted the untouched RU/EN text;
 *   3. the sales contact was editable in admin but the mailer ignored it.
 */
class MagnooliaPhase36AdminEditabilityTest extends TestCase
{
    use RefreshDatabase;

    // ── 1. Translation editor access ────────────────────────────────────

    public function test_client_admin_can_reach_the_translation_editor(): void
    {
        $client = User::factory()->create(['role' => 'magnoolia_client_admin']);

        $this->actingAs($client);
        $this->assertTrue(TranslationManager::canAccess(), 'The client must be able to edit texts.');
    }

    public function test_system_admin_still_reaches_the_translation_editor(): void
    {
        $admin = User::factory()->create(['role' => 'magnoolia_admin']);

        $this->actingAs($admin);
        $this->assertTrue(TranslationManager::canAccess());
    }

    public function test_other_roles_are_still_kept_out(): void
    {
        $editor = User::factory()->create(['role' => 'magnoolia_editor']);

        $this->actingAs($editor);
        $this->assertFalse(TranslationManager::canAccess(), 'Only admin and client admin may edit raw texts.');
    }

    // ── 2. Language fallback ────────────────────────────────────────────

    public function test_an_estonian_only_edit_is_shown_on_other_locales(): void
    {
        // The client edits ET and leaves RU blank. Showing the stale RU lang value
        // would contradict the fresh Estonian — the untranslated Estonian is the
        // lesser evil and the agreed behaviour (Phase 36 decision 4).
        $this->publishContent(['et' => ['hero.h1' => 'Uus eestikeelne pealkiri']]);

        app()->setLocale('ru');
        $this->assertSame('Uus eestikeelne pealkiri', mg_text('hero.h1'));
    }

    public function test_a_translated_locale_keeps_its_own_text(): void
    {
        $this->publishContent([
            'et' => ['hero.h1' => 'Eesti pealkiri'],
            'ru' => ['hero.h1' => 'Русский заголовок'],
        ]);

        app()->setLocale('ru');
        $this->assertSame('Русский заголовок', mg_text('hero.h1'));
    }

    public function test_an_untouched_key_still_uses_its_own_translation(): void
    {
        // Nothing was overridden, so the RU lang file is authoritative — the
        // Estonian fallback must NOT hijack keys the client never edited.
        $this->publishContent([]);

        app()->setLocale('ru');
        $ru = mg_text('hero.h1');

        app()->setLocale('et');
        $et = mg_text('hero.h1');

        $this->assertNotSame($et, $ru, 'An untouched key must keep its real translation.');
    }

    // ── 3. Lead recipient ───────────────────────────────────────────────

    public function test_enquiry_goes_to_the_sales_contact_set_in_admin(): void
    {
        $this->recordMail();
        $this->publishSettings(['sales_contact_email' => 'uus.muugijuht@estlanda.ee']);

        $this->post('/kontakt', $this->validEnquiry());

        $this->assertContains('uus.muugijuht@estlanda.ee', $this->capturedRecipients());
    }

    public function test_enquiry_falls_back_to_config_when_nothing_is_published(): void
    {
        $this->recordMail();
        $this->post('/kontakt', $this->validEnquiry());

        $this->assertContains(config('magnoolia.project.contact_email'), $this->capturedRecipients());
    }

    public function test_lead_is_stored_even_when_sending_fails(): void
    {
        // A silent mail failure must never lose the enquiry — the Leads screen is
        // the safety net, which is why it now shows the delivery status.
        Mail::shouldReceive('raw')->andThrow(new \RuntimeException('SMTP down'));

        $this->post('/kontakt', $this->validEnquiry());

        $lead = MagnooliaLead::query()->latest('id')->first();
        $this->assertNotNull($lead, 'The enquiry must be stored regardless of mail delivery.');
        $this->assertSame('failed', $lead->mail_status);
    }


    // ── 4. Module A: overrides reach every read, not just mg_text() ─────

    public function test_a_published_override_changes_a_plain_translation_call(): void
    {
        // Templates mostly use __(). Before Module A an edit to such a key showed an
        // input in admin and did nothing on the site.
        $this->publishContent(['et' => ['pricing.area_total' => 'Netopind kokku (muudetud)']]);
        $this->bootOverrides();

        app()->setLocale('et');
        $this->assertSame('Netopind kokku (muudetud)', __('magnoolia.pricing.area_total'));
    }

    public function test_untouched_keys_survive_the_overlay(): void
    {
        // The dangerous failure mode: addLines() marks the translation group loaded,
        // so overlaying before the lang file is read would blank every other key and
        // wipe the site's text. This asserts the file is still there.
        $this->publishContent(['et' => ['pricing.area_total' => 'Muudetud']]);
        $this->bootOverrides();

        app()->setLocale('et');
        $untouched = __('magnoolia.pricing.rooms');

        $this->assertNotSame('magnoolia.pricing.rooms', $untouched, 'Untouched keys must still resolve.');
        $this->assertNotSame('', $untouched);
    }

    public function test_blank_override_does_not_erase_the_existing_text(): void
    {
        $this->publishContent(['et' => ['pricing.area_total' => '']]);
        $this->bootOverrides();

        app()->setLocale('et');
        $this->assertNotSame('', __('magnoolia.pricing.area_total'));
    }

    public function test_every_editable_key_is_reachable(): void
    {
        // Guards the promise made to the client: if a key is offered in the editor,
        // editing it must change the site.
        $registry = [];
        foreach (config('magnoolia_editable', []) as $page => $definition) {
            foreach ($definition['groups'] ?? [] as $group => $keys) {
                foreach ($keys as $key => $label) {
                    $registry[$key] = $label;
                }
            }
        }
        $this->assertNotEmpty($registry);

        $sample = array_slice(array_keys($registry), 0, 20);
        $overrides = [];
        foreach ($sample as $key) {
            $overrides[$key] = 'OVERRIDE::' . $key;
        }
        $this->publishContent(['et' => $overrides]);
        $this->bootOverrides();

        app()->setLocale('et');
        foreach ($sample as $key) {
            $this->assertSame('OVERRIDE::' . $key, __('magnoolia.' . $key), "Key {$key} is offered for editing but does not reach the page.");
        }
    }

    private function bootOverrides(): void
    {
        (new \App\Providers\MagnooliaContentOverrideProvider($this->app))->boot();
    }

    // ── helpers ─────────────────────────────────────────────────────────

    /**
     * Recipients of the enquiry mail.
     *
     * Mail::raw() builds its message inside a closure, which Mail::assertSent()
     * cannot introspect, so the closure is run against a recorder instead.
     *
     * @return array<int, string>
     */
    private array $recipients = [];

    private function capturedRecipients(): array
    {
        return $this->recipients;
    }

    private function recordMail(): void
    {
        Mail::shouldReceive('raw')->andReturnUsing(function ($body, $callback) {
            $recorder = new class {
                /** @var array<int, string> */
                public array $to = [];
                public function to($address) { $this->to[] = $address; return $this; }
                public function replyTo($address, $name = null) { return $this; }
                public function subject($subject) { return $this; }
            };
            $callback($recorder);
            $this->recipients = array_merge($this->recipients, $recorder->to);
        });
    }

    /** @return array<string, mixed> */
    private function validEnquiry(): array
    {
        return [
            'name' => 'Test Ostja',
            'email' => 'ostja@example.com',
            'phone' => '+372 5555 5555',
            'message' => 'Palun saatke pakkumine.',
            'consent' => 'on',
        ];
    }

    // ── Campaign: publishing must not silently drop the offer ──────────

    public function test_the_seed_command_loads_the_config_campaign_into_the_admin(): void
    {
        $this->artisan('magnoolia:seed-campaign')->assertSuccessful();

        $settings = \App\Models\MagnooliaSetting::query()->latest('id')->first();

        $this->assertNotNull($settings);
        $this->assertTrue($settings->campaign_active);
        // The wording must be the one visitors actually saw — the banner rendered
        // `pricing.special_offer` until Phase 35.1. config carries a second, differently
        // worded copy of the same offer; importing that would reword the live promise.
        $this->assertSame(__('magnoolia.pricing.special_offer', [], 'et'), $settings->campaign_note_et);
        $this->assertSame(__('magnoolia.pricing.special_offer', [], 'ru'), $settings->campaign_note_ru);
        $this->assertStringStartsWith('Eripakkumine:', (string) $settings->campaign_note_et);
        $this->assertSame(2000000, $settings->campaign_discount_cents, '20 000 € must be stored as cents.');
        $this->assertSame('2026-08-31', $settings->campaign_deadline->toDateString());
    }

    public function test_the_seed_command_does_not_overwrite_a_campaign_the_client_wrote(): void
    {
        \App\Models\MagnooliaSetting::query()->create(['campaign_active' => true, 'campaign_note_et' => 'Kliendi enda tekst']);

        $this->artisan('magnoolia:seed-campaign')->assertSuccessful();

        $this->assertSame('Kliendi enda tekst', \App\Models\MagnooliaSetting::query()->latest('id')->first()->campaign_note_et);
    }

    public function test_force_replaces_the_campaign_text(): void
    {
        \App\Models\MagnooliaSetting::query()->create(['campaign_active' => true, 'campaign_note_et' => 'Vana tekst']);

        $this->artisan('magnoolia:seed-campaign', ['--force' => true])->assertSuccessful();

        $this->assertSame(__('magnoolia.pricing.special_offer', [], 'et'),
            \App\Models\MagnooliaSetting::query()->latest('id')->first()->campaign_note_et);
    }

    public function test_the_ribbon_and_the_banner_carry_their_own_texts(): void
    {
        // The gold ribbon on the home page is a narrow strip with a one-liner; the
        // red banner above the price table carries the full sentence. Phase 35.1
        // collapsed both onto one admin field, so the strip started printing the
        // long sentence. They are separate texts and must stay separate.
        $this->artisan('magnoolia:seed-campaign')->assertSuccessful();
        $settings = \App\Models\MagnooliaSetting::query()->latest('id')->first();

        $this->assertSame(config('magnoolia.campaign.body_short_et'), $settings->campaign_note_short_et);
        $this->assertNotSame($settings->campaign_note_et, $settings->campaign_note_short_et);
    }

    public function test_the_short_text_falls_back_to_the_long_one(): void
    {
        // A campaign written by hand may leave the short field blank — the ribbon
        // must still say something rather than vanish.
        $this->publishSettings(['campaign' => [
            'active' => true, 'note_et' => 'Ainult pikk tekst',
        ]]);

        app()->setLocale('et');
        $campaign = $this->composedCampaign();

        $this->assertSame('Ainult pikk tekst', $campaign['body_short']);
    }

    public function test_the_short_text_and_legal_note_follow_the_locale(): void
    {
        $this->publishSettings(['campaign' => [
            'active' => true,
            'note_et' => 'Pikk', 'note_short_et' => 'Lühike', 'legal_note' => 'ET tingimused',
            'note_short_ru' => 'Коротко', 'legal_note_ru' => 'RU условия',
        ]]);

        app()->setLocale('ru');
        $campaign = $this->composedCampaign();

        $this->assertSame('Коротко', $campaign['body_short']);
        $this->assertSame('RU условия', $campaign['legal_note']);
    }

    public function test_a_missing_translation_of_the_legal_note_falls_back_to_estonian(): void
    {
        $this->publishSettings(['campaign' => [
            'active' => true, 'note_et' => 'Pikk', 'legal_note' => 'ET tingimused',
        ]]);

        app()->setLocale('en');

        $this->assertSame('ET tingimused', $this->composedCampaign()['legal_note']);
    }

    public function test_the_campaign_form_offers_both_texts(): void
    {
        $admin = User::factory()->create(['role' => 'magnoolia_client_admin', 'email_verified_at' => now()]);

        $res = $this->actingAs($admin)->get('/admin/magnoolia/campaign');

        $res->assertOk();
        $res->assertSee('campaign_note_short_et', false);
        $res->assertSee('campaign_legal_note_ru', false);
    }

    public function test_the_campaign_form_saves_both_texts(): void
    {
        $admin = User::factory()->create(['role' => 'magnoolia_client_admin', 'email_verified_at' => now()]);

        $this->actingAs($admin)->post('/admin/magnoolia/campaign', [
            'campaign_active' => 1,
            'campaign_discount_type' => 'fixed',
            'campaign_discount_eur' => 20000,
            'campaign_note_et' => 'Pikk lause',
            'campaign_note_short_et' => 'Lühike riba',
            'campaign_legal_note_ru' => 'RU väiketekst',
        ])->assertRedirect();

        $settings = \App\Models\MagnooliaSetting::query()->latest('id')->first();

        $this->assertSame('Lühike riba', $settings->campaign_note_short_et);
        $this->assertSame('RU väiketekst', $settings->campaign_legal_note_ru);
    }

    /** The campaign array the templates receive, as built by the view composer. */
    private function composedCampaign(): array
    {
        $view = new \Illuminate\View\View(
            app('view'), app('view')->getEngineResolver()->resolve('blade'), 'stub', 'stub', []
        );
        app(\App\View\Composers\MagnooliaPublicDataComposer::class)->compose($view);

        return $view->getData()['mgPublic']['campaign'];
    }

    public function test_an_empty_campaign_screen_is_reported_but_never_blocks(): void
    {
        // The notice is INFO on purpose. Publishing is refused while any warning is
        // unconfirmed, and this one cannot be cleared by a client who wants the offer
        // retired — they cannot edit config. As a warning it would have to be ticked
        // past on every publish, which trains people to ignore the real ones.
        \App\Models\MagnooliaSetting::query()->create([]);

        $v = app(\App\Services\Magnoolia\MagnooliaValidationService::class)->validateDraft();

        $this->assertNotEmpty(array_filter($v['info'], fn ($i) => str_contains($i, 'no special offer is shown')));
        $this->assertEmpty(array_filter($v['warnings'], fn ($w) => stripos($w, 'special offer') !== false));
        $this->assertEmpty(array_filter($v['blockers'], fn ($b) => stripos($b, 'special offer') !== false));
    }

    public function test_the_notice_clears_once_a_campaign_is_set(): void
    {
        $this->artisan('magnoolia:seed-campaign')->assertSuccessful();

        $v = app(\App\Services\Magnoolia\MagnooliaValidationService::class)->validateDraft();

        $this->assertEmpty(array_filter($v['info'], fn ($i) => str_contains($i, 'no special offer is shown')));
    }

    public function test_no_admin_message_ever_tells_the_client_to_run_a_command(): void
    {
        \App\Models\MagnooliaSetting::query()->create([]);

        $v = app(\App\Services\Magnoolia\MagnooliaValidationService::class)->validateDraft();

        foreach (array_merge($v['info'], $v['warnings'], $v['blockers']) as $message) {
            $this->assertStringNotContainsString('artisan', $message);
        }
    }

    // ── Main navigation: the front page marks nothing ──────────────────

    public function test_the_front_page_marks_no_menu_item_as_current(): void
    {
        // Server-side this has always been right; the template's JS was adding the
        // class on top. Asserted here so the server side cannot regress either.
        foreach (['/', '/ru', '/en'] as $uri) {
            $html = $this->get($uri)->assertOk()->getContent();
            $this->assertSame(0, substr_count($html, '<li class="current">'),
                "The front page {$uri} must not mark a navigation item as current.");
        }

        $this->assertSame(1, substr_count($this->get('/asukoht')->getContent(), '<li class="current">'));
    }

    public function test_the_template_does_not_highlight_the_first_menu_item_by_default(): void
    {
        // zoomvilla.js shipped a static-site fallback: with no file name in the URL it
        // marked the first menu entry as current. Every front page URL ends in "/", so
        // Asukoht lit up as though the visitor were on the location page. If a theme
        // update reintroduces this line, the bug comes back silently.
        $js = file_get_contents(public_path('assets/js/zoomvilla.js'));

        $this->assertStringNotContainsString(
            'selector.find("li").eq(0).addClass("current")',
            $js,
            'The template is highlighting the first menu item again.'
        );
    }

    // ── Plan A / plan B: one wording, both pages ───────────────────────

    public function test_the_plan_cards_say_the_same_thing_on_both_pages(): void
    {
        // The client reported the home page describing the plans as "3-kodune
        // terrassmaja" / "4-kodune terrassmaja" while Kodud ja hinnad described the
        // home types. The block groups homes by plan_type and lists their addresses,
        // so the home-type wording is the correct one — and both blocks now read it
        // from the same keys.
        $home = $this->get('/')->assertOk()->getContent();
        $subpage = $this->get('/kodud-ja-hinnad')->assertOk()->getContent();

        foreach ([
            __('magnoolia.page.kodudjahinnad.plan_a_title'),
            __('magnoolia.page.kodudjahinnad.plan_b_title'),
        ] as $wording) {
            $this->assertStringContainsString($wording, $home);
            $this->assertStringContainsString($wording, $subpage);
        }
    }

    public function test_the_building_typology_wording_is_gone_everywhere(): void
    {
        foreach (['/', '/kodud-ja-hinnad', '/ru/', '/en/'] as $uri) {
            $this->assertStringNotContainsString(
                'kodune terrassmaja',
                $this->get($uri)->assertOk()->getContent(),
                "The stale building-typology label is still on {$uri}."
            );
        }
    }

    public function test_editing_the_plan_wording_once_changes_both_pages(): void
    {
        $this->publishContent(['et' => ['page.kodudjahinnad.plan_a_title' => 'Muudetud plaani nimi']]);
        $this->bootOverrides();
        app()->setLocale('et');

        $this->assertStringContainsString('Muudetud plaani nimi', $this->get('/')->getContent());
        $this->assertStringContainsString('Muudetud plaani nimi', $this->get('/kodud-ja-hinnad')->getContent());
    }

    public function test_the_floor_plan_section_texts_are_editable(): void
    {
        $registry = [];
        foreach (config('magnoolia_editable', []) as $definition) {
            foreach ($definition['groups'] ?? [] as $keys) {
                $registry += $keys;
            }
        }

        foreach (['floorplan.title', 'floorplan.subtitle', 'floorplan.eyebrow'] as $key) {
            $this->assertArrayHasKey($key, $registry, "The plan block's {$key} is not offered in Page Texts.");
        }
    }

    // ── The client has to be able to sign in ───────────────────────────

    public function test_the_client_admin_can_reach_the_login_panel(): void
    {
        // /login redirects to the Filament login, which is the only login form.
        // While canAccessPanel() excluded this role it could not authenticate at
        // all, so every screen Phase 36 built for it was unreachable.
        $client = \App\Models\User::factory()->create([
            'role' => 'magnoolia_client_admin', 'email_verified_at' => now(),
        ]);

        $this->assertTrue($client->canAccessPanel(\Filament\Facades\Filament::getPanel('admin')));
    }

    public function test_the_client_admin_reaches_the_control_centre_but_not_the_audit_log(): void
    {
        $client = \App\Models\User::factory()->create([
            'role' => 'magnoolia_client_admin', 'email_verified_at' => now(),
        ]);

        foreach (['/admin/magnoolia', '/admin/magnoolia/content', '/admin/magnoolia/media-slots',
                  '/admin/magnoolia/lists', '/admin/magnoolia/publish'] as $uri) {
            $this->actingAs($client)->get($uri)->assertOk("Client admin cannot open {$uri}");
        }

        // Advanced/system screens stay with the developer.
        $this->actingAs($client)->get('/admin/magnoolia/audit')->assertForbidden();
    }

    public function test_the_theme_resources_are_hidden_from_the_client_admin(): void
    {
        // Letting the role into the panel must not hand it the original template's
        // own content (apartments, blog posts, services…), which nobody asked it
        // to manage.
        $client = \App\Models\User::factory()->create([
            'role' => 'magnoolia_client_admin', 'email_verified_at' => now(),
        ]);
        $this->actingAs($client);

        foreach ([
            \App\Filament\Resources\FaqResource::class,
            \App\Filament\Resources\BlogPostResource::class,
            \App\Filament\Resources\ApartmentResource::class,
            \App\Filament\Resources\GalleryImageResource::class,
            \App\Filament\Resources\NavItemResource::class,
            \App\Filament\Resources\ServiceResource::class,
        ] as $resource) {
            $this->assertFalse($resource::canViewAny(), "{$resource} must be hidden from the client admin.");
        }

        // The Translation Manager, on the other hand, was deliberately opened to it.
        $this->assertTrue(\App\Filament\Pages\TranslationManager::canAccess());
    }

    public function test_the_create_command_can_make_a_client_admin(): void
    {
        $this->artisan('magnoolia:admin:create', [
            '--name' => 'Klient', '--email' => 'klient@example.test',
            '--password' => 'Salajane2026!', '--role' => 'magnoolia_client_admin',
        ])->assertSuccessful();

        $user = \App\Models\User::query()->where('email', 'klient@example.test')->first();

        $this->assertNotNull($user);
        $this->assertSame('magnoolia_client_admin', $user->role);
        $this->assertNotNull($user->email_verified_at, 'An unverified user is bounced by the verified middleware.');
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('Salajane2026!', $user->password));
    }

    public function test_the_create_command_rejects_an_unknown_role(): void
    {
        $this->artisan('magnoolia:admin:create', [
            '--name' => 'X', '--email' => 'x@example.test',
            '--password' => 'x', '--role' => 'superuser',
        ])->assertFailed();

        $this->assertDatabaseMissing('users', ['email' => 'x@example.test']);
    }

    /** @param array<string, array<string, string>> $content */
    private function publishContent(array $content): void
    {
        $this->fakePayload(['content' => $content]);
    }

    /** @param array<string, mixed> $settings */
    private function publishSettings(array $settings): void
    {
        $this->fakePayload(['settings' => $settings]);
    }

    /** @param array<string, mixed> $payload */
    private function fakePayload(array $payload): void
    {
        $repo = \Mockery::mock(\App\Services\Magnoolia\MagnooliaPublicDataRepository::class)->makePartial();
        $repo->shouldReceive('getPublicPayload')->andReturn($payload + ['content' => [], 'units' => [], 'settings' => []]);
        $repo->shouldReceive('getSettings')->andReturn($payload['settings'] ?? []);
        $repo->shouldReceive('getUnits')->andReturn([]);
        $this->app->instance(\App\Services\Magnoolia\MagnooliaPublicDataRepository::class, $repo);
    }
}
