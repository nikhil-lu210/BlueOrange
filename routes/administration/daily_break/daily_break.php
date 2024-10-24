<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\DailyBreak\DailyBreakController;

/* ==============================================
===============< Daily Break Routes >==============
===============================================*/
Route::controller(DailyBreakController::class)->prefix('daily_break')->name('daily_break.')->group(function () {
    Route::get('/all', 'index')->name('index');
    Route::get('/my', 'myDailyBreaks')->name('my');
    Route::get('/start_stop', 'create')->name('create');
    Route::post('/start', 'startBreak')->name('start');
    Route::post('/stop', 'stopBreak')->name('stop');
    
    Route::get('/show/{break}', 'show')->name('show');
    Route::put('/update/{break}', 'update')->name('update');
    
    Route::get('/export', 'export')->name('export');
});