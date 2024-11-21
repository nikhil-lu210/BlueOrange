<?php

namespace App\Models\IncomeExpense\Relations;

use App\Models\IncomeExpense\Expense;
use App\Models\IncomeExpense\Income;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait IncomeExpenseCategoryRelations
{
    /**
     * Get the incomes associated with the IncomeExpenseCategory.
     */
    public function incomes(): HasMany
    {
        return $this->hasMany(Income::class);
    }
    
    /**
     * Get the expenses associated with the IncomeExpenseCategory.
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}