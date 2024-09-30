<?php

namespace App\Rules\Administration\Attendance;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ClockInDateMatch implements ValidationRule
{
    protected $attendance;

    public function __construct($attendance)
    {
        $this->attendance = $attendance;
    }

    public function validate($attribute, $value, Closure $fail): void
    {
        // Validate that the date part of the given value matches the clock_in_date
        if (date('Y-m-d', strtotime($value)) !== $this->attendance->clock_in_date) {
            $fail("The {$attribute} date must match the Clock In Date from the attendance record.");
        }
    }
}
