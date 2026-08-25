<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\MagnooliaList;
use App\Models\MagnooliaListItem;
use App\Models\MagnooliaMediaItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesMagnooliaTestUnits;

/**
 * Phase 36, Module C — editable lists.
 *
 * The property that matters most is the same one as for the image slots: a list
 * nobody has published must leave the page exactly as it ships. Everything else
 * here is about the editor doing what it claims — including that a change to a
 * list is actually publishable, which the checksum used to swallow.
 */
class MagnooliaPhase36ListsTest extends TestCase
{
    use RefreshDatabase, CreatesMagnooliaTestUnits;

    // ── Fallback: nothing published ────────────────────────────────────

    public function test_an_unpublished_list_reads_as_empty(): void
    {
        $this->assertSame([], mg_list('home.gallery_cards'));
        $this->assertSame([], mg_list('this.list.does.not.exist'));
    }

    public function test_pages_keep_their_shipped_content_with_no_lists_published(): void
    {
        $this->get('/')->assertOk()->assertSee('city-house__card__title', false);
        $this->get('/arhitektuur-ja-valisdisain')->assertOk()->assertSee('Külm panipaik', false);
        $this->get('/arendajast')->assertOk()->assertSee('Keila Park Residence', false);
        $this->get('/sisedisain')->assertOk()->assertSee('Schneider Sedna', false);
        $this->get('/kkk')->assertOk()->assertSee('FAQPage', false);
    }

    // ── Reading a published list ───────────────────────────────────────

    public function test_a_published_list_replaces_the_shipped_array(): void
    {
        $this->publishLists([
            'arendajast.projects' => [
                ['meta' => ['url' => 'https://example.test/'], 'et' => ['name' => 'Uus projekt']],
            ],
        ]);

        $rows = mg_list('arendajast.projects');

        $this->assertCount(1, $rows);
        $this->assertSame('Uus projekt', $rows[0]['name']);
        $this->assertSame('https://example.test/', $rows[0]['url']);
    }

    public function test_a_blank_translation_falls_back_to_estonian(): void
    {
        $this->publishLists([
            'arhitektuur.faq' => [
                ['et' => ['q' => 'ET küsimus', 'a' => 'ET vastus'], 'ru' => ['q' => 'RU вопрос']],
            ],
        ]);

        app()->setLocale('ru');
        $row = mg_list('arhitektuur.faq')[0];

        $this->assertSame('RU вопрос', $row['q'], 'A filled translation must win.');
        $this->assertSame('ET vastus', $row['a'], 'A blank one falls back to Estonian, not to nothing.');
    }

    public function test_an_image_is_returned_as_a_url_and_a_raw_path(): void
    {
        $this->publishLists([
            'home.gallery_cards' => [[
                'image' => 'assets/images/magnoolia/Cam001.0000.jpg',
                'image_alt_et' => 'Üldvaade',
                'et' => ['title' => 'Kaart'],
            ]],
        ]);

        $row = mg_list('home.gallery_cards')[0];

        $this->assertStringContainsString('Cam001.0000.jpg', $row['image']);
        $this->assertSame('assets/images/magnoolia/Cam001.0000.jpg', $row['image_path']);
        $this->assertSame('Üldvaade', $row['image_alt']);
    }

    public function test_a_published_card_keeps_its_responsive_variants(): void
    {
        // Publishing must not silently downgrade the home page from 1200w webp to
        // the full-size JPEG — that would be a regression nobody asked for.
        $this->publishLists([
            'home.gallery_cards' => [[
                'image' => 'assets/images/magnoolia/Cam001.0000.jpg',
                'et' => ['title' => 'Kaart', 'cap1' => '4 tuba'],
                'meta' => ['cap1_icon' => 'icon-bedroom'],
            ]],
        ]);

        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('Kaart', $html);
        $this->assertStringContainsString('Cam001.0000-1200w.webp', $html);
        $this->assertStringContainsString('icon-bedroom', $html);
    }

    // ── FAQ: one source for the page and for Google ────────────────────

