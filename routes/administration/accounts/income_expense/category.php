<?php

use App\Http\Controllers\Administration\Accounts\IncomeExpense\IncomeExpenseCategoryController;
use Illuminate\Support\Facades\Route;

/* ==============================================
===============< Category Routes >==============
===============================================*/
Route::controller(IncomeExpenseCategoryController::class)->prefix('category')->name('category.')->group(function () {
    Route::get('/all', 'index')->name('index');
    Route::get('/show/{category}', 'show')->name('show');

    Route::post('/store', 'store')->name('store');
    Route::put('/update/{category}', 'update')->name('update');
    
    Route::get('/destroy/{category}', 'destroy')->name('destroy')->middleware('can:Income Delete|Expense Delete');
});