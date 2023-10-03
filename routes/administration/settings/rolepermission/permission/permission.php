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
            Route::get('/all', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::post('/update', 'update')->name('update');
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