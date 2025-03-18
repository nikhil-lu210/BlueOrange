<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\Booking\DiningRoomBooking\DiningRoomBookingController;

/* ==============================================
===============< dining_room_booking Routes >==============
===============================================*/
Route::controller(DiningRoomBookingController::class)->prefix('dining_room')->name('dining_room.')->group(function () {
    Route::get('/', 'index')->name('index')->can('Dining Room Booking Read');

    Route::post('/create', 'bookOrCancel')->name('book')->can('Dining Room Booking Create');
});
