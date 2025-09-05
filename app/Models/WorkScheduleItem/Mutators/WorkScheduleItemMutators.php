<?php

namespace App\Models\WorkScheduleItem\Mutators;

use Carbon\Carbon;

trait WorkScheduleItemMutators
{
    /**
     * Set start time attribute
     */
    public function setStartTimeAttribute($value)
    {
        $this->attributes['start_time'] = $value;
    }

    /**
     * Set end time attribute
     */
    public function setEndTimeAttribute($value)
    {
        $this->attributes['end_time'] = $value;
    }
}
