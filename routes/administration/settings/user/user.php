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
            Route::get('/all', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/show/{user}', 'show')->name('show');
            Route::get('/edit/{user}', 'edit')->name('edit');
            Route::post('/update/{user}', 'update')->name('update');
            Route::get('/destroy/{user}', 'destroy')->name('destroy');
        });