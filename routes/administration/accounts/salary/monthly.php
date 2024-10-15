<?php

use App\Http\Controllers\Administration\Accounts\Salary\MonthlySalaryController;
use Illuminate\Support\Facades\Route;

/* ==============================================
===============< Monthly Salary Routes >==============
===============================================*/
Route::controller(MonthlySalaryController::class)->prefix('monthly')->name('monthly.')->group(function () {
    Route::get('/all', 'index')->name('index')->can('Salary Read');
    Route::get('/show/{monthly_salary}', 'show')->name('show')->can('Salary Read');
    Route::get('/re_generate/{monthly_salary}', 'reGenerateSalary')->name('regenerate')->can('Salary Update');

    Route::post('/add_earning/{monthly_salary}', 'addEarning')->name('add.earning')->can('Salary Update');
    Route::post('/add_deduction/{monthly_salary}', 'addDeduction')->name('add.deduction')->can('Salary Update');

    Route::post('/mark_as_paid/{monthly_salary}', 'markAsPaid')->name('mark.paid')->can('Salary Update');

    Route::get('/send_mail_payslip/{monthly_salary}', 'sendMailPayslip')->name('send.mail.payslip')->can('Salary Update');
});