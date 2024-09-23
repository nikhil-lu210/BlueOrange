<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\DailyBreak\DailyBreakController;

/* ==============================================
===============< Daily Break Routes >==============
===============================================*/
Route::controller(DailyBreakController::class)->prefix('daily_break')->name('daily_break.')->group(function () {
    Route::get('/all', 'index')->name('index');
    // Route::get('/my', 'myAttendances')->name('my');
    // Route::get('/create', 'create')->name('create');
    // Route::post('/store', 'store')->name('store');
    // Route::post('/clockin', 'clockIn')->name('clockin');
    // Route::post('/clockout', 'clockOut')->name('clockout');
    
    // Route::get('/show/{attendance}', 'show')->name('show');
    // Route::post('/update/{attendance}', 'update')->name('update');
    
    // Route::get('/export', 'export')->name('export');
});