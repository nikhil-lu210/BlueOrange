<?php

use App\Http\Controllers\Administration\Settings\Permission\PermissionController;
use Illuminate\Support\Facades\Route;


/* ==============================================
===============< Permission Routes >==============
===============================================*/
Route::controller(PermissionController::class)
        ->prefix('permission')
        ->name('permission.')
        ->group(function () {
            Route::get('/all', 'index')->name('index')->can('Permission Read');
            Route::get('/create', 'create')->name('create')->can('Permission Create');
            Route::post('/store', 'store')->name('store')->can('Permission Create');
            Route::post('/update', 'update')->name('update')->can('Permission Update');
        });

/* ==============================================
============< Modules Routes >============
===============================================*/
Route::prefix('permission')
        ->name('permission.')
        ->group(function () {
            // permission_module
            include_once 'permission_module/permission_module.php';
        });