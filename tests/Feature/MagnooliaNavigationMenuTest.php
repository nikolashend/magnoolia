<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\NavItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The main menu, managed from admin.
 *
 * partials/header.blade.php falls back to a hardcoded list of eight items while
 * nav_items is empty, and the fallback is all-or-nothing:
 *
 *     if ($navItems->isEmpty()) { $navItems = $fallbackNavItems; }
 *
 * So creating one item by hand used to wipe the other seven off the live site.
 * magnoolia:seed-nav writes all eight together, with the wording the site is
 * already showing — the guarantee this file exists to protect.
 */
class MagnooliaNavigationMenuTest extends TestCase
{
    use RefreshDatabase;

    /** The menu labels the page renders, in order. */
    private function menu(string $uri = '/'): array
    {
        $html = $this->get($uri)->assertOk()->getContent();
        preg_match('~<ul class="main-menu__list".*?</ul>~su', $html, $nav);
        preg_match_all('~<a[^>]*>\s*([^<]{1,30})~su', $nav[0] ?? '', $m);

        return array_values(array_filter(array_map('trim', $m[1] ?? [])));
    }

    public function test_seeding_does_not_change_what_the_visitor_sees(): void
    {
        // The whole point: handing the menu to the admin must be invisible.
        $before = $this->menu();

        $this->artisan('magnoolia:seed-nav')->assertSuccessful();

        $this->assertSame($before, $this->menu());
        $this->assertCount(8, $before);
    }

    public function test_all_eight_items_are_created_together(): void
    {
        // One item alone would discard the fallback and leave a one-link menu.
        $this->artisan('magnoolia:seed-nav')->assertSuccessful();

        $this->assertSame(8, NavItem::query()->count());
        $this->assertSame(8, NavItem::query()->where('is_active', true)->count());
    }

    public function test_labels_are_seeded_for_all_three_languages(): void
    {
        $this->artisan('magnoolia:seed-nav')->assertSuccessful();

        foreach (NavItem::query()->get() as $item) {
            foreach (['et', 'ru', 'en'] as $locale) {
                $this->assertNotEmpty($item->label[$locale] ?? '', "{$item->route_name} has no {$locale} label.");
            }
        }
    }

    public function test_every_seeded_item_points_at_a_route_that_exists(): void
    {
        $this->artisan('magnoolia:seed-nav')->assertSuccessful();

        foreach (NavItem::query()->get() as $item) {
            $this->assertNotSame('#', $item->getHref(), "{$item->route_name} resolves to a dead link.");
        }
    }

    public function test_renaming_an_item_changes_the_menu(): void
    {
        $this->artisan('magnoolia:seed-nav')->assertSuccessful();
        $item = NavItem::query()->firstWhere('route_name', 'magnoolia.galerii');

        $item->update(['label' => ['et' => 'Pildid'] + $item->label]);

        $this->assertContains('Pildid', $this->menu());
        $this->assertNotContains('Galerii', $this->menu());
    }

    public function test_switching_an_item_off_removes_it_from_the_menu(): void
    {
        $this->artisan('magnoolia:seed-nav')->assertSuccessful();
        NavItem::query()->firstWhere('route_name', 'magnoolia.galerii')->update(['is_active' => false]);

        $menu = $this->menu();

        $this->assertNotContains('Galerii', $menu);
        $this->assertCount(7, $menu, 'Only the hidden item should disappear.');
    }

    public function test_sort_order_decides_the_position(): void
    {
        $this->artisan('magnoolia:seed-nav')->assertSuccessful();
        NavItem::query()->firstWhere('route_name', 'magnoolia.contact')->update(['sort_order' => 5]);

        $this->assertSame('Kontakt', $this->menu()[0]);
    }

    public function test_the_menu_follows_the_page_language(): void
    {
        $this->artisan('magnoolia:seed-nav')->assertSuccessful();

        $this->assertContains('Галерея', $this->menu('/ru'));
        $this->assertContains('Gallery', $this->menu('/en'));
    }

    public function test_running_it_again_does_not_duplicate_or_overwrite(): void
    {
        $this->artisan('magnoolia:seed-nav')->assertSuccessful();
        NavItem::query()->firstWhere('route_name', 'magnoolia.galerii')
            ->update(['label' => ['et' => 'Kliendi enda nimi', 'ru' => 'x', 'en' => 'x'], 'is_active' => false]);

        $this->artisan('magnoolia:seed-nav')->assertSuccessful();

        $item = NavItem::query()->firstWhere('route_name', 'magnoolia.galerii');
        $this->assertSame(8, NavItem::query()->count(), 'Re-running must not duplicate items.');
        $this->assertSame('Kliendi enda nimi', $item->label['et'], 'The client\'s wording must survive a re-run.');
        $this->assertFalse($item->is_active, 'A deliberately hidden item must stay hidden.');
    }

    public function test_force_resets_the_wording_to_the_language_files(): void
    {
        $this->artisan('magnoolia:seed-nav')->assertSuccessful();
        NavItem::query()->firstWhere('route_name', 'magnoolia.galerii')
            ->update(['label' => ['et' => 'Muudetud', 'ru' => 'x', 'en' => 'x']]);

        $this->artisan('magnoolia:seed-nav', ['--force' => true])->assertSuccessful();

        $this->assertSame(
            __('magnoolia.nav.gallery', [], 'et'),
            NavItem::query()->firstWhere('route_name', 'magnoolia.galerii')->label['et']
        );
    }

    public function test_the_navigation_screen_is_reachable_for_the_developer_role(): void
    {
        $admin = User::factory()->create(['role' => 'magnoolia_admin', 'email_verified_at' => now()]);

        $this->actingAs($admin);
        $this->assertTrue(\App\Filament\Resources\NavItemResource::canViewAny());
    }
}
