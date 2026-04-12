<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\InstagramPost;
use App\Domain\Instagram\Jobs\PublishInstagramPostJob;
use Illuminate\Support\Facades\Log;

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
        $schedule->command('tenants:auto-post:instagram')
            ->everyMinute()
            ->timezone(config('app.timezone', 'America/Costa_Rica'))
            ->appendOutputTo(storage_path('logs/instagram_dispatch_due.log'));
        $schedule->command('tenants:sitemap:generate')->daily();
        $schedule->command('tenants:artisan send:reminders')
            ->hourly()
            ->timezone(config('app.timezone', 'America/Costa_Rica'))
            ->appendOutputTo(storage_path('logs/auto_reminder_run.log'));
        // Agenda la siguiente cita automáticamente cuando llega la hora de la cita actual.
        // Corre cada hora; withoutOverlapping(10) evita ejecuciones paralelas.
        $schedule->command('tenants:auto-book-run')
            ->hourly()
            ->withoutOverlapping(10)
            ->timezone(config('app.timezone', 'America/Costa_Rica'))
            ->appendOutputTo(storage_path('logs/auto_book_run.log'));
        $schedule->command('tenants:auto-book:expire-holds')->everyFiveMinutes()
            ->appendOutputTo(storage_path('logs/auto_book_expire_run.log'));
        $schedule->command('instagram:clean-zombies --minutes=15')
            ->everyTenMinutes()
            ->appendOutputTo(storage_path('logs/instagram_zombie_cleanup.log'));
        $schedule->command('instagram:reschedule-failed --window=4 --delay=30')
            ->hourly()
            ->appendOutputTo(storage_path('logs/instagram_reschedule_failed.log'));
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
