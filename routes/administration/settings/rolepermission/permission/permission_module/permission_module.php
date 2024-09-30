<?php

use App\Http\Controllers\Administration\Settings\Permission\PermissionModuleController;
use Illuminate\Support\Facades\Route;


/* ==============================================
===============< Permission Module Routes >==============
===============================================*/
Route::controller(PermissionModuleController::class)
        ->prefix('module')
        ->name('module.')
        ->group(function () {
            Route::post('/store', 'store')->name('store')->can('Permission Create');
            Route::get('/show/{module}', 'show')->name('show')->can('Permission Read');
            Route::get('/edit/{module}', 'edit')->name('edit')->can('Permission Update');
        });