<?php

use App\Http\Controllers\Administration\Suggestion\SuggestionController;
use Illuminate\Support\Facades\Route;

/* ==============================================
===============< Suggestion Routes >================
===============================================*/
Route::prefix('suggestion')
    ->name('suggestion.')
    ->group(function () {
        Route::controller(SuggestionController::class)
            ->group(function () {
                Route::get('/all', 'index')->name('index')->can('Suggestion Everything');
                Route::get('/my/suggestion', 'my')->name('my')->can('Suggestion Read');
                Route::post('/store', 'store')->name('store')->can('Suggestion Create');
                Route::get('/show/{suggestion}', 'show')->name('show')->can('Suggestion Read');
                Route::get('/destroy/{suggestion}', 'destroy')->name('destroy')->can('Suggestion Delete');
            });
    });
