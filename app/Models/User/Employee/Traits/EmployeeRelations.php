<?php

namespace App\Models\User\Employee\Traits;

use App\Models\User;
use App\Models\Religion\Religion;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait EmployeeRelations
{
    /**
     * Get the user that owns the employee.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the religion that owns the employee.
     */
    public function religion(): BelongsTo
    {
        return $this->belongsTo(Religion::class);
    }
}
