<?php

use Illuminate\Support\Facades\Route;


/* ==============================================
===============< app Settings Routes >==============
===============================================*/
Route::prefix('app_setting')
        ->name('app_setting.')
        ->group(function () {
            // restriction
            include_once 'restriction/restriction.php';
            // translation
            include_once 'translation/translation.php';
        });