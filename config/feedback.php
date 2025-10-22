<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Feedback Types
    |--------------------------------------------------------------------------
    | These types categorize the kind of feedback a user can submit.
    */

    'types' => [
        'bug' => 'Bug Report',
        'feature' => 'Feature Suggestion',
        'ui' => 'UI/UX Issue',
        'performance' => 'Performance Problem',
        'other' => 'Other',
    ],

    /*
    |--------------------------------------------------------------------------
    | HR Modules
    |--------------------------------------------------------------------------
    | These modules help identify which part of the HR system the feedback is about.
    */

    'modules' => [
        'dashboard' => 'Dashboard',
        'attendance' => 'Attendance',
        'daily_break' => 'Daily Break',
        'work_update' => 'Daily Work Update',
        'task' => 'Task',
        'leave' => 'Leave',
        'announcement' => 'Announcement',
        'recognition' => 'Recognition',
        'learning_hub' => 'Learning Hub',
        'it_ticket' => 'IT Ticket',
        'booking' => 'Booking',
        'other' => 'Other',
    ],

];
