<?php

use App\Http\Controllers\Administration\Settings\Permission\PermissionGroupController;
use Illuminate\Support\Facades\Route;


/* ==============================================
===============< Permission Group Routes >==============
===============================================*/
Route::controller(PermissionGroupController::class)
        ->prefix('group')
        ->name('group.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
        });