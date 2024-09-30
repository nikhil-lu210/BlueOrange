<?php

namespace App\Models\Salary\Monthly\Traits;

use App\Models\Salary\Salary;
use App\Models\User;
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
     * Get the salary for the monthly_salary.
     */
    public function salary(): BelongsTo
    {
        return $this->belongsTo(Salary::class);
    }
}