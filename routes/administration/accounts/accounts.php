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

        // income_expense
        include_once 'income_expense/income_expense.php';
});