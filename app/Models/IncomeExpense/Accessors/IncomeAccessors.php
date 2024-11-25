<?php

namespace App\Models\IncomeExpense\Accessors;

trait IncomeAccessors
{
    /**
     * Get the total overall income from the beginning to the current date.
     *
     * @return float
     */
    public static function total_overall_income(): float
    {
        return static::query()->whereNull('deleted_at')->sum('total');
    }
}
