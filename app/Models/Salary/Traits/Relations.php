<?php

namespace App\Models\Salary\Traits;

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
}