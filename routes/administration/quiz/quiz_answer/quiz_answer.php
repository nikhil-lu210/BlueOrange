<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\Quiz\QuizAnswerController;

/* ==============================================
===============< Quiz Answer Routes >==============
===============================================*/
Route::controller(QuizAnswerController::class)->prefix('answer')->name('answer.')->group(function () {
    Route::get('/all', 'index')->name('index')->can('Quiz Read');
    Route::get('/create', 'create')->name('create')->can('Quiz Create');
    Route::post('/store', 'store')->name('store')->can('Quiz Create');

    Route::get('/show/{test}', 'show')->name('show')->can('Quiz Read');
    Route::get('/edit/{test}', 'edit')->name('edit')->can('Quiz Update');
    Route::put('/update/{test}', 'update')->name('update')->can('Quiz Update');

    Route::get('/destroy/{test}', 'destroy')->name('destroy')->can('Quiz Delete');
});
