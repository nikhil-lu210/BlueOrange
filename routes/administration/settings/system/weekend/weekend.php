<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\Settings\System\Weekend\WeekendController;

/* ==============================================
===============< Weekend Routes >================
===============================================*/
Route::controller(WeekendController::class)
        ->prefix('weekend')
        ->name('weekend.')
        ->group(function () {
            Route::get('/', 'index')->name('index')->can('Weekend Read');
            Route::put('/update/{weekend}', 'update')->name('update')->can('Weekend Update');
        });