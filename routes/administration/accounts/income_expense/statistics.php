<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\Accounts\IncomeExpense\IncomeExpenseStatisticController;

/* ==============================================
===============< Statistics Routes >==============
===============================================*/
Route::controller(IncomeExpenseStatisticController::class)->prefix('statistics')->name('statistics.')->group(function () {
    Route::get('/', 'index')->name('index');
});