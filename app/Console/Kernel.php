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

        /**
         * Task 1: Send task notifications
         * Runs daily at 02:00 AM
         * withoutOverlapping prevents a new instance if the previous is still running
         * Output is logged to send-task-notifications.log
         */
        $schedule->command('send:task-notifications --no-ansi --quiet')
                ->dailyAt('02:00')
                ->withoutOverlapping()
                ->appendOutputTo(storage_path('logs/send-task-notifications.log'));

        /**
         * Task 2: Send birthday emails
         * Runs daily at 03:00 AM
         * Staggered after task notifications and alerts to prevent hitting entry process limit
         * Output logged to send-birthday-emails.log
         */
        $schedule->command('send:birthday-emails --no-ansi --quiet')
                ->dailyAt('03:00')
                ->withoutOverlapping()
                ->appendOutputTo(storage_path('logs/send-birthday-emails.log'));

        /**
         * Task 3: Process queue jobs
         * Runs every 10 minutes (reduced from 5 minutes to reduce concurrent PHP processes)
         * '--once' ensures the queue worker exits after one job, preventing long-running processes
         * withoutOverlapping prevents multiple simultaneous workers
         * Output logged to queue-cron.log
         */
        $schedule->command('queue:work --tries=3 --max-jobs=10 --stop-when-empty')
                ->everyFiveMinutes()
                ->withoutOverlapping()
                ->appendOutputTo(storage_path('logs/queue-cron.log'));

        /**
         * Task 4: Clear logs
         * Runs weekly on Friday at 06:00 AM
         * withoutOverlapping prevents overlap with previous clear logs run
         * Output logged to clear-logs.log
         */
        $schedule->command('clear:logs --no-ansi --quiet')
                ->weeklyOn(5, '06:00')
                ->withoutOverlapping()
                ->appendOutputTo(storage_path('logs/clear-logs.log'));

        /**
         * Task 5: Database backup
         * Runs daily at 04:00 PM (16:00)
         * withoutOverlapping prevents multiple backup processes
         * Output logged to database-backup.log
         */
        $schedule->command('backup:database-daily --no-ansi --quiet')
                ->dailyAt('16:00')
                ->withoutOverlapping()
                ->appendOutputTo(storage_path('logs/database-backup.log'));

        /**
         * Task 6: Clear cache (optional)
         * Runs daily at 07:00 AM
         * Staggered after all heavy tasks to avoid peak load
         * withoutOverlapping ensures no conflicts if a previous cache clear is still running
         * Output logged to cache-clear.log
         */
        $schedule->command('cache:clear')
                ->dailyAt('07:00')
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
