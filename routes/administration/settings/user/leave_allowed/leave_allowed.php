<?php

use App\Http\Controllers\Administration\Leave\LeaveAllowedController;
use App\Http\Controllers\Administration\Settings\User\UserInteractionController;
use Illuminate\Support\Facades\Route;


/* ====================================================
===============< User's Allowed Leaves Routes >==============
=====================================================*/
Route::controller(LeaveAllowedController::class)
        ->prefix('show/{user:userid}/allowed_leaves')
        ->name('leave_allowed.')
        ->group(function () {
            Route::get('/', 'index')->name('index')->can('Leave Allowed Read');
        });