<?php

namespace App\Console\Commands;

use App\Models\MagnooliaContentBlock;
use App\Models\MagnooliaMediaItem;
use App\Models\MagnooliaPublication;
use App\Models\MagnooliaUnit;
use Illuminate\Console\Command;

/**
 * Phase 33.3 — one-shot production readiness check. Run after the deploy seed
 * commands to confirm the client-facing admin will not be empty. Exits non-zero
 * if any gate fails, so it can be used in a deploy script.
 */
class MagnooliaVerifyReadinessCommand extends Command
{
    protected $signature = 'magnoolia:verify-readiness';

    protected $description = 'Verify Magnoolia admin/public data is populated and client-ready.';

    public function handle(): int
    {
        $units = MagnooliaUnit::count();
        $media = MagnooliaMediaItem::count();
        $gallery = MagnooliaMediaItem::where('category', 'gallery')->count();
        $content = MagnooliaContentBlock::count();
        $active = MagnooliaPublication::where('status', 'active')->orderByDesc('version')->first();
        $dist = MagnooliaUnit::selectRaw('status, count(*) c')->groupBy('status')->pluck('c', 'status')->all();

        $checks = [
            ['Units = 19', $units === 19, $units],
            ['Media >= 20 (not empty)', $media >= 20, $media],
            ['Gallery media >= 29', $gallery >= 29, $gallery],
            ['Content blocks >= 34', $content >= 34, $content],
            ['Active publication exists', (bool) $active, $active ? ('v' . $active->version) : 'NONE'],
            ['Status 14/4/1 (approved)', ($dist['available'] ?? 0) === 14 && ($dist['reserved'] ?? 0) === 4 && ($dist['sold'] ?? 0) === 1,
                ($dist['available'] ?? 0) . '/' . ($dist['reserved'] ?? 0) . '/' . ($dist['sold'] ?? 0)],
        ];

        $this->line('');
        $this->line('Magnoolia production readiness:');
        $ok = true;
        foreach ($checks as [$label, $pass, $value]) {
            $this->line(sprintf('  [%s] %-30s %s', $pass ? 'OK' : 'XX', $label, $value));
            $ok = $ok && $pass;
        }
        $this->line('');

        if ($ok) {
            $this->info('READY — admin and public data are populated. (Publish if status/active is missing.)');
            return self::SUCCESS;
        }
        $this->error('NOT READY — run the seed commands and/or publish. See magnoolia:seed-* and magnoolia:publish.');
        return self::FAILURE;
    }
}