    public function test_mg_faq_falls_back_to_the_lang_key(): void
    {
        $faqs = mg_faq('arhitektuur.faq', 'magnoolia.page.arhitektuur.faq_items');

        $this->assertNotEmpty($faqs);
        $this->assertArrayHasKey('q', $faqs[0]);
        $this->assertArrayHasKey('a', $faqs[0]);
    }

    public function test_mg_faq_reads_the_flat_q1_a1_lang_shape_too(): void
    {
        app('translator')->addLines([
            'tmp_faq.q1' => 'Küsimus 1', 'tmp_faq.a1' => 'Vastus 1',
            'tmp_faq.q2' => 'Küsimus 2', 'tmp_faq.a2' => 'Vastus 2',
        ], app()->getLocale());

        $faqs = mg_faq('no.such.list', 'tmp_faq');

        $this->assertCount(2, $faqs);
        $this->assertSame('Küsimus 2', $faqs[1]['q']);
    }

    public function test_the_kkk_page_shows_the_published_questions_in_both_places(): void
    {
        $this->publishLists([
            'kkk.faq' => [
                ['meta' => ['group' => '0'], 'et' => ['q' => 'Kas hind sisaldab panipaika?', 'a' => 'Jah, sisaldab.']],
            ],
        ]);

        $html = $this->get('/kkk')->assertOk()->getContent();

        // Once in the visible list, once inside the FAQPage JSON-LD — same wording.
        $this->assertSame(2, substr_count($html, 'Kas hind sisaldab panipaika?'),
            'The page and the structured data must carry the same question.');
        $this->assertStringNotContainsString('Kus Magnoolia asub?', $html,
            'A published list replaces the lang questions rather than adding to them.');
    }

    public function test_a_question_with_no_answer_is_left_out_of_the_page(): void
    {
        $this->publishLists([
            'kkk.faq' => [
                ['meta' => ['group' => '0'], 'et' => ['q' => 'Täidetud?', 'a' => 'Jah.']],
                ['meta' => ['group' => '0'], 'et' => ['q' => 'Pooleli küsimus']],
            ],
        ]);

        $html = $this->get('/kkk')->assertOk()->getContent();

        $this->assertStringContainsString('Täidetud?', $html);
        $this->assertStringNotContainsString('Pooleli küsimus', $html);
    }

    // ── Gallery order (Phase 35.1 item 8) ──────────────────────────────

    public function test_the_published_gallery_list_decides_order(): void
    {
        $this->publishLists([
            'galerii.items' => [
                ['image' => 'assets/images/magnoolia/Cam005.0000.jpg', 'meta' => ['cat' => 'valised'], 'et' => ['alt' => 'Teine']],
                ['image' => 'assets/images/magnoolia/Cam001.0000.jpg', 'meta' => ['cat' => 'interjer'], 'et' => ['alt' => 'Esimene']],
            ],
        ]);

        $gallery = mg_gallery();

        $this->assertCount(2, $gallery);
        $this->assertStringContainsString('Cam005.0000.jpg', $gallery[0]['src']);
        $this->assertSame('interjer', $gallery[1]['cat']);
    }

    // ── The editor ─────────────────────────────────────────────────────

    public function test_the_lists_index_renders(): void
    {
        $res = $this->actingAs($this->clientAdmin())->get('/admin/magnoolia/lists');

        $res->assertOk();
        $res->assertSee('Nimekirjad lehel', false);
        $res->assertSee('Avalehe pildikaardid', false);
        $res->assertSee('Välisruumi elemendid', false);
    }

    public function test_every_registered_list_opens_in_the_editor(): void
    {
        $admin = $this->clientAdmin();

        foreach (array_keys(config('magnoolia_lists', [])) as $key) {
            $this->actingAs($admin)->get('/admin/magnoolia/lists/' . $key)
                ->assertOk("List {$key} does not open.");
        }
    }

    public function test_an_unknown_list_cannot_be_opened_or_saved(): void
    {
        $admin = $this->clientAdmin();

        $this->actingAs($admin)->get('/admin/magnoolia/lists/made.up.list')->assertNotFound();
        $this->actingAs($admin)->put('/admin/magnoolia/lists/made.up.list', ['items' => []])->assertNotFound();
    }

