<?php

namespace App\Console\Commands\Administration\Accounts\Salary;

use App\Models\User;
use Illuminate\Console\Command;
use App\Services\Administration\SalaryService\SalaryService;

class CalculateMonthlySalaries extends Command
{
    protected $signature = 'salaries:calculate';

    protected $description = 'Calculate monthly salaries for all users.';

    public function handle()
    {
        $users = User::select('id')->whereStatus('Active')->get();
        $salaryService = new SalaryService();

        foreach ($users as $user) {
            $salaryService->calculateMonthlySalary($user);
        }

        $this->info('Monthly salaries calculated successfully.');
    }
}
