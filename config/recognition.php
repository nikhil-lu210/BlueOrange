<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Recognition Categories
    |--------------------------------------------------------------------------
    | These are the available recognition categories.
    | You can add/remove categories here without touching the database.
    */
    'categories' => [
        'Behavior',
        'Appreciation',
        'Leadership',
        'Loyalty',
        'Dedication',
        'Teamwork',
        'Innovation'
    ],

    // Recognition reminder threshold
    'reminder_days' => 15,

    /*
    |--------------------------------------------------------------------------
    | Marks Range
    |--------------------------------------------------------------------------
    | This defines the minimum and maximum score a leader can give.
    | Can be numeric range or predefined steps.
    */
    'marks' => [
        'min' => 100,
        'max' => 1000,
        'step' => 1 // step for slider/dropdown (1 = all integers)
    ],
];
