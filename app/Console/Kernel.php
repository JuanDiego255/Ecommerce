<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\InstagramPost;
use App\Domain\Instagram\Jobs\PublishInstagramPostJob;

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
        $schedule->call(function () {
            $posts = InstagramPost::where('status', 'scheduled')
                ->whereNotNull('scheduled_at')
                ->where('scheduled_at', '<=', now())
                ->orderBy('scheduled_at')
                ->limit(10)
                ->get();

            foreach ($posts as $post) {
                // Evita doble dispatch: cambia a publishing inmediatamente
                $post->update(['status' => 'publishing']);
                dispatch(new PublishInstagramPostJob($post->id));
            }
        })->everyMinute();
        $schedule->command('tenants:sitemap:generate')->daily();
        $schedule->command('tenants:artisan send:reminders')
            ->hourly()
            ->timezone(config('app.timezone', 'America/Costa_Rica'))
            ->appendOutputTo(storage_path('logs/auto_reminder_run.log'));
        /* $schedule->command('tenants:auto-book-run')->hourly()->withoutOverlapping(10)
            ->appendOutputTo(storage_path('logs/auto_book_run.log')); */
        $schedule->command('tenants:auto-book:expire-holds')->everyFiveMinutes()
            ->appendOutputTo(storage_path('logs/auto_book_expire_run.log'));
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
