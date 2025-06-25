<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Application\Quiz\QuizController;

/* ==============================================
===============< Non-Auth Quiz Routes >=========
===============================================*/
Route::controller(QuizController::class)->prefix('quiz')->name('quiz.')->group(function () {
    Route::get('/test', 'index')->name('index');
});
