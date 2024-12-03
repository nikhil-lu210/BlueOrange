<?php

namespace Database\Seeders\Accounts;

use Illuminate\Database\Seeder;
use App\Models\IncomeExpense\Income;
use App\Models\IncomeExpense\Expense;
use App\Models\IncomeExpense\IncomeExpenseCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class IncomeExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $totalCategory = IncomeExpenseCategory::count();
        if ($totalCategory < 7) {
            // Create 7 IncomeExpenseCategory records
            IncomeExpenseCategory::factory()->count(7)->create();
        }
        
        // Create 100 Income records
        Income::factory()->count(100)->create();

        // Create 100 Expense records
        Expense::factory()->count(100)->create();
    }
}
