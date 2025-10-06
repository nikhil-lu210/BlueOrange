<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear old log files to prevent them from growing indefinitely';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $logPath = storage_path('logs');

        // Get all files in the logs directory
        $logFiles = glob($logPath . '/*.log');

        foreach ($logFiles as $file) {
            if (file_exists($file)) {
                file_put_contents($file, ''); // Empty the file
                $this->info("Cleared: $file");
            }
        }

        $this->info('All log files have been cleared.');
        return 0;
    }

}
