<?php

namespace App\Console\Commands;

use App\Models\NavItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/**
 * Put the main menu under admin control.
 *
 * WHY THIS EXISTS
 * ---------------
 * partials/header.blade.php falls back to a hardcoded list of eight items when
 * the nav_items table is empty — which it was. So the "Navigation Menu" screen
 * showed nothing and the menu could not be renamed, reordered or hidden from
 * admin at all.
 *
 * Worse, the fallback is all-or-nothing:
 *
 *     if ($navItems->isEmpty()) { $navItems = $fallbackNavItems; }
 *
 * Creating a single item by hand therefore discards the other seven and leaves a
 * one-link menu on the live site. This command writes all eight in one
 * transaction, with the labels the site is showing right now, so the menu is
 * unchanged the moment it becomes editable.
 *
 * Idempotent: matched on route_name, so re-running updates rather than
 * duplicates. Labels the client has since edited in admin are left alone unless
 * --force is given.
 */
class MagnooliaSeedNavCommand extends Command
{
    protected $signature = 'magnoolia:seed-nav
                            {--force : Reset labels to the current language-file wording}';

    protected $description = 'Import the main menu into the admin Navigation screen (idempotent)';

    /**
     * The menu as the site renders it today: route name => translation key.
     * Order here is the order on screen; sort_order is spaced by 10 so an item
     * can be inserted between two others without renumbering everything.
     */
    private const ITEMS = [
        'magnoolia.location'     => 'location',
        'magnoolia.galerii'      => 'gallery',
        'magnoolia.homes'        => 'homes',
        'magnoolia.arhitektuur'  => 'architecture',
        'magnoolia.sisedisain'   => 'interior',
        'magnoolia.construction' => 'building',
        'magnoolia.developer'    => 'developer',
        'magnoolia.contact'      => 'contact',
    ];

    public function handle(): int
    {
        // A menu is not something to half-write. If a route has gone missing the
        // seeded item would render as "#", so stop before touching anything.
        $missing = array_values(array_filter(array_keys(self::ITEMS), fn ($r) => ! Route::has($r)));
        if ($missing !== []) {
            $this->error('These routes do not exist: ' . implode(', ', $missing));
            $this->line('Nothing was written. Fix the route names in this command first.');

            return self::FAILURE;
        }

        $existing = NavItem::query()->count();
        $created = $updated = $skipped = 0;

        DB::transaction(function () use (&$created, &$updated, &$skipped) {
            $position = 0;

            foreach (self::ITEMS as $route => $key) {
                $position += 10;

                $labels = [
                    'et' => (string) __('magnoolia.nav.' . $key, [], 'et'),
                    'ru' => (string) __('magnoolia.nav.' . $key, [], 'ru'),
                    'en' => (string) __('magnoolia.nav.' . $key, [], 'en'),
                ];

                $item = NavItem::withTrashed()->firstWhere('route_name', $route);

                if ($item === null) {
                    NavItem::query()->create([
                        'label'      => $labels,
                        'route_name' => $route,
                        'url'        => null,
                        'sort_order' => $position,
                        'is_active'  => true,
                        'open_blank' => false,
                    ]);
                    $created++;

                    continue;
                }

                if ($item->trashed()) {
                    $item->restore();
                }

                // An existing row is the client's; only --force overwrites their
                // wording, their order and their show/hide choice.
                if (! $this->option('force')) {
                    $skipped++;

                    continue;
                }

                $item->fill([
                    'label'      => $labels,
                    'sort_order' => $position,
                    'is_active'  => true,
                    'open_blank' => false,
                ])->save();
                $updated++;
            }
        });

        $this->info(sprintf(
            'Menu items — created: %d, updated: %d, left as they were: %d (table had %d rows).',
            $created,
            $updated,
            $skipped,
            $existing
        ));

        $this->newLine();
        $this->line('The menu is now driven by the admin: Site Settings → Navigation Menu.');
        $this->line('It does NOT go through Publish — saving an item changes the live site at once.');

        return self::SUCCESS;
    }
}
