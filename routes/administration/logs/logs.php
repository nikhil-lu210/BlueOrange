<?php

use Illuminate\Support\Facades\Route;

/* ==============================================
============< Logs Routes >============
===============================================*/
Route::prefix('logs')
        ->name('logs.')
        ->group(function () {
            // login_logout_history
            include_once 'login_logout_history/login_logout_history.php';
        });