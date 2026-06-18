<?php

namespace App\Console\Commands;

use App\Models\MagnooliaAuditLog;
use App\Models\MagnooliaSetting;
use App\Models\MagnooliaUnit;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Phase 33 — canonical, idempotent seeder for the 19 Magnoolia homes.
 *
 * Source of truth for INITIAL values: config/magnoolia_units.php (the approved
 * hinnatabel facts + statuses). Running this twice does NOT duplicate homes and
 * does NOT overwrite admin-edited values unless --force is given.
 *
 * Safety: prices are seeded as INTERNAL price_cents but price_public is forced to
 * FALSE so no price is exposed publicly until the client explicitly confirms it
 * per-unit in the admin. This preserves the Phase-32 "Hind täpsustamisel" behaviour.
 */
class MagnooliaSeedUnitsCommand extends Command
{
    protected $signature = 'magnoolia:seed-units
        {--force : Overwrite existing units with canonical config values (otherwise existing rows are left untouched)}
        {--with-admin : Ensure a magnoolia_admin user exists}
        {--admin-email=admin@magnoolia.ee : Admin email when --with-admin is used}
        {--admin-password= : Admin password when creating the admin user (random if omitted)}';

    protected $description = 'Seed/refresh the 19 canonical Magnoolia homes into the admin draft DB (idempotent).';

    /** Approved fallback statuses (Phase 32/33). Config already carries these, but we assert them. */
    private const RESERVED = ['tee-1-3', 'tee-3-2', 'tee-7-2', 'tee-9-2'];
    private const SOLD = ['tee-5-1'];

