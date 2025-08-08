<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\EmployeeRecognition\MonthlyEvaluationController;

Route::middleware(['web', 'auth', 'active_user', 'localization', 'unrestricted.users', 'restrict.devices', 'restrict.ip'])
    ->prefix('employee-recognition/monthly')
    ->name('employee_recognition.monthly.')
    ->controller(MonthlyEvaluationController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/leaderboard', 'leaderboard')->name('leaderboard');
        Route::get('/my', 'myScores')->name('my');
        Route::get('/reports', 'reports')->name('reports');
        Route::get('/trend/{user}', 'employeeTrend')->name('employee.trend');
    });
