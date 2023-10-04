<?php

use App\Http\Controllers\Administration\Settings\User\UserController;
use Illuminate\Support\Facades\Route;


/* ==============================================
===============< User Routes >==============
===============================================*/
Route::controller(UserController::class)
        ->prefix('user')
        ->name('user.')
        ->group(function () {
            Route::get('/all', 'index')->name('index')->can('User Read');
            Route::get('/create', 'create')->name('create')->can('User Create');
            Route::post('/store', 'store')->name('store')->can('User Create');
            Route::get('/show/{user}', 'show')->name('show')->can('User Read');
            Route::get('/edit/{user}', 'edit')->name('edit')->can('User Update');
            Route::post('/update/{user}', 'update')->name('update')->can('User Update');
            Route::get('/destroy/{user}', 'destroy')->name('destroy')->can('User Delete');
        });