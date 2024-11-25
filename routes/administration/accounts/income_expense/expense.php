<?php

use App\Http\Controllers\Administration\Accounts\IncomeExpense\ExpenseController;
use Illuminate\Support\Facades\Route;

/* ==============================================
===============< Expense Routes >==============
===============================================*/
Route::controller(ExpenseController::class)->prefix('expense')->name('expense.')->group(function () {
    Route::get('/all', 'index')->name('index')->can('Expense Read');
    Route::get('/create', 'create')->name('create')->can('Expense Create');

    Route::post('/store', 'store')->name('store')->can('Expense Create');

    Route::get('/all/show/{expense}', 'show')->name('show')->can('Expense Read');
    Route::get('/all/edit/{expense}', 'edit')->name('edit')->can('Expense Update');

    Route::put('/update/{expense}', 'update')->name('update')->can('Expense Update');
    Route::get('/destroy/{expense}', 'destroy')->name('destroy')->can('Expense Delete');
});