<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Application\Accounts\Salary\MonthlySalaryController;

/* ==============================================
===============< Monthly Salary Routes >=========
===============================================*/
Route::controller(MonthlySalaryController::class)->prefix('monthly')->name('monthly.')->group(function () {
    Route::get('/show/{salary_id}/{userid}/{id}', 'show')->name('show');
});