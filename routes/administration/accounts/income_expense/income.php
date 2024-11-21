<?php

use App\Http\Controllers\Administration\Accounts\IncomeExpense\IncomeController;
use Illuminate\Support\Facades\Route;

/* ==============================================
===============< Income Routes >==============
===============================================*/
Route::controller(IncomeController::class)->prefix('income')->name('income.')->group(function () {
    Route::get('/all', 'index')->name('index')->can('Income Read');
    Route::get('/create', 'create')->name('create')->can('Income Create');
});