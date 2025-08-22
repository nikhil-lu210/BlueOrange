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
        // $schedule->command('inspire')->hourly();

        // Schedule to auto calculate monthly salary for all users on every months 1st date at 8:00 AM
        // $schedule->command('salaries:calculate')->monthlyOn(1, '08:00')->timezone(config('app.timezone'));

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
