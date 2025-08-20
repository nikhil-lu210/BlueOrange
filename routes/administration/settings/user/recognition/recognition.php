<?php

use App\Http\Controllers\Administration\Settings\User\UserController;
use Illuminate\Support\Facades\Route;


/* ====================================================
===============< User Controller Routes >==============
=====================================================*/
Route::controller(UserController::class)
        ->prefix('show/{user}/recognition')
        ->name('recognition.')
        ->group(function () {
            Route::get('/', 'showRecognitions')->name('index')->can('Recognition Everything');
        });
