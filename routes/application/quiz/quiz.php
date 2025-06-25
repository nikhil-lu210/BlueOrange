<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Application\Quiz\QuizController;

/* ==============================================
===============< Non-Auth Quiz Routes >=========
===============================================*/
Route::controller(QuizController::class)->prefix('quiz')->name('quiz.')->group(function () {
    Route::get('/test', 'index')->name('test.index');

    Route::post('/test/start', 'startTest')->name('test.start');

    Route::get('/test/show/{testid}', 'show')->name('test.show');

    Route::post('/test/save-answer/{testid}', 'saveAnswer')->name('test.save.answer');

    Route::post('/test/store/{testid}', 'store')->name('test.store');

    Route::get('/test/results/{testid}', 'results')->name('test.results');
});
