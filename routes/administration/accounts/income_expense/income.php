<?php

use App\Http\Controllers\Administration\Accounts\IncomeExpense\IncomeController;
use Illuminate\Support\Facades\Route;

/* ==============================================
===============< Income Routes >==============
===============================================*/
Route::controller(IncomeController::class)->prefix('income')->name('income.')->group(function () {
    Route::get('/all', 'index')->name('index')->can('Income Read');
    Route::get('/create', 'create')->name('create')->can('Income Create');

    Route::post('/store', 'store')->name('store')->can('Income Create');

    Route::get('/show/{income}', 'show')->name('show')->can('Income Read');
    Route::get('/edit/{income}', 'edit')->name('edit')->can('Income Update');

    Route::put('/update/{income}', 'update')->name('update')->can('Income Update');
    Route::get('/destroy/{income}', 'destroy')->name('destroy')->can('Income Delete');
});