<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\DailyBreak\DailyBreakController;
use App\Http\Controllers\Administration\DailyBreak\BarCodeDailyBreakController;

/* ==============================================
===============< Daily Break Routes >==============
===============================================*/
Route::controller(DailyBreakController::class)->prefix('daily_break')->name('daily_break.')->group(function () {
    Route::get('/all', 'index')->name('index')->can('Daily Break Update');
    Route::get('/my', 'myDailyBreaks')->name('my')->can('Daily Break Read');
    Route::get('/start_stop', 'create')->name('create')->can('Daily Break Create');
    Route::post('/start', 'startBreak')->name('start')->can('Daily Break Create');
    Route::post('/stop', 'stopBreak')->name('stop')->can('Daily Break Create');
    
    Route::get('/show/{break}', 'show')->name('show')->can('Daily Break Read');
    Route::put('/update/{break}', 'update')->name('update')->can('Daily Break Update');

    Route::get('/destroy/{break}', 'destroy')->name('destroy')->can('Daily Break Delete');
    
    Route::get('/export', 'export')->name('export')->can('Daily Break Update');
});

Route::controller(BarCodeDailyBreakController::class)->prefix('daily_break/barcode')->name('daily_break.barcode.')->group(function () {
    Route::get('/scan', 'scanner')->name('scanner')->can('Daily Break Create');
    Route::post('/scan/{scanner_id}', 'scanBarCode')->name('scan')->can('Daily Break Create');
});