    public function test_saving_stores_translations_meta_and_order(): void
    {
        $admin = $this->clientAdmin();

        $this->actingAs($admin)->put('/admin/magnoolia/lists/arhitektuur.faq', ['items' => [
            ['is_active' => 1, 'et' => ['q' => 'Teine', 'a' => 'B'], 'ru' => ['q' => 'Второй', 'a' => 'Б']],
            ['is_active' => 1, 'et' => ['q' => 'Esimene', 'a' => 'A']],
        ]])->assertRedirect();

        $items = MagnooliaList::query()->where('list_key', 'arhitektuur.faq')->first()->items;

        $this->assertCount(2, $items);
        // Position in the submitted array is the order — that is what dragging changes.
        $this->assertSame('Teine', $items[0]->payload_et['q']);
        $this->assertSame(0, $items[0]->sort_order);
        $this->assertSame('Второй', $items[0]->payload_ru['q']);
        $this->assertSame('Esimene', $items[1]->payload_et['q']);
    }

    public function test_dragging_a_row_reorders_it(): void
    {
        // What the browser actually sends after a drag: the rows keep the index
        // numbers they were rendered with, but arrive in their new screen order.
        // Order therefore has to come from arrival order, not from the index.
        $this->actingAs($this->clientAdmin())->put('/admin/magnoolia/lists/arhitektuur.faq', ['items' => [
            2 => ['is_active' => 1, 'et' => ['q' => 'Kolmandana renderdatud', 'a' => 'A']],
            0 => ['is_active' => 1, 'et' => ['q' => 'Esimesena renderdatud', 'a' => 'B']],
            1 => ['is_active' => 1, 'et' => ['q' => 'Teisena renderdatud', 'a' => 'C']],
        ]])->assertRedirect();

        $items = MagnooliaList::query()->where('list_key', 'arhitektuur.faq')->first()->items;

        $this->assertSame('Kolmandana renderdatud', $items[0]->payload_et['q']);
        $this->assertSame('Esimesena renderdatud', $items[1]->payload_et['q']);
        $this->assertSame([0, 1, 2], $items->pluck('sort_order')->all());
    }

    public function test_a_bullet_field_is_split_into_lines(): void
    {
        $this->actingAs($this->clientAdmin())
            ->put('/admin/magnoolia/lists/arhitektuur.exterior_elements', ['items' => [[
                'is_active' => 1,
                'et' => ['title' => 'Panipaik', 'list' => "Üks\nKaks\n\n  Kolm  "],
                'meta' => ['reverse' => '1'],
            ]]])->assertRedirect();

        $item = MagnooliaList::query()->where('list_key', 'arhitektuur.exterior_elements')->first()->items->first();

        $this->assertSame(['Üks', 'Kaks', 'Kolm'], $item->payload_et['list']);
        $this->assertTrue($item->meta['reverse']);
    }

    public function test_an_entry_left_completely_blank_is_not_stored(): void
    {
        $this->actingAs($this->clientAdmin())
            ->put('/admin/magnoolia/lists/arhitektuur.faq', ['items' => [
                ['is_active' => 1, 'et' => ['q' => 'Päris küsimus', 'a' => 'Vastus']],
                ['is_active' => 1, 'et' => ['q' => '', 'a' => '']],
            ]])->assertRedirect();

        $this->assertSame(1, MagnooliaList::query()->where('list_key', 'arhitektuur.faq')->first()->items()->count());
    }

    public function test_a_blank_spec_row_is_not_stored_despite_its_default_badge(): void
    {
        // A spec row always arrives carrying a badge default, so "has any value"
        // would keep every empty row the client added and then abandoned.
        $this->actingAs($this->clientAdmin())
            ->put('/admin/magnoolia/lists/sisedisain.spec.electrical', ['items' => [
                ['is_active' => 1, 'meta' => ['name' => 'Põrandakütte displei', 'type' => 'standard']],
                ['is_active' => 1, 'meta' => ['name' => '', 'type' => 'standard']],
            ]])->assertRedirect();

        $items = MagnooliaList::query()->where('list_key', 'sisedisain.spec.electrical')->first()->items;

        $this->assertCount(1, $items);
        $this->assertSame('Põrandakütte displei', $items[0]->meta['name']);
    }

