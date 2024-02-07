<?php

namespace App\Models\User\Traits;

use App\Models\Attendance\Attendance;
use App\Models\WorkingShift\Shift;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait Relations
{    
    /**
     * Get the shifts associated with the user.
     */
    public function shifts(): HasMany
    {
        return $this->hasMany(Shift::class);
    }

    /**
     * Get the attendances associated with the user.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}