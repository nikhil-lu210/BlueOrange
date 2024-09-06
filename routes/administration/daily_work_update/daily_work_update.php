<?php

use App\Http\Controllers\Administration\DailyWorkUpdate\DailyWorkUpdateController;
use Illuminate\Support\Facades\Route;

/* ==============================================
===============< daily_work_update Routes >==============
===============================================*/
Route::controller(DailyWorkUpdateController::class)->prefix('daily_work_update')->name('daily_work_update.')->group(function () {
    Route::get('/all', 'index')->name('index')->can('Daily Work Update Delete');
    Route::get('/my', 'my')->name('my')->can('Daily Work Update Read');
    Route::get('/create', 'create')->name('create')->can('Daily Work Update Create');
    Route::post('/store', 'store')->name('store')->can('Daily Work Update Create');
    Route::get('/edit/{daily_work_update}', 'edit')->name('edit')->can('Daily Work Update Update');
    Route::post('/update/{daily_work_update}', 'update')->name('update')->can('Daily Work Update Update');
    Route::get('/destroy/{daily_work_update}', 'destroy')->name('destroy')->can('Daily Work Update Delete');
});