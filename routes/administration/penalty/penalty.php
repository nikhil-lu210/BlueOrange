<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\Penalty\PenaltyController;

/* ==============================================
===============< Penalty Routes >================
===============================================*/
Route::prefix('penalty')
    ->name('penalty.')
    ->group(function () {
        Route::controller(PenaltyController::class)
            ->group(function () {
                Route::get('/all', 'index')->name('index')->can('Penalty Everything');
                Route::get('/my', 'my')->name('my')->can('Penalty Read');
                Route::get('/create', 'create')->name('create')->can('Penalty Create');
                Route::post('/store', 'store')->name('store')->can('Penalty Create');
                Route::get('/show/{penalty}', 'show')->name('show')->can('Penalty Read');

                // AJAX endpoint for getting attendances
                Route::get('/get-attendances', 'getAttendances')->name('attendances.get')->can('Penalty Create');
            });
    });
