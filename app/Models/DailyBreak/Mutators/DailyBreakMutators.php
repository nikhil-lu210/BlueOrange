<?php

namespace App\Models\DailyBreak\Mutators;

use Carbon\Carbon;
use Stevebauman\Purify\Facades\Purify;

trait DailyBreakMutators
{
    /**
     * Set the break-in time attribute.
     *
     * This mutator parses the given value, converts it to a Carbon instance,
     * and sets it to the 'break_in_at' attribute, adjusting the timezone 
     * based on the application's configured timezone.
     *
     * @param  mixed  $value  The input value to be parsed as a date/time.
     * @return void
     */
    public function setClockInAttribute($value)
    {
        $this->attributes['break_in_at'] = Carbon::parse($value)->setTimezone(config('app.timezone'));
    }

    /**
     * Set the break-out time attribute.
     *
     * This mutator checks if a value is provided. If it is, the value is parsed,
     * converted to a Carbon instance, and set to the 'break_out_at' attribute 
     * with the application's timezone. If no value is provided, it sets the 
     * 'break_out_at' attribute to null.
     *
     * @param  mixed  $value  The input value to be parsed as a date/time.
     * @return void
     */
    public function setClockOutAttribute($value)
    {
        $this->attributes['break_out_at'] = $value ? Carbon::parse($value)->setTimezone(config('app.timezone')) : null;
    }

    /**
     * Mutator for note (Sanitize HTML before storing)
     */
    public function setNoteAttribute($value): void
    {
        $this->attributes['note'] = Purify::clean($value);
    }
}
