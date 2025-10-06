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
                Route::get('/edit/{recognition}', 'edit')->name('edit')->can('Recognition Update');
                Route::put('/update/{recognition}', 'update')->name('update')->can('Recognition Update');
                Route::get('/destroy/{recognition}', 'destroy')->name('destroy')->can('Recognition Delete');
                Route::get('/analytics', 'analytics')->name('analytics')->can('Recognition Read');
                Route::get('/leaderboard', 'leaderboard')->name('leaderboard')->can('Recognition Read');
                Route::get('/export', 'export')->name('export')->can('Recognition Read');

                Route::get('/mark-recognize-as-read', 'markRecognizeAsRead')->name('notification.mark_as_read')->can('Recognition Read');
            });
    });
