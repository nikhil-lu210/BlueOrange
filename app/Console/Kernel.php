<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Schedule to auto calculate monthly salary for all users on the 1st date at 8:00 AM
        // $schedule->command('salaries:calculate')
        //          ->monthlyOn(1, '08:00')
        //          ->timezone(config('app.timezone'))
        //          ->withoutOverlapping()
        //          ->appendOutputTo(storage_path('logs/salaries-calculate.log'));

        // Schedule to send task notifications daily at 2:00 AM
        $schedule->command('send:task-notifications --no-ansi --quiet')
                 ->dailyAt('02:00')
                 ->timezone(config('app.timezone'))
                 ->withoutOverlapping()
                 ->appendOutputTo(storage_path('logs/send-task-notifications.log'));

        // Schedule to send birthday emails daily at 4:00 AM
        $schedule->command('send:birthday-emails --no-ansi --quiet')
                 ->dailyAt('04:00')
                 ->timezone(config('app.timezone'))
                 ->withoutOverlapping()
                 ->appendOutputTo(storage_path('logs/send-birthday-emails.log'));

        // Schedule to send task alert notifications every day at 3:00 AM
        $schedule->command('send:task-alerts --no-ansi --quiet')
                 ->dailyAt('03:00')
                 ->timezone(config('app.timezone'))
                 ->withoutOverlapping()
                 ->appendOutputTo(storage_path('logs/send-task-alerts.log'));

        // Schedule to process queue every 5 minutes (replaces --once cron)
        $schedule->command('queue:work --once --tries=3')
                 ->everyFiveMinutes()
                 ->withoutOverlapping()
                 ->appendOutputTo(storage_path('logs/queue-cron.log'));

        // Clear logs every week on Friday at 6:00 AM
        $schedule->command('clear:logs --no-ansi --quiet')
                 ->weeklyOn(5, '06:00')
                 ->timezone(config('app.timezone'))
                 ->withoutOverlapping()
                 ->appendOutputTo(storage_path('logs/clear-logs.log'));

        // Optional: delete old cache sessions daily at 7:00 AM
        $schedule->command('cache:clear')
                 ->dailyAt('07:00')
                 ->timezone(config('app.timezone'))
                 ->withoutOverlapping()
                 ->appendOutputTo(storage_path('logs/cache-clear.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
