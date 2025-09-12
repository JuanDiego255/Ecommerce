<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('tenants:sitemap:generate')->daily();
        $schedule->command('tenants:artisan send:reminders')
            ->hourly()
            ->timezone(config('app.timezone', 'America/Costa_Rica'));
        $schedule->command('tenants:auto-book-run')->hourly()->withoutOverlapping(10)
            ->appendOutputTo(storage_path('logs/auto_book_run.log'));
        $schedule->command('tenants:auto-book:expire-holds')->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
