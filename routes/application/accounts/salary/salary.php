<?php

use Illuminate\Support\Facades\Route;


/* ==============================================
===============< Salary Routes >==============
===============================================*/
Route::prefix('salary')
    ->name('salary.')
    ->group(function () {
        // monthly
        include_once 'monthly.php';
});