<?php

use Illuminate\Support\Facades\Route;

/* ==============================================
============< rolepermission Routes >============
===============================================*/
Route::prefix('rolepermission')
        ->name('rolepermission.')
        ->group(function () {
            // role
            include_once 'role/role.php';

            // permission
            include_once 'permission/permission.php';
        });