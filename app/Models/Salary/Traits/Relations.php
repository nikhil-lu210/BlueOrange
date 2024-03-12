<?php

namespace App\Models\Salary\Traits;

use App\Models\User;
use App\Models\Salary\Monthly\MonthlySalary;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait Relations
{
    /**
     * Get the user for the salary.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the monthly_salaries associated with the salary.
     */
    public function monthly_salaries(): HasMany
    {
        return $this->hasMany(MonthlySalary::class);
    }
}