<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\DailyBreak\DailyBreakController;
use App\Http\Controllers\Administration\DailyBreak\BarCodeDailyBreakController;

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

Route::controller(BarCodeDailyBreakController::class)->prefix('daily_break/barcode')->name('daily_break.barcode.')->group(function () {
    Route::get('/scan', 'scanner')->name('scanner')->can('Daily Break Create');
    Route::post('/scan/{scanner_id}', 'scanBarCode')->name('scan')->can('Daily Break Create');
});