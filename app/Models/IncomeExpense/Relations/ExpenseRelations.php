<?php

namespace App\Models\IncomeExpense\Relations;

use App\Models\User;
use App\Models\FileMedia\FileMedia;
use App\Models\IncomeExpense\Expense;
use App\Models\IncomeExpense\IncomeExpenseCategory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait ExpenseRelations
{
    /**
     * Get the creator for the Expense.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Get the category for the Expense.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(IncomeExpenseCategory::class, 'category_id');
    }

    /**
     * Get the files associated with the Expense.
     */
    public function files(): MorphMany
    {
        return $this->morphMany(FileMedia::class, 'fileable');
    }
}