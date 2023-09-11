<?php

use Illuminate\Support\Facades\Route;

/* ==============================================
============< Administration Routes >============
===============================================*/
// Route::prefix('administration')
Route::prefix('')
        ->name('administration.')
        ->group(function () {
            // Dashboard
            include_once 'dashboard/dashboard.php';

            // settings
            include_once 'settings/settings.php';
        });