    public function test_a_card_with_a_picture_but_no_title_yet_is_kept(): void
    {
        $media = MagnooliaMediaItem::query()->create([
            'title' => 'Render', 'category' => 'exterior', 'public_path' => 'assets/images/magnoolia/Cam001.0000.jpg',
        ]);

        $this->actingAs($this->clientAdmin())
            ->put('/admin/magnoolia/lists/home.gallery_cards', ['items' => [
                ['is_active' => 1, 'media_item_id' => $media->id, 'et' => ['title' => '']],
            ]])->assertRedirect();

        $this->assertSame(1, MagnooliaList::query()->where('list_key', 'home.gallery_cards')->first()->items()->count());
    }

    public function test_saving_does_not_change_the_public_page_before_publish(): void
    {
        $this->actingAs($this->clientAdmin())
            ->put('/admin/magnoolia/lists/arendajast.projects', ['items' => [
                ['is_active' => 1, 'et' => ['name' => 'Ainult mustandis'], 'meta' => ['url' => 'https://example.test/']],
            ]])->assertRedirect();

        $this->assertSame([], mg_list('arendajast.projects'), 'Draft edits must not leak to the site.');
        $this->get('/arendajast')->assertOk()->assertDontSee('Ainult mustandis', false);
    }

    public function test_an_entry_can_be_added_and_deleted(): void
    {
        $admin = $this->clientAdmin();

        $this->actingAs($admin)->post('/admin/magnoolia/lists/arhitektuur.faq/items')->assertRedirect();
        $list = MagnooliaList::query()->where('list_key', 'arhitektuur.faq')->first();
        $this->assertSame(1, $list->items()->count());

        $item = $list->items()->first();
        $this->actingAs($admin)->delete('/admin/magnoolia/lists/arhitektuur.faq/items/' . $item->id)->assertRedirect();
        $this->assertSame(0, $list->items()->count());
    }

    public function test_an_entry_of_another_list_cannot_be_deleted(): void
    {
        $admin = $this->clientAdmin();
        $victim = MagnooliaList::query()->create(['list_key' => 'kkk.faq', 'type' => 'faq', 'page' => 'kkk']);
        $item = MagnooliaListItem::query()->create(['list_id' => $victim->id, 'payload_et' => ['q' => 'x', 'a' => 'y']]);

        $this->actingAs($admin)
            ->delete('/admin/magnoolia/lists/arhitektuur.faq/items/' . $item->id)
            ->assertRedirect();

        $this->assertDatabaseHas('magnoolia_list_items', ['id' => $item->id]);
    }

    // ── Publishing ─────────────────────────────────────────────────────

    public function test_publishing_carries_the_lists_and_omits_empty_ones(): void
    {
        $list = MagnooliaList::query()->create(['list_key' => 'arhitektuur.faq', 'type' => 'faq', 'page' => 'arhitektuur']);
        $list->items()->create(['sort_order' => 0, 'is_active' => true, 'payload_et' => ['q' => 'K', 'a' => 'V']]);
        MagnooliaList::query()->create(['list_key' => 'kkk.faq', 'type' => 'faq', 'page' => 'kkk']);

        $payload = $this->publishAndReadPayload();

        $this->assertArrayHasKey('arhitektuur.faq', $payload['lists']);
        $this->assertArrayNotHasKey('kkk.faq', $payload['lists'], 'A list with no entries must not be published at all.');
        $this->assertSame('K', $payload['lists']['arhitektuur.faq'][0]['et']['q']);
    }

    public function test_a_hidden_entry_is_not_published(): void
    {
        $list = MagnooliaList::query()->create(['list_key' => 'arhitektuur.faq', 'type' => 'faq', 'page' => 'arhitektuur']);
        $list->items()->create(['sort_order' => 0, 'is_active' => true,  'payload_et' => ['q' => 'Nähtav', 'a' => 'V']]);
        $list->items()->create(['sort_order' => 1, 'is_active' => false, 'payload_et' => ['q' => 'Peidetud', 'a' => 'V']]);

        $payload = $this->publishAndReadPayload();

        $this->assertCount(1, $payload['lists']['arhitektuur.faq']);
        $this->assertSame('Nähtav', $payload['lists']['arhitektuur.faq'][0]['et']['q']);
    }

