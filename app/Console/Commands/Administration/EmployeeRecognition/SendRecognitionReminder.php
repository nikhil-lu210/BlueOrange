<?php

namespace App\Console\Commands\Administration\EmployeeRecognition;

use Illuminate\Console\Command;
use App\Jobs\SendRecognitionReminderJob;

class SendRecognitionReminder extends Command
{
    protected $signature = 'send:recognition-reminder {--month=} {--force}';

    protected $description = 'Send recognition reminder notifications to team leaders who have pending recognitions.';

    public function handle(): int
    {
        $month = $this->option('month');
        $force = (bool) $this->option('force');

        // Only run on configured reminder days unless forced
        $days = config('ers.reminder_days', [1, 3, 5]);
        $today = now()->day;
        if (!$force && !in_array((int)$today, array_map('intval', $days), true)) {
            $this->info('Skipping recognition reminders: today is not a configured reminder day. Use --force to override.');
            return Command::SUCCESS;
        }

        dispatch(new SendRecognitionReminderJob($month));
        $this->info('Recognition reminder job dispatched'.($month ? " for month {$month}" : '').($force ? ' (forced)' : '').'.');
        return Command::SUCCESS;
    }
}
