<?php

namespace App\Http\Controllers\Administration\Quiz;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Quiz\QuizQuestion\QuizQuestion;

class QuizQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questions = QuizQuestion::with([
            'creator.employee',
            'creator.media',
            'creator.roles'
        ])->orderByDesc('created_at')->get();
        // dd($questions[0]->tests()->count());

        return view('administration.quiz.question.index', compact(['questions']));
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
    public function show(QuizQuestion $question)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QuizQuestion $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QuizQuestion $question)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QuizQuestion $question)
    {
        try {
            DB::transaction(function () use ($question) {
                // Find all quiz tests where this question ID is used
                $tests = DB::table('quiz_tests')
                    ->whereJsonContains('question_ids', $question->id)
                    ->get();

                // Loop through and update each test
                foreach ($tests as $test) {
                    $updatedIds = collect(json_decode($test->question_ids, true))
                        ->reject(fn ($id) => $id == $question->id)
                        ->values()
                        ->all();

                    DB::table('quiz_tests')
                        ->where('id', $test->id)
                        ->update(['question_ids' => json_encode($updatedIds)]);
                }

                // Delete the question
                $question->delete();
            });

            toast('Question deleted successfully.', 'success');
            return redirect()->back();

        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back();
        }
    }
}
