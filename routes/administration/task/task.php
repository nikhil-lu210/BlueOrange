<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\Task\TaskController;

/* ==============================================
===============< Task Routes >==============
===============================================*/
Route::controller(TaskController::class)->prefix('task')->name('task.')->group(function () {
    Route::get('/all', 'index')->name('index')->can('Task Read');
    Route::get('/my', 'my')->name('my')->can('Task Read');
    Route::get('/create', 'create')->name('create')->can('Task Create');
    Route::post('/store', 'store')->name('store')->can('Task Create');
    
    Route::get('/show/{task}', 'show')->name('show')->can('Task Read');
    Route::get('/edit/{task}', 'edit')->name('edit')->can('Task Update');
    Route::post('/update/{task}', 'update')->name('update')->can('Task Update');
    Route::get('/destroy/{task}', 'destroy')->name('destroy')->can('Task Delete');
});


// Route::controller(AnnouncementCommentController::class)
//         ->prefix('announcement/comment')
//         ->name('announcement.comment.')
//         ->group(function () {
//             Route::post('/store/{announcement}', 'store')->name('store')->can('Announcement Read');
//         });