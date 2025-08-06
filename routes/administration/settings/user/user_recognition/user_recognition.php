<?php

use App\Http\Controllers\Administration\Settings\User\UserController;
use Illuminate\Support\Facades\Route;


/* ====================================================
===============< User Controller Routes >==============
=====================================================*/
Route::controller(UserController::class)
        ->prefix('show/{user}/user_recognition')
        ->name('user_recognition.')
        ->group(function () {
            Route::get('/', 'showRecognitions')->name('index')->can('User Create');
        });
