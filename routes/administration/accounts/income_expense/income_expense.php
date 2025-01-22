<?php

use Illuminate\Support\Facades\Route;

/* ==============================================
===============< income_expense Routes >==============
===============================================*/
Route::prefix('income_expense')
    ->name('income_expense.')
    ->group(function () {
        // statistics
        include_once 'statistics.php';
        
        // category
        include_once 'category.php';

        // income
        include_once 'income.php';
        
        // expense
        include_once 'expense.php';
});