    public function test_a_list_only_change_is_publishable(): void
    {
        $this->create19TestUnits();
        // The publish checksum is built from a snapshot of everything editable. When
        // lists (or picture assignments) were missing from it, changing only those
        // looked identical to the previous version and publishing was refused with
        // "Avaldatud andmed ei erine praegusest versioonist" — the change could
        // never reach the site.
        $service = app(\App\Services\Magnoolia\MagnooliaPublicationService::class);
        $admin = $this->clientAdmin();

        $first = $service->publish($admin->id, 'baseline');
        $this->assertTrue($first['ok'], 'Baseline publish failed: ' . ($first['message'] ?? ''));

        $list = MagnooliaList::query()->create(['list_key' => 'arhitektuur.faq', 'type' => 'faq', 'page' => 'arhitektuur']);
        $list->items()->create(['sort_order' => 0, 'is_active' => true, 'payload_et' => ['q' => 'Uus', 'a' => 'Vastus']]);

        $second = $service->publish($admin->id, 'only a list changed');

        $this->assertTrue($second['ok'], 'A list-only change must be publishable: ' . ($second['message'] ?? ''));
    }

    public function test_a_slot_only_change_is_publishable(): void
    {
        $this->create19TestUnits();
        $service = app(\App\Services\Magnoolia\MagnooliaPublicationService::class);
        $admin = $this->clientAdmin();
        $service->publish($admin->id, 'baseline');

        $media = MagnooliaMediaItem::query()->create([
            'title' => 'Uus', 'category' => 'exterior', 'public_path' => 'assets/magnoolia/media/uus.webp',
        ]);
        \App\Models\MagnooliaMediaSlot::query()->create(['slot_key' => 'home.intro.image', 'media_item_id' => $media->id]);

        $result = $service->publish($admin->id, 'only a picture changed');

        $this->assertTrue($result['ok'], 'A picture-only change must be publishable: ' . ($result['message'] ?? ''));
    }

    public function test_rollback_restores_the_previous_entries(): void
    {
        $this->create19TestUnits();
        $service = app(\App\Services\Magnoolia\MagnooliaPublicationService::class);
        $admin = $this->clientAdmin();

        $list = MagnooliaList::query()->create(['list_key' => 'arhitektuur.faq', 'type' => 'faq', 'page' => 'arhitektuur']);
        $list->items()->create(['sort_order' => 0, 'is_active' => true, 'payload_et' => ['q' => 'Algne', 'a' => 'V']]);
        $good = $service->publish($admin->id, 'good version');
        $this->assertTrue($good['ok']);

        $list->items()->delete();
        $list->items()->create(['sort_order' => 0, 'is_active' => true, 'payload_et' => ['q' => 'Vale', 'a' => 'V']]);
        $this->assertTrue($service->publish($admin->id, 'bad version')['ok']);

        $this->assertTrue($service->rollback($admin->id, $good['publication']->id, 'undo')['ok']);

        $restored = MagnooliaList::query()->where('list_key', 'arhitektuur.faq')->first()->items;
        $this->assertCount(1, $restored);
        $this->assertSame('Algne', $restored[0]->payload_et['q']);
    }

    // ── Importing the shipped content ──────────────────────────────────

    public function test_the_seed_command_imports_the_shipped_lists_in_three_languages(): void
    {
        $this->artisan('magnoolia:seed-lists')->assertSuccessful();

        $cards = MagnooliaList::query()->where('list_key', 'home.gallery_cards')->first();
        $this->assertSame(12, $cards->items()->count());

        $first = $cards->items()->first();
        $this->assertNotEmpty($first->payload_et['title']);
        $this->assertNotEmpty($first->payload_ru['title'], 'Russian must be imported too, not left for retyping.');
        $this->assertNotNull($first->media_item_id, 'The card picture must become a media item that can be swapped.');

        $spec = MagnooliaList::query()->where('list_key', 'sisedisain.spec.electrical')->first();
        $this->assertTrue($spec->items->contains(fn ($i) => str_contains((string) ($i->meta['name'] ?? ''), 'Schneider Sedna')));
    }

