<?php

namespace App\Models\Salary\Monthly\Traits;

use App\Models\Salary\Monthly\MonthlySalary;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait MonthlySalaryBreakdownRelations
{
    /**
     * Get the MonthlySalary for the MonthlySalaryBreakdown.
     */
    public function monthly_salary(): BelongsTo
    {
        return $this->belongsTo(MonthlySalary::class);
    }
}