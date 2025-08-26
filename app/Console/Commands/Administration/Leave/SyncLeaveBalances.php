<?php

namespace App\Console\Commands\Administration\Leave;

use Exception;
use App\Models\User;
use Illuminate\Console\Command;
use App\Services\Administration\Leave\LeaveValidationService;

class SyncLeaveBalances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave:sync-balances
                            {--user-id= : Sync balances for a specific user ID}
                            {--year= : Sync balances for a specific year (default: current year)}
                            {--all : Sync balances for all users}
                            {--force : Force sync even if no changes detected}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync leave balances between leave_alloweds, leave_availables, and leave_histories tables';

    protected $leaveValidationService;

    /**
     * Create a new command instance.
     */
    public function __construct(LeaveValidationService $leaveValidationService)
    {
        parent::__construct();
        $this->leaveValidationService = $leaveValidationService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->option('user-id');
        $year = $this->option('year') ?: now()->year;
        $syncAll = $this->option('all');
        $force = $this->option('force');

        $this->info("Starting leave balance synchronization for year: {$year}");

        try {
            if ($userId) {
                // Sync for specific user
                $user = User::find($userId);
                if (!$user) {
                    $this->error("User with ID {$userId} not found.");
                    return 1;
                }

                $this->syncUserBalances($user, $year, $force);
                $this->info("Successfully synced leave balances for user: {$user->name} (ID: {$user->id})");
            } elseif ($syncAll) {
                // Sync for all users
                $users = User::with(['leave_alloweds', 'leave_availables', 'leave_histories'])->get();
                $totalUsers = $users->count();
                $successCount = 0;
                $errorCount = 0;

                $this->info("Found {$totalUsers} users to sync.");

                $progressBar = $this->output->createProgressBar($totalUsers);
                $progressBar->start();

                /** @var User $user */
                foreach ($users as $user) {
                    try {
                        $this->syncUserBalances($user, $year, $force);
                        $successCount++;
                    } catch (Exception $e) {
                        $errorCount++;
                        $this->newLine();
                        $this->warn("Failed to sync user {$user->name} (ID: {$user->id}): {$e->getMessage()}");
                    }
                    $progressBar->advance();
                }

                $progressBar->finish();
                $this->newLine(2);
                $this->info("Sync completed. Success: {$successCount}, Errors: {$errorCount}");
            } else {
                $this->error("Please specify either --user-id or --all option.");
                return 1;
            }

            $this->info("Leave balance synchronization completed successfully!");
            return 0;

        } catch (Exception $e) {
            $this->error("Failed to sync leave balances: {$e->getMessage()}");
            return 1;
        }
    }

    /**
     * Sync leave balances for a specific user.
     *
     * @param User $user
     * @param int $year
     * @param bool $force
     * @return void
     */
    private function syncUserBalances(User $user, int $year, bool $force = false): void
    {
        $this->leaveValidationService->syncLeaveBalances($user, $year);

        if ($force) {
            $this->info("Force synced user {$user->name} (ID: {$user->id}) for year {$year}");
        }
    }
}