    public function test_the_seed_command_does_not_overwrite_existing_entries(): void
    {
        $list = MagnooliaList::query()->create(['list_key' => 'arhitektuur.faq', 'type' => 'faq', 'page' => 'arhitektuur']);
        $list->items()->create(['sort_order' => 0, 'is_active' => true, 'payload_et' => ['q' => 'Käsitsi', 'a' => 'V']]);

        $this->artisan('magnoolia:seed-lists')->assertSuccessful();

        $this->assertSame(1, $list->items()->count());
        $this->assertSame('Käsitsi', $list->items()->first()->payload_et['q']);
    }

    public function test_seeding_twice_does_not_duplicate_media_entries(): void
    {
        $this->artisan('magnoolia:seed-lists')->assertSuccessful();
        $after = MagnooliaMediaItem::query()->count();

        $this->artisan('magnoolia:seed-lists', ['--force' => true])->assertSuccessful();

        $this->assertSame($after, MagnooliaMediaItem::query()->count());
    }

    public function test_the_seeded_lists_render_the_same_pages(): void
    {
        $this->artisan('magnoolia:seed-lists')->assertSuccessful();
        $this->publishAndReadPayload();

        // Seeded content is the shipped content, so the pages must look unchanged.
        $this->get('/')->assertOk()->assertSee('Üldvaade — välisfassaad', false);
        $this->get('/arhitektuur-ja-valisdisain')->assertOk()->assertSee('Külm panipaik igale kodule', false);
        $this->get('/sisedisain')->assertOk()->assertSee('Schneider Sedna', false);
        $this->get('/arendajast')->assertOk()->assertSee('Keila Park Residence', false);
        $this->get('/kkk')->assertOk()->assertSee('FAQPage', false);
    }

    // ── Gallery import ─────────────────────────────────────────────────

    public function test_the_gallery_import_creates_one_entry_per_picture_not_per_file(): void
    {
        // Each photo ships in two formats side by side (Cam001.jpg + Cam001.webp).
        // Importing per file doubled the media library and the gallery list. The
        // public page hid it by de-duplicating on render, so the only place it ever
        // showed was the admin screen.
        $this->artisan('magnoolia:seed-gallery')->assertSuccessful();

        $paths = MagnooliaMediaItem::query()->where('category', 'gallery')->pluck('public_path');
        $pictures = $paths->map(fn ($p) => preg_replace('~\.[a-z0-9]+$~i', '', (string) $p));

        $this->assertSame(
            $pictures->unique()->count(),
            $paths->count(),
            'The same picture must not be imported once per file format.'
        );
        $this->assertGreaterThan(0, $paths->count(), 'The fixture gallery folder should not be empty.');
    }

    public function test_the_gallery_import_prefers_the_more_efficient_format(): void
    {
        $this->artisan('magnoolia:seed-gallery')->assertSuccessful();

        $jpgWithWebpTwin = MagnooliaMediaItem::query()
            ->where('category', 'gallery')
            ->get()
            ->filter(function (MagnooliaMediaItem $m) {
                $stem = preg_replace('~\.[a-z0-9]+$~i', '', (string) $m->public_path);
                return str_ends_with((string) $m->public_path, '.jpg') && is_file(public_path($stem . '.webp'));
            });

        $this->assertCount(0, $jpgWithWebpTwin, 'A picture that also exists as webp must be imported as webp.');
    }

    public function test_running_the_gallery_import_twice_adds_nothing(): void
    {
        $this->artisan('magnoolia:seed-gallery')->assertSuccessful();
        $after = MagnooliaMediaItem::query()->where('category', 'gallery')->count();

        $this->artisan('magnoolia:seed-gallery', ['--dedupe' => true])->assertSuccessful();

        $this->assertSame($after, MagnooliaMediaItem::query()->where('category', 'gallery')->count());
    }

