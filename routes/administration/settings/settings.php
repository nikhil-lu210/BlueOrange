<?php

use Illuminate\Support\Facades\Route;

/* ==============================================
============< Settings Routes >============
===============================================*/
Route::prefix('settings')
        ->name('settings.')
        ->group(function () {
            // user
            include_once 'user/user.php';
            
            // rolepermission
            include_once 'rolepermission/rolepermission.php';
        });