<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\LearningHub\LearningHubController;
use App\Http\Controllers\Administration\LearningHub\LearningHubCommentController;


/* =================================================
===============< Learning Hub Routes >==============
==================================================*/
Route::controller(LearningHubController::class)->prefix('learning_hub')->name('learning_hub.')->group(function () {
    Route::get('/all', 'index')->name('index')->can('Learning Hub Everything');
    Route::get('/my', 'my')->name('my')->can('Learning Hub Read');
    Route::get('/create', 'create')->name('create')->can('Learning Hub Create');
    Route::post('/store', 'store')->name('store')->can('Learning Hub Create');

    Route::get('/show/{learning_topic}', 'show')->name('show')->can('Learning Hub Read');
    Route::get('/edit/{learning_topic}', 'edit')->name('edit')->can('Learning Hub Update');
    Route::post('/update/{learning_topic}', 'update')->name('update')->can('Learning Hub Update');
    Route::get('/destroy/{learning_topic}', 'destroy')->name('destroy')->can('Learning Hub Delete');
});
Route::controller(LearningHubCommentController::class)
    ->prefix('learning_hub/comment')
    ->name('learning_hub.comment.')
    ->group(function () {
        Route::post('/store/{learning_topic}', 'store')->name('store')->can('Learning Hub Read');
    });
