<?php

use Illuminate\Support\Facades\Route;

/* ==============================================
============< Settings Routes >============
===============================================*/
Route::prefix('settings')
        ->name('settings.')
        ->group(function () {
            // role
            include_once 'role/role.php';

            // permission
            include_once 'permission/permission.php';
        });