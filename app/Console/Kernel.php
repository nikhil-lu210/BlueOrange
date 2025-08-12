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
        $schedule->command('salaries:calculate')->monthlyOn(1, '08:00')->timezone(config('app.timezone'));

        // ERS: Send recognition reminders to team leaders during the submission window (1st, 3rd, 5th)
        $schedule->command('send:recognition-reminder')->dailyAt(config('ers.reminder_time', '09:00'))->timezone(config('app.timezone'));
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
