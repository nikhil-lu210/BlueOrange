<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\Settings\System\Holiday\HolidayController;


/* ==============================================
===============< holiday Routes >==============
===============================================*/
Route::controller(HolidayController::class)
        ->prefix('holiday')
        ->name('holiday.')
        ->group(function () {
            Route::get('/', 'index')->name('index')->can('Holiday Read');
            Route::post('/store', 'store')->name('store')->can('Holiday Create');
        });