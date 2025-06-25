<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\Quiz\QuizQuestionController;

/* ==============================================
===============< Quiz Question Routes >==============
===============================================*/
Route::controller(QuizQuestionController::class)->prefix('question')->name('question.')->group(function () {
    Route::get('/all', 'index')->name('index')->can('Quiz Read');
    Route::get('/create', 'create')->name('create')->can('Quiz Create');
    Route::post('/store', 'store')->name('store')->can('Quiz Create');

    Route::get('/show/{question}', 'show')->name('show')->can('Quiz Read');
    Route::get('/edit/{question}', 'edit')->name('edit')->can('Quiz Update');
    Route::put('/update/{question}', 'update')->name('update')->can('Quiz Update');

    Route::get('/destroy/{question}', 'destroy')->name('destroy')->can('Quiz Delete');
});
