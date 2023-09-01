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
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');

            // Permission Group
            include_once 'group/group.php';
        });