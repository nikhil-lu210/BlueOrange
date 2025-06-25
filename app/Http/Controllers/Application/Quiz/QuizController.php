<?php

namespace App\Http\Controllers\Application\Quiz;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuizController extends Controller
{
    public function __construct()
    {
        // Abort with 403 if user is authenticated
        $this->middleware(function ($request, $next) {
            if (auth()->check()) {
                abort(403, 'Unauthorized action. You are not allowed to access this page.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        return view('application.quiz.index');
    }
}