    public function test_dedupe_repoints_a_list_entry_instead_of_orphaning_it(): void
    {
        // Simulates a library seeded by the old command: the same picture twice.
        $keep = MagnooliaMediaItem::query()->create([
            'title' => 'Cam001', 'category' => 'gallery',
            'public_path' => 'assets/magnoolia/gallery/exterior/Cam001.webp',
        ]);
        $duplicate = MagnooliaMediaItem::query()->create([
            'title' => 'Cam001', 'category' => 'gallery',
            'public_path' => 'assets/magnoolia/gallery/exterior/Cam001.jpg',
            'alt_et' => 'Ainult siin olev alt-tekst',
        ]);

        $list = MagnooliaList::query()->create(['list_key' => 'galerii.items', 'type' => 'gallery', 'page' => 'galerii']);
        $entry = $list->items()->create(['sort_order' => 0, 'is_active' => true, 'media_item_id' => $duplicate->id]);

        $this->artisan('magnoolia:seed-gallery', ['--dedupe' => true])->assertSuccessful();

        $this->assertNull(MagnooliaMediaItem::query()->find($duplicate->id), 'The redundant row should be gone.');
        $this->assertNotNull($entry->fresh(), 'The list entry must survive, repointed — not be orphaned.');
        $this->assertSame($keep->id, $entry->fresh()->media_item_id);
        $this->assertSame('Ainult siin olev alt-tekst', $keep->fresh()->alt_et, 'Alt text must be carried over, not lost.');
    }

    // ── Registry sanity ────────────────────────────────────────────────

    public function test_every_registered_list_has_a_known_type_and_a_label(): void
    {
        $types = config('magnoolia_list_types', []);

        foreach (config('magnoolia_lists', []) as $key => $definition) {
            $this->assertArrayHasKey($definition['type'], $types, "List {$key} has an unknown type.");
            $this->assertNotEmpty($definition['label'] ?? '', "List {$key} needs a client-facing label.");
            $this->assertNotEmpty($definition['page'] ?? '', "List {$key} needs a page.");
        }
    }

    public function test_every_select_field_points_at_a_real_option_set(): void
    {
        $types = config('magnoolia_list_types', []);

        foreach ($types as $type => $definition) {
            if ($type === '_options') {
                continue;
            }
            foreach ($definition['fields'] as $field => $spec) {
                if (($spec['kind'] ?? '') === 'select') {
                    $this->assertNotEmpty(
                        MagnooliaList::options($spec['options'] ?? ''),
                        "Field {$type}.{$field} refers to an option set that does not exist."
                    );
                }
            }
        }
    }

    // ── Helpers ────────────────────────────────────────────────────────

    private function clientAdmin(): User
    {
        return User::factory()->create(['role' => 'magnoolia_client_admin', 'email_verified_at' => now()]);
    }

    /** Publish for real and return the payload that reaches the site. */
    private function publishAndReadPayload(): array
    {
        // Publishing runs the draft validator first, and an empty units table is a
        // blocker — so a publish test has to start from a valid draft.
        $this->create19TestUnits();

        $result = app(\App\Services\Magnoolia\MagnooliaPublicationService::class)
            ->publish($this->clientAdmin()->id, 'test');

        $this->assertTrue($result['ok'] ?? false, 'Publish failed: ' . ($result['message'] ?? ''));

        return app(\App\Services\Magnoolia\MagnooliaPublicDataRepository::class)->getPublicPayload();
    }

    /** @param array<string, array<int, array<string, mixed>>> $lists */
    private function publishLists(array $lists): void
    {
        $repo = \Mockery::mock(\App\Services\Magnoolia\MagnooliaPublicDataRepository::class)->makePartial();
        $repo->shouldReceive('getPublicPayload')->andReturn([
            'lists' => $lists, 'slots' => [], 'content' => [], 'units' => [], 'settings' => [], 'gallery' => [],
        ]);
        $repo->shouldReceive('getSettings')->andReturn([]);
        $repo->shouldReceive('getUnits')->andReturn([]);
        $this->app->instance(\App\Services\Magnoolia\MagnooliaPublicDataRepository::class, $repo);
    }
}
