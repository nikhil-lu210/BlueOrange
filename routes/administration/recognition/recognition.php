<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\Recognition\RecognitionController;

/* ==============================================
===============< Recognition Routes >================
===============================================*/
Route::prefix('recognition')
    ->name('recognition.')
    ->group(function () {
        Route::controller(RecognitionController::class)
            ->group(function () {
                Route::get('/all', 'index')->name('index')->can('Recognition Everything');
                Route::get('/my', 'my')->name('my')->can('Recognition Read');
                Route::get('/create', 'create')->name('create')->can('Recognition Create');
                Route::post('/store', 'store')->name('store')->can('Recognition Create');
                Route::get('/show/{recognition}', 'show')->name('show')->can('Recognition Read');
                Route::get('/destroy/{recognition}', 'destroy')->name('destroy')->can('Recognition Delete');
            });
    });
