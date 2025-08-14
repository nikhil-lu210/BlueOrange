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
        'Teamwork',
        'Innovation',
        'Leadership',
        'Problem Solving',
        'Creativity',
        'Punctuality',
        'Customer Focus',
    ],

    /*
    |--------------------------------------------------------------------------
    | Marks Range
    |--------------------------------------------------------------------------
    | This defines the minimum and maximum score a leader can give.
    | Can be numeric range or predefined steps.
    */
    'marks' => [
        'min' => 1,
        'max' => 20,
        'step' => 1 // step for slider/dropdown (1 = all integers)
    ],
];
