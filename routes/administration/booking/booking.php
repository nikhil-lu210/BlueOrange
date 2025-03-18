<?php

use Illuminate\Support\Facades\Route;

/* ==============================================
===============< Booking Routes >==============
===============================================*/
Route::prefix('booking')
    ->name('booking.')
    ->group(function () {
        // dining_room_booking
        include_once 'dining_room_booking/dining_room_booking.php';
});
