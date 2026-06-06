<?php

namespace App\Console\Commands;

use App\Models\MagnooliaPublication;
use App\Models\MagnooliaUnit;
use Illuminate\Console\Command;

class MagnooliaPublicationStatusCommand extends Command
{
    protected $signature = 'magnoolia:publication:status';

    protected $description = 'Show current Magnoolia publication status and public data health';

    public function handle(): int
    {
        $this->line('');
        $this->line('<fg=yellow;options=bold>Magnoolia — Publication Status</>');
        $this->line(str_repeat('─', 50));

        // Active publication
        $active = MagnooliaPublication::query()
            ->where('status', 'active')
            ->orderByDesc('version')
            ->first();

        if ($active) {
            $this->info('Active publication:  YES');
            $this->line("Version:             {$active->version}");
            $this->line("Published at:        {$active->published_at}");
            $this->line("Published by:        {$active->published_by}");
            $this->line("Note:                {$active->publication_note}");

            $payload = $active->public_payload;
            $units   = $payload['units'] ?? [];
            $stage1  = collect($units)->where('stage', 1)->count();
            $stage2  = collect($units)->where('stage', 2)->count();

            $this->line('');
            $this->line('<fg=yellow>Units in public payload:</>');
            $this->line("  Total:             " . count($units));
            $this->line("  Stage I:           {$stage1}");
            $this->line("  Stage II:          {$stage2}");
        } else {
            $this->warn('Active publication:  NO');
            $this->line('');
            $this->warn('No active publication found in database.');
        }

        // Snapshot file
        $snapshotPath = storage_path('app/magnoolia/published/current.json');
        $snapshotExists = is_file($snapshotPath);

        $this->line('');
        $this->line('<fg=yellow>Snapshot file:</>');
        $this->line("  Path:              {$snapshotPath}");
        $this->line("  Exists:            " . ($snapshotExists ? 'YES' : 'NO'));

        if ($snapshotExists) {
            $decoded = json_decode((string) file_get_contents($snapshotPath), true);
            $fileUnits = $decoded['units'] ?? [];
            $fileVersion = $decoded['meta']['version'] ?? '?';
            $fileGeneratedAt = $decoded['meta']['generated_at'] ?? '?';

            $this->line("  Version in file:   {$fileVersion}");
            $this->line("  Generated at:      {$fileGeneratedAt}");
            $this->line("  Units in file:     " . count($fileUnits));
            $this->line("  Stage I in file:   " . collect($fileUnits)->where('stage', 1)->count());
            $this->line("  Stage II in file:  " . collect($fileUnits)->where('stage', 2)->count());

            $checksum = hash('sha256', (string) file_get_contents($snapshotPath));
            $this->line("  Checksum (sha256): " . substr($checksum, 0, 16) . '...');

            if (!$active) {
                $this->line('');
                $this->warn('DB fallback used: public site is serving from snapshot file, not DB publication.');
            }
        }

        // Draft DB state
        $dbUnits = MagnooliaUnit::query()->count();
        $this->line('');
        $this->line('<fg=yellow>Draft DB state:</>');
        $this->line("  Units in DB:       {$dbUnits}");

        if ($dbUnits > 0) {
            $stage1db = MagnooliaUnit::query()->where('stage', 1)->count();
            $stage2db = MagnooliaUnit::query()->where('stage', 2)->count();
            $this->line("  Stage I:           {$stage1db}");
            $this->line("  Stage II:          {$stage2db}");
        }

        // Summary
        $this->line('');
        $this->line(str_repeat('─', 50));

        if (!$active && !$snapshotExists && $dbUnits === 0) {
            $this->error('CRITICAL: No publication, no snapshot file, no units in DB.');
            $this->line('');
            $this->line('Run in order:');
            $this->line('  php artisan magnoolia:units:import-config --apply');
            $this->line('  Then publish from: /admin/magnoolia/publish');
            return self::FAILURE;
        }

        if (!$active && $snapshotExists) {
            $this->warn('STATUS: Serving from snapshot file (no DB publication). Admin edits will not be visible until you publish.');
            $this->line('');
            $this->line('To publish:');
            $this->line('  1. Ensure admin user exists: php artisan magnoolia:admin:create');
            $this->line('  2. Open: /admin/magnoolia/publish');
            return self::SUCCESS;
        }

        if ($active) {
            $this->info('STATUS: OK — active publication found.');
        }

        $this->line('');

        return self::SUCCESS;
    }
}
