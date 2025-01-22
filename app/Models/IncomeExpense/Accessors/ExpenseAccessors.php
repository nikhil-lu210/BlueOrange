<?php

namespace App\Models\IncomeExpense\Accessors;

use Carbon\Carbon;

trait ExpenseAccessors
{
    /**
     * Get the total Expense from the beginning to the current date.
     *
     * @return float
     */
    public static function getTotalOverallExpense(): float
    {
        return static::query()->whereNull('deleted_at')->sum('total');
    }

    /**
     * Get the total Expense for the last month.
     *
     * @return float
     */
    public static function getLastMonthTotalExpense(): float
    {
        return static::query()
            ->whereMonth('date', Carbon::now()->subMonth()->month)
            ->whereYear('date', Carbon::now()->subMonth()->year)
            ->whereNull('deleted_at')
            ->sum('total');
    }

    /**
     * Get the total Expense for the current month.
     *
     * @return float
     */
    public static function getCurrentMonthTotalExpense(): float
    {
        return static::query()
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->whereNull('deleted_at')
            ->sum('total');
    }
}
