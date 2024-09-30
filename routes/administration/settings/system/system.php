<?php

use Illuminate\Support\Facades\Route;


/* ==============================================
===============< System Settings Routes >==============
===============================================*/
Route::prefix('system')
        ->name('system.')
        ->group(function () {
            // holiday
            include_once 'holiday/holiday.php';
        });