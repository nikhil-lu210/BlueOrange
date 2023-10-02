<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\Settings\Role\RoleController;


/* ==============================================
===============< Role Routes >==============
===============================================*/
Route::controller(RoleController::class)
        ->prefix('role')
        ->name('role.')
        ->group(function () {
            Route::get('/all', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/show/{role}', 'show')->name('show');
            Route::get('/edit/{role}', 'edit')->name('edit');
            Route::post('/update', 'update')->name('update');
        });