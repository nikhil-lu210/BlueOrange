<?php

namespace App\Models\Attendance\Mutators;

use Carbon\Carbon;

trait AttendanceMutators
{
    /**
     * Set the clock-in attribute.
     *
     * @param  mixed  $value
     * @return void
     */
    public function setClockInAttribute($value)
    {
        $this->attributes['clock_in'] = Carbon::parse($value)->setTimezone(config('app.timezone'));
    }

    /**
     * Set the clock-out attribute.
     *
     * @param  mixed  $value
     * @return void
     */
    public function setClockOutAttribute($value)
    {
        $this->attributes['clock_out'] = $value ? Carbon::parse($value)->setTimezone(config('app.timezone')) : null;
    }
}
