<?php

use App\Http\Controllers\Administration\Settings\User\Salary\SalaryController;
use Illuminate\Support\Facades\Route;


/* ==============================================
===============< Monthly Salary Routes >==============
===============================================*/
Route::controller(SalaryController::class)
        ->prefix('monthly')
        ->name('monthly.')
        ->group(function () {
            Route::get('/{user}/history', 'index')->name('index')->can('Salary Read');
        });