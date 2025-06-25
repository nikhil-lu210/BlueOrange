<?php

namespace App\Http\Controllers\Administration\Quiz;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Quiz\QuizTest\QuizTest;
use App\Models\Quiz\QuizQuestion\QuizQuestion;

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
        $questions = QuizQuestion::select(['id', 'question'])->get();

        return view('administration.quiz.test.create', compact(['questions']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'candidate_name' => 'required|string|max:255',
            'candidate_email' => 'required|email|max:255',
            'total_questions' => 'required|integer|min:1|max:100',
            'total_time' => 'required|integer|min:1|max:300',
            'passing_score' => 'required|integer|min:1',
            'question_ids' => 'nullable|array',
            'question_ids.*' => 'exists:quiz_questions,id',
        ], [
            'total_questions.min' => 'Total questions must be at least 1.',
            'total_questions.max' => 'Total questions cannot exceed 100.',
            'total_time.min' => 'Time limit must be at least 1 minute.',
            'total_time.max' => 'Time limit cannot exceed 300 minutes (5 hours).',
            'passing_score.min' => 'Passing score must be at least 1.',
        ]);

        // Additional validation
        if ($validated['passing_score'] > $validated['total_questions']) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['passing_score' => 'Passing score cannot be greater than total questions.']);
        }

        // Check if we have enough active questions
        $totalActiveQuestions = QuizQuestion::where('is_active', true)->count();
        if ($totalActiveQuestions < $validated['total_questions']) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['total_questions' => "Only {$totalActiveQuestions} active questions are available. Please reduce the total questions or add more questions."]);
        }

        try {
            /** @var \App\Models\Quiz\QuizTest $createdTest */
            $createdTest = null;

            DB::transaction(function () use ($validated, &$createdTest) {
                $questionIds = $validated['question_ids'] ?? [];

                // If no questions selected, randomly select from active questions
                if (empty($questionIds)) {
                    $availableQuestions = QuizQuestion::where('is_active', true)->pluck('id')->toArray();

                    $questionIds = collect($availableQuestions)
                        ->shuffle()
                        ->take($validated['total_questions'])
                        ->values()
                        ->toArray();
                } else {
                    // If questions are selected, ensure we have the right amount
                    if (count($questionIds) > $validated['total_questions']) {
                        // Take only the required number of questions
                        $questionIds = array_slice($questionIds, 0, $validated['total_questions']);
                    } elseif (count($questionIds) < $validated['total_questions']) {
                        // Fill remaining with random questions
                        $remainingCount = $validated['total_questions'] - count($questionIds);
                        $availableQuestions = QuizQuestion::where('is_active', true)
                            ->whereNotIn('id', $questionIds)
                            ->pluck('id')
                            ->toArray();

                        if (count($availableQuestions) < $remainingCount) {
                            throw new Exception('Not enough additional questions available to reach the total questions count.');
                        }

                        $additionalQuestions = collect($availableQuestions)
                            ->shuffle()
                            ->take($remainingCount)
                            ->values()
                            ->toArray();

                        $questionIds = array_merge($questionIds, $additionalQuestions);
                    }
                }

                // Ensure we have exactly the right number of questions
                $questionIds = array_slice($questionIds, 0, $validated['total_questions']);

                // Create the quiz test
                $createdTest = QuizTest::create([
                    'candidate_name' => $validated['candidate_name'],
                    'candidate_email' => $validated['candidate_email'],
                    'total_questions' => $validated['total_questions'],
                    'total_time' => $validated['total_time'],
                    'passing_score' => $validated['passing_score'],
                    'status' => 'Pending',
                ]);

                // Attach questions via pivot table
                $pivotData = collect($questionIds)->mapWithKeys(function ($questionId) {
                    return [
                        $questionId => [
                            'selected_option' => null,
                            'is_correct' => false,
                            'answered_at' => null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    ];
                })->toArray();

                $createdTest->questions()->attach($pivotData);
            });

            // Success message with additional info
            $message = "Quiz test created successfully for {$createdTest->candidate_name}. ";
            $message .= "Test ID: {$createdTest->getRouteKey()}. ";
            $message .= "Questions: {$createdTest->total_questions}, ";
            $message .= "Time: {$createdTest->total_time} minutes, ";
            $message .= "Passing Score: {$createdTest->passing_score}.";

            toast($message, 'success');
            return redirect()->route('administration.quiz.test.show', $createdTest);
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(QuizTest $test)
    {
        $test->load([
            'creator.employee',
            'creator.media',
            'creator.roles',
            'questions.creator.employee',
            'questions.creator.media',
            'questions.creator.roles',
            'questions.tests',
            'questions.tests.creator.employee',
            'questions.tests.creator.media',
            'questions.tests.creator.roles',
        ]);

        return view('administration.quiz.test.show', compact(['test']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QuizTest $test)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QuizTest $test)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QuizTest $test)
    {
        try {
            $test->forceDelete();

            toast('Quiz Test deleted successfully.', 'success');
            return redirect()->route('administration.quiz.test.index');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }
}
