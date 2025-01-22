<?php

namespace App\Models\IncomeExpense\Accessors;

use Carbon\Carbon;

trait IncomeAccessors
{
    /**
     * Get the total income from the beginning to the current date.
     *
     * @return float
     */
    public static function getTotalOverallIncome(): float
    {
        return static::query()->whereNull('deleted_at')->sum('total');
    }

    /**
     * Get the total income for the last month.
     *
     * @return float
     */
    public static function getLastMonthTotalIncome(): float
    {
        return static::query()
            ->whereMonth('date', Carbon::now()->subMonth()->month)
            ->whereYear('date', Carbon::now()->subMonth()->year)
            ->whereNull('deleted_at')
            ->sum('total');
    }

    /**
     * Get the total income for the current month.
     *
     * @return float
     */
    public static function getCurrentMonthTotalIncome(): float
    {
        return static::query()
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->whereNull('deleted_at')
            ->sum('total');
    }
}
