<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\Dashboard\DashboardController;
use App\Http\Controllers\Administration\Dashboard\DashboardCalendarController;


/* ==============================================
===============< Dashboard Routes >==============
===============================================*/
Route::controller(DashboardController::class)->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', 'index')->name('index');
});

// Calendar API Routes
Route::controller(DashboardCalendarController::class)->prefix('dashboard/calendar')->name('dashboard.calendar.')->group(function () {
    Route::get('/events', 'getEvents')->name('events');
});
