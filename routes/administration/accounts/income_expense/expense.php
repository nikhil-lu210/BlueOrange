<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\Accounts\IncomeExpense\ExpenseController;

/* ==============================================
===============< Expense Routes >==============
===============================================*/
Route::controller(ExpenseController::class)->prefix('expense')->name('expense.')->group(function () {
    Route::get('/all', 'index')->name('index')->can('Expense Read');
});