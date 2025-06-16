<?php

namespace App\Http\Controllers\Administration\Quiz;

use App\Models\Quiz\QuizTest\QuizTest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuizTestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tests = QuizTest::with([
            'creator.employee',
            'creator.media',
            'creator.roles'
        ])->orderByDesc('created_at')->get();

        return view('administration.quiz.test.index', compact(['tests']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
