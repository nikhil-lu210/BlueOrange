<?php

use App\Http\Controllers\Administration\Task\TaskCommentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\Task\TaskController;
use App\Http\Controllers\Administration\Task\TaskHistoryController;

/* ==============================================
===============< Task Routes >==============
===============================================*/
Route::controller(TaskController::class)->prefix('task')->name('task.')->group(function () {
    Route::get('/all', 'index')->name('index')->can('Task Read');
    Route::get('/my', 'my')->name('my')->can('Task Read');
    Route::get('/create', 'create')->name('create')->can('Task Create');
    Route::post('/store', 'store')->name('store')->can('Task Create');
    
    Route::get('/show/{task}/{taskid}', 'show')->name('show')->can('Task Read');
    Route::get('/edit/{task}', 'edit')->name('edit')->can('Task Update');
    Route::post('/update/{task}', 'update')->name('update')->can('Task Update');
    Route::get('/destroy/{task}', 'destroy')->name('destroy')->can('Task Delete');
});


Route::controller(TaskCommentController::class)->prefix('task/comment')->name('task.comment.')->group(function () {
    Route::post('/store/{task}', 'store')->name('store')->can('Task Read');
});


Route::controller(TaskHistoryController::class)->prefix('task/history')->name('task.history.')->group(function () {
    Route::post('/start/{task}', 'start')->name('start')->can('Task Read');
    Route::post('/stop/{task}/{taskHistory}', 'stop')->name('stop')->can('Task Read');
});