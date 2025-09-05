<?php

namespace App\Models\WorkScheduleItem\Relations;

use App\Models\WorkSchedule\WorkSchedule;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait WorkScheduleItemRelations
{
    /**
     * Get the work schedule that owns the work schedule item.
     */
    public function workSchedule(): BelongsTo
    {
        return $this->belongsTo(WorkSchedule::class);
    }
}
