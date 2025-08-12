<?php

$daysInMonth = now()->daysInMonth;

return [
    // Submission window for team leaders (inclusive day-of-month bounds)
    'recognition_window' => [
        'start_day' => $daysInMonth - 5 + 1, // 5 days before month end
        'end_day'   => $daysInMonth,         // last day of month
    ],

    // Enforcement behavior during the submission window if recognitions are incomplete
    // none | soft | hard
    'enforcement' => env('ERS_ENFORCEMENT', 'none'),

    /**
     * Days in the month to send reminder notifications to team leaders (calculated as 5, 3, and 1 day before the end of the month)
     *
     * This way:
     * If month has 31 days, reminder days will be [26, 28, 30].
     * If month has 30 days, reminder days will be [25, 27, 29].
     * If month has 28 days (February non-leap), reminder days will be [23, 25, 27].
     */
    'reminder_days' => [
        $daysInMonth - 5,
        $daysInMonth - 3,
        $daysInMonth - 1,
    ],

    // Time of day to send reminders (24h format, server timezone)
    'reminder_time' => '09:00',
];
