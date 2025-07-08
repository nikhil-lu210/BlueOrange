<?php

use App\Http\Controllers\Administration\Settings\User\UserController;
use Illuminate\Support\Facades\Route;


/* ====================================================
===============< User Controller Routes >==============
=====================================================*/
Route::controller(UserController::class)
        ->prefix('show/{user}/user_file')
        ->name('user_file.')
        ->group(function () {
            Route::get('/', 'showFiles')->name('index')->can('User Create');
            Route::post('/upload', 'uploadFile')->name('upload')->can('User Create');
        });
