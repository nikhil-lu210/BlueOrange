<?php

use Illuminate\Support\Facades\Route;


/* ==============================================
===============< System Settings Routes >==============
===============================================*/
Route::prefix('system')
        ->name('system.')
        ->group(function () {
            // weekend
            include_once 'weekend/weekend.php';
            // holiday
            include_once 'holiday/holiday.php';
            // app_setting
            include_once 'app_setting/app_setting.php';
        });