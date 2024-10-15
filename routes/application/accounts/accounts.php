<?php

use Illuminate\Support\Facades\Route;

/* ==============================================
===============< Accounts Routes >==============
===============================================*/
Route::prefix('accounts')
    ->name('accounts.')
    ->group(function () {
        // salary
        include_once 'salary/salary.php';
});