<?php

namespace App\Console;

use App\Jobs\PublishPostsJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->job(PublishPostsJob::class)->everyMinute();

        $schedule->command('backup:clean')->daily()->at('01:00');

        $schedule->command('backup:run')->daily()->at('01:30');
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
