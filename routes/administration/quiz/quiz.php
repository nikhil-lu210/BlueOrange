<?php

use Illuminate\Support\Facades\Route;

/* ==============================================
===============< Quiz Routes >==============
===============================================*/
Route::prefix('quiz')->name('quiz.')->group(function () {
    // quiz_question
    include_once 'quiz_question/quiz_question.php';

    // quiz_test
    include_once 'quiz_test/quiz_test.php';

    // quiz_answer
    include_once 'quiz_answer/quiz_answer.php';
});
