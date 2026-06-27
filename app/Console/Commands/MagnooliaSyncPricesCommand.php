<?php

namespace App\Console\Commands;

use App\Models\MagnooliaAuditLog;
use App\Models\MagnooliaUnit;
use Illuminate\Console\Command;

/**
 * Phase 35 — sync per-unit sale price + public-price visibility from
 * config/magnoolia_units.php into the DB draft, WITHOUT touching status, areas
 * or any other admin-edited field. Use this to publish the sale prices on an
 * existing database (seed-units --force would also reset statuses).
 *
 * After running, publish so the active publication reflects the prices:
 *   php artisan magnoolia:sync-prices
 *   php artisan magnoolia:publish --note="Publish sale prices"
 */
class MagnooliaSyncPricesCommand extends Command
{
    protected $signature = 'magnoolia:sync-prices';

    protected $description = 'Sync sale price + price_public from config into the DB (price fields only, no status changes).';

    public function handle(): int
    {
        $cfg = collect(config('magnoolia_units', []))->keyBy('id');
        if ($cfg->isEmpty()) {
            $this->error('config/magnoolia_units.php is empty — nothing to sync.');
            return self::FAILURE;
        }

        $updated = 0;
        foreach (MagnooliaUnit::query()->get() as $unit) {
            $c = $cfg->get($unit->unit_key);
            if (!$c) {
                continue;
            }
            $newCents = isset($c['price']) && $c['price'] !== null ? (int) round(((float) $c['price']) * 100) : null;
            $newPublic = (bool) ($c['price_public'] ?? false);

            if ((int) $unit->price_cents === (int) $newCents && (bool) $unit->price_public === $newPublic) {
                continue; // already in sync
            }

            $before = ['price_cents' => $unit->price_cents, 'price_public' => $unit->price_public];
            $unit->price_cents = $newCents;
            $unit->price_public = $newPublic;
            $unit->lock_version = ((int) $unit->lock_version) + 1;
            $unit->save();

            MagnooliaAuditLog::query()->create([
                'admin_user_id' => null,
                'action' => 'unit_price_synced',
                'entity_type' => 'unit',
                'entity_id' => $unit->unit_key,
                'before_json' => json_encode($before, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'after_json' => json_encode(['price_cents' => $newCents, 'price_public' => $newPublic], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'reason' => 'Phase 35 price sync from config',
                'created_at' => now(),
            ]);
            $updated++;
        }

        $public = MagnooliaUnit::query()->where('price_public', true)->count();
        $this->info("Prices synced. updated={$updated}; units with public price = {$public}.");
        $this->line('Next: php artisan magnoolia:publish --note="Publish sale prices"');
        return self::SUCCESS;
    }
}