    public function handle(): int
    {
        $config = (array) config('magnoolia_units', []);
        if (count($config) === 0) {
            $this->error('config/magnoolia_units.php is empty — nothing to seed.');
            return self::FAILURE;
        }

        // ---- integrity checks on the source ----
        $distribution = [];
        foreach ($config as $u) {
            $b = (int) Str::after((string) ($u['building'] ?? ''), 'Magnoolia tee ');
            $distribution[$b] = ($distribution[$b] ?? 0) + 1;
        }
        ksort($distribution);
        $expected = [1 => 3, 3 => 4, 5 => 3, 7 => 3, 9 => 3, 11 => 3];
        if ($distribution !== $expected) {
            $this->error('Config building distribution is not 3/4/3/3/3/3: ' . json_encode($distribution));
            return self::FAILURE;
        }
        if (collect($config)->pluck('id')->unique()->count() !== count($config)) {
            $this->error('Duplicate unit ids in config.');
            return self::FAILURE;
        }
        if (collect($config)->pluck('address')->unique()->count() !== count($config)) {
            $this->error('Duplicate addresses in config.');
            return self::FAILURE;
        }

        $force = (bool) $this->option('force');
        $created = 0;
        $updated = 0;
        $skipped = 0;

        foreach (array_values($config) as $index => $u) {
            $unitKey = (string) $u['id'];                                  // e.g. "tee-3-2"
            $building = (int) Str::after((string) $u['building'], 'Magnoolia tee ');
            $section = (int) Str::after((string) ($u['section'] ?? ''), '/');
            if ($section <= 0) {
                $section = $index + 1;
            }

            $status = $this->normalizeStatus($u, $unitKey);

            $row = [
                'slug' => Str::slug(str_replace('/', '-', (string) $u['address'])),
                'address' => (string) $u['address'],
                'building_number' => $building,
                'section_number' => $section,
                'stage' => (int) ($u['stage'] ?? 1),
                'status' => $status,
                'is_visible' => true,
                'public_page_visible' => true,
                // INTERNAL price retained; NEVER public until the client confirms per-unit.
                'price_cents' => isset($u['price']) && $u['price'] !== null ? (int) round(((float) $u['price']) * 100) : null,
                'price_public' => false,
                'rooms' => (int) ($u['rooms'] ?? 0),
                'net_area' => (float) ($u['net_area'] ?? 0),
                'terrace_area' => isset($u['terrace_area']) ? (float) $u['terrace_area'] : null,
                'balcony_area' => isset($u['balcony_area']) ? (float) $u['balcony_area'] : null,
                'storage_area' => isset($u['storage_area']) ? (float) $u['storage_area'] : null,
                'private_yard_area' => isset($u['private_yard_area']) ? (float) $u['private_yard_area'] : null,
                'parking_spaces' => (int) ($u['parking_spaces'] ?? $u['parking'] ?? 2),
                'completion_key' => (string) ($u['completion'] ?? ($building <= 3 ? 'kevad 2027' : 'kevad 2028')),
                'floorplan_floor_1' => (string) ($u['floorplan_1_pdf'] ?? ''),
                'floorplan_floor_2' => (string) ($u['floorplan_2_pdf'] ?? ''),
                'asendiplaan_key' => $u['masterplan_key'] ?? null,
                'plan_type' => $u['plan_type'] ?? null,
                'featured' => false,
                'sort_order' => $index + 1,
            ];

            $existing = MagnooliaUnit::query()->where('unit_key', $unitKey)->first();

            if (!$existing) {
                MagnooliaUnit::query()->create(array_merge(['unit_key' => $unitKey], $row, ['internal_note' => null, 'updated_by' => null]));
                $created++;
                MagnooliaAuditLog::query()->create([
                    'admin_user_id' => null,
                    'action' => 'unit_created_from_seed',
                    'entity_type' => 'unit',
                    'entity_id' => $unitKey,
                    'after_json' => json_encode($row, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    'reason' => 'Phase 33 canonical seed',
                    'created_at' => now(),
                ]);
            } elseif ($force) {
                $existing->fill($row);
                $existing->lock_version = ((int) $existing->lock_version) + 1;
                $existing->save();
                $updated++;
            } else {
                $skipped++;
            }
        }

        $this->ensureSettingsRow();

        if ($this->option('with-admin')) {
            $this->ensureAdmin();
        }

        // ---- report ----
        $total = MagnooliaUnit::query()->count();
        $byStatus = MagnooliaUnit::query()->selectRaw('status, count(*) c')->groupBy('status')->pluck('c', 'status')->all();
        $this->info("Seed complete. created={$created} updated={$updated} skipped(existing,no --force)={$skipped}");
        $this->line("Total units in DB: {$total}");
        $this->line('Status distribution: ' . json_encode($byStatus));

        if ($total !== 19) {
            $this->warn("Expected 19 units, found {$total}.");
        }

        return self::SUCCESS;
    }

    private function normalizeStatus(array $u, string $unitKey): string
    {
        // Prefer explicit config status; assert the approved reserved/sold list.
        $status = (string) ($u['status'] ?? 'available');
        if (in_array($unitKey, self::RESERVED, true)) {
            $status = 'reserved';
        } elseif (in_array($unitKey, self::SOLD, true)) {
            $status = 'sold';
        }
        return match ($status) {
            'tbc' => 'coming_soon',
            'available', 'reserved', 'sold', 'coming_soon' => $status,
            default => 'available',
        };
    }

    private function ensureSettingsRow(): void
    {
        if (MagnooliaSetting::query()->exists()) {
            return;
        }
        MagnooliaSetting::query()->create([
            'campaign_active' => false,
            'stage_1_completion' => 'kevad 2027',
            'stage_2_completion' => 'kevad 2028',
            'default_stage_1_price_public' => false,
            'default_stage_2_price_public' => false,
            'sales_contact_name' => 'Diana Tali',
            'sales_contact_phone' => '+37258164078',
            'sales_contact_email' => 'diana@estlanda.ee',
        ]);
        $this->line('Created default magnoolia_settings row.');
    }

    private function ensureAdmin(): void
    {
        $email = (string) $this->option('admin-email');
        if (User::query()->where('email', $email)->exists()) {
            $this->line("Admin already exists: {$email}");
            return;
        }
        $password = (string) ($this->option('admin-password') ?: Str::random(16));
        User::query()->create([
            'name' => 'Magnoolia Admin',
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'magnoolia_admin',
            'email_verified_at' => now(),
        ]);
        $this->info("Created magnoolia_admin: {$email}");
        if (!$this->option('admin-password')) {
            $this->warn("Generated password: {$password}  (store it securely; change after first login)");
        }
    }
}
