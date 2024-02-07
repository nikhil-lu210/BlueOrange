<?php

namespace App\Models\User\Traits;

use App\Models\Attendance\Attendance;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait Relations
{    
    /**
     * Get the attendances associated with the user.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}