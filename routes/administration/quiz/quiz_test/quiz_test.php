<?php

use App\Http\Controllers\Administration\Quiz\QuizTestController;
use Illuminate\Support\Facades\Route;

/* ==============================================
===============< Quiz Test Routes >==============
===============================================*/
Route::controller(QuizTestController::class)->prefix('test')->name('test.')->group(function () {
    Route::get('/all', 'index')->name('index')->can('Quiz Read');
    Route::get('/create', 'create')->name('create')->can('Quiz Create');
    Route::post('/store', 'store')->name('store')->can('Quiz Create');

    Route::get('/show/{test}', 'show')->name('show')->can('Quiz Read');
    Route::get('/edit/{test}', 'edit')->name('edit')->can('Quiz Update');
    Route::put('/update/{test}', 'update')->name('update')->can('Quiz Update');

    Route::delete('/destroy/{test}', 'destroy')->name('destroy')->can('Quiz Delete');
});
