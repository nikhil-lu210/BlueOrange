<?php

namespace App\Http\Controllers\Application\Quiz;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use App\Models\Quiz\QuizTest\QuizTest;
use App\Models\Quiz\QuizQuestion\QuizQuestion;

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
        // Check if there's an existing quiz_test_id in cookies
        $existingTestId = request()->cookie('quiz_test_id');

        if ($existingTestId) {
            // Check if the test exists and is still valid
            $existingTest = QuizTest::where('testid', $existingTestId)
                ->whereIn('status', ['Pending', 'Running'])
                ->first();

            if ($existingTest) {
                // Redirect to the existing test
                return redirect()->route('application.quiz.test.show', $existingTest->testid);
            } else {
                // Test doesn't exist or is completed, remove the cookie
                Cookie::queue(Cookie::forget('quiz_test_id'));
            }
        }

        return view('application.quiz.index');
    }

    public function startTest(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'candidate_name' => 'required|string|max:255',
            'candidate_email' => 'required|email|max:255',
        ]);

        try {
            DB::transaction(function () use ($validated, &$createdTest) {
                // Get 10 random active questions
                $questionIds = QuizQuestion::where('is_active', true)
                    ->inRandomOrder()
                    ->limit(10)
                    ->pluck('id')
                    ->toArray();

                if (count($questionIds) < 10) {
                    throw new Exception('Not enough active questions available. Please contact administrator.');
                }

                // Create the quiz test with default values
                $createdTest = QuizTest::create([
                    'candidate_name' => $validated['candidate_name'],
                    'candidate_email' => $validated['candidate_email'],
                    'total_questions' => 10,
                    'total_time' => 10, // 10 minutes
                    'passing_score' => 6,
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

            // Set cookie with test ID (expires in 1 hour)
            Cookie::queue('quiz_test_id', $createdTest->testid, 60);

            toast('Quiz test created successfully! You can now start your test.', 'success');
            return redirect()->route('application.quiz.test.show', $createdTest->testid);

        } catch (Exception $e) {
            toast('Failed to create quiz test: ' . $e->getMessage(), 'error');
            return back()->withInput();
        }
    }

    public function show($testid)
    {
        // Find the test by testid
        $test = QuizTest::where('testid', $testid)
            ->with(['questions' => function ($query) {
                $query->withPivot(['selected_option', 'is_correct', 'answered_at']);
            }])
            ->first();

        if (!$test) {
            toast('Quiz test not found.', 'error');
            return redirect()->route('application.quiz.test.index');
        }

        // Check if test is completed
        if ($test->status === 'Completed') {
            toast('This quiz test has already been completed.', 'info');
            return redirect()->route('application.quiz.test.index');
        }

        // Check if test is cancelled
        if ($test->status === 'Cancelled') {
            toast('This quiz test has been cancelled.', 'error');
            return redirect()->route('application.quiz.test.index');
        }

        // Update test status to Running if it's Pending
        if ($test->status === 'Pending') {
            $test->update([
                'status' => 'Running',
                'started_at' => now(),
            ]);
        }

        return view('application.quiz.show', compact('test'));
    }

    public function store(Request $request, $testid)
    {
        // Find the test
        $test = QuizTest::where('testid', $testid)->first();

        if (!$test) {
            toast('Quiz test not found.', 'error');
            return redirect()->route('application.quiz.test.index');
        }

        // Check if test is already completed
        if ($test->status === 'Completed') {
            toast('This quiz test has already been completed.', 'info');
            return redirect()->route('application.quiz.test.index');
        }

        // Validate answers
        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|in:A,B,C,D',
        ]);

        try {
            DB::transaction(function () use ($test, $validated) {
                $correctAnswers = 0;
                $attemptedQuestions = count($validated['answers']);

                // Process each answer
                foreach ($validated['answers'] as $questionId => $selectedOption) {
                    $question = $test->questions()->where('quiz_questions.id', $questionId)->first();

                    if ($question) {
                        $isCorrect = $question->correct_option === $selectedOption;
                        if ($isCorrect) {
                            $correctAnswers++;
                        }

                        // Update pivot table
                        $test->questions()->updateExistingPivot($questionId, [
                            'selected_option' => $selectedOption,
                            'is_correct' => $isCorrect,
                            'answered_at' => now(),
                        ]);
                    }
                }

                // Update test with results
                $test->update([
                    'status' => 'Completed',
                    'ended_at' => now(),
                    'attempted_questions' => $attemptedQuestions,
                    'total_score' => $correctAnswers,
                ]);
            });

            // Remove the cookie
            Cookie::queue(Cookie::forget('quiz_test_id'));

            $passed = $test->total_score >= $test->passing_score;
            $message = $passed
                ? "Congratulations! You passed the quiz with {$test->total_score}/{$test->total_questions} correct answers."
                : "You scored {$test->total_score}/{$test->total_questions}. Better luck next time!";

            toast($message, $passed ? 'success' : 'info');
            return redirect()->route('application.quiz.test.index')->with('test_completed', true);

        } catch (Exception $e) {
            toast('Failed to submit quiz: ' . $e->getMessage(), 'error');
            return back();
        }
    }
}
