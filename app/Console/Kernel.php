<?php

namespace App\Console;

use App\Console\Commands\MagnooliaAdminCreateCommand;
use App\Console\Commands\MagnooliaUnitsImportConfigCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        MagnooliaAdminCreateCommand::class,
        MagnooliaUnitsImportConfigCommand::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
