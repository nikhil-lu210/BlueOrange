<?php

namespace App\Http\Controllers\Application\Quiz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        return view('application.quiz.index');
    }
}
