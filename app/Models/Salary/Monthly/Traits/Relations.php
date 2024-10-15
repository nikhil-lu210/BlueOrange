<?php

namespace App\Models\Salary\Monthly\Traits;

use App\Models\User;
use App\Models\Salary\Salary;
use App\Models\FileMedia\FileMedia;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Salary\Monthly\MonthlySalaryBreakdown;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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
     * Get the salary for the monthly_salary.
     */
    public function salary(): BelongsTo
    {
        return $this->belongsTo(Salary::class);
    }


    /**
     * Get the monthly_salary_breakdowns associated with the monthly_salary.
     */
    public function monthly_salary_breakdowns(): HasMany
    {
        return $this->hasMany(MonthlySalaryBreakdown::class);
    }
    
    /**
     * Get the payer for the monthly_salary.
     */
    public function payer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    /**
     * Get the files associated with the monthly salary.
     */
    public function files(): MorphMany
    {
        return $this->morphMany(FileMedia::class, 'fileable');
    }
}