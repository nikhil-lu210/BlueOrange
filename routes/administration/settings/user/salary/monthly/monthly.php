<?php

use App\Http\Controllers\Administration\Settings\User\Salary\MonthlySalaryController;
use Illuminate\Support\Facades\Route;


/* ==============================================
===============< Monthly Salary Routes >==============
===============================================*/
Route::controller(MonthlySalaryController::class)
        ->prefix('monthly')
        ->name('monthly.')
        ->group(function () {
            Route::get('/all', 'index')->name('index')->can('Salary Read');
        });