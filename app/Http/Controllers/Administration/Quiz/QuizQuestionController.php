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

        return view('administration.quiz.question.index', compact(['questions']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('administration.quiz.question.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                QuizQuestion::create([
                    'creator_id' => auth()->id(),
                    'question' => $request->question,
                    'option_a' => $request->option_a,
                    'option_b' => $request->option_b,
                    'option_c' => $request->option_c,
                    'option_d' => $request->option_d,
                    'correct_option' => $request->correct_option,
                ]);
            });

            toast('Question created successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(QuizQuestion $question)
    {
        $question->load([
            'creator.employee',
            'creator.media',
            'creator.roles',
            'tests',
        ]);

        return view('administration.quiz.question.show', compact(['question']));
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
