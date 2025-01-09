<?php

use App\Http\Controllers\Administration\Settings\User\UserInteractionController;
use Illuminate\Support\Facades\Route;


/* ====================================================
===============< User Interation Routes >==============
=====================================================*/
Route::controller(UserInteractionController::class)
        ->prefix('show/{user}/user_interaction')
        ->name('user_interaction.')
        ->group(function () {
            Route::get('/', 'index')->name('index')->can('User Interaction Read');

            Route::post('/update_team_leader', 'updateTeamLeader')->name('update_team_leader')->can('User Interaction Create');
            Route::post('/add_users', 'addUsers')->name('add_users')->can('User Interaction Create');
            Route::post('/remove_user', 'removeUser')->name('remove_user')->can('User Interaction Delete');
        });