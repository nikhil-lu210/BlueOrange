<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\Hiring\HiringController;

/* ==============================================
===============< Employee Hiring Routes >========
===============================================*/
Route::prefix('hiring')
    ->name('hiring.')
    ->group(function () {
        Route::controller(HiringController::class)
            ->group(function () {
                // Main CRUD routes
                Route::get('/all', 'index')->name('index')->middleware('can:Employee Hiring Everything');
                Route::get('/create', 'create')->name('create')->middleware('can:Employee Hiring Create');
                Route::post('/store', 'store')->name('store')->middleware('can:Employee Hiring Create');
                Route::get('/show/{hiring_candidate}', 'show')->name('show')->middleware('can:Employee Hiring Read');
                Route::get('/edit/{hiring_candidate}', 'edit')->name('edit')->middleware('can:Employee Hiring Update');
                Route::put('/update/{hiring_candidate}', 'update')->name('update')->middleware('can:Employee Hiring Update');
                Route::get('/destroy/{hiring_candidate}', 'destroy')->name('destroy')->middleware('can:Employee Hiring Delete');

                // My evaluations (for assigned evaluators)
                Route::get('/my-evaluations', 'myEvaluations')->name('my.evaluations')->middleware('can:Employee Hiring Read');

                // Stage evaluation management
                Route::post('/evaluation/store', 'storeEvaluation')->name('evaluation.store')->middleware('can:Employee Hiring Update');
                Route::post('/evaluation/start', 'startEvaluation')->name('evaluation.start')->middleware('can:Employee Hiring Update');
                Route::post('/evaluation/complete', 'completeEvaluation')->name('evaluation.complete')->middleware('can:Employee Hiring Update');

                // Hiring completion
                Route::get('/complete/{hiring_candidate}', 'showHiringForm')->name('complete.form')->middleware('can:Employee Hiring Everything');
                Route::post('/complete/{hiring_candidate}', 'completeHiring')->name('complete')->middleware('can:Employee Hiring Everything');

                // Candidate rejection
                Route::post('/reject/{hiring_candidate}', 'reject')->name('reject')->middleware('can:Employee Hiring Update');
            });
    });
