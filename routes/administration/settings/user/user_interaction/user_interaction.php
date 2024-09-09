<?php

use App\Http\Controllers\Administration\Settings\User\UserInteractionController;
use Illuminate\Support\Facades\Route;


/* ====================================================
===============< User Interation Routes >==============
=====================================================*/
Route::controller(UserInteractionController::class)
        ->prefix('show/{user:userid}/user_interaction')
        ->name('user_interaction.')
        ->group(function () {
            Route::get('/', 'index')->name('index')->can('User Interaction Read');
        });