@extends('layouts.public.app')

@section('page_title', __('QUIZ TEST'))

@section('custom_css')
    <style>
        /* Override public layout styles for quiz */
        .authentication-wrapper {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            min-height: 100vh;
        }

        .authentication-inner {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }

        .d-none.d-lg-flex {
            display: none !important;
        }

        .col-lg-5 {
            flex: 0 0 100% !important;
            max-width: 100% !important;
        }

        .w-px-400 {
            width: 100% !important;
            max-width: 100% !important;
        }

        /* Quiz Header */
        .quiz-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .quiz-timer {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            margin-bottom: 25px;
            font-size: 1.4rem;
            font-weight: 700;
            box-shadow: 0 8px 25px rgba(231, 76, 60, 0.3);
            position: sticky;
            top: 20px;
            z-index: 100;
            border: 3px solid rgba(255, 255, 255, 0.1);
        }

        .quiz-timer.warning {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            animation: pulse 1s infinite;
        }

        .quiz-timer.danger {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            animation: pulse 0.5s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); box-shadow: 0 8px 25px rgba(231, 76, 60, 0.3); }
            50% { transform: scale(1.02); box-shadow: 0 12px 35px rgba(231, 76, 60, 0.5); }
            100% { transform: scale(1); box-shadow: 0 8px 25px rgba(231, 76, 60, 0.3); }
        }

        .question-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .question-card:hover {
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            transform: translateY(-3px);
        }

        .question-header {
            display: flex;
            align-items: flex-start;
            margin-bottom: 25px;
            gap: 20px;
        }

        .question-number {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            box-shadow: 0 6px 15px rgba(52, 152, 219, 0.3);
            flex-shrink: 0;
        }

        .question-text {
            font-size: 1.15rem;
            font-weight: 600;
            color: #2c3e50;
            line-height: 1.6;
            flex: 1;
        }

        .options-container {
            margin-top: 25px;
        }

        /* Enhanced radio button styling */
        .form-check.custom-option {
            margin-bottom: 15px;
        }

        .custom-option-content {
            display: block;
            padding: 20px 25px;
            border: 2px solid #e8ecf4;
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
            position: relative;
            backdrop-filter: blur(5px);
        }

        .custom-option-content:hover {
            border-color: #3498db;
            background: rgba(52, 152, 219, 0.05);
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.2);
        }

        .custom-option-content input[type="radio"] {
            position: absolute;
            opacity: 0;
        }

        .custom-option-content input[type="radio"]:checked + .custom-option-header {
            color: #2980b9;
        }

        .custom-option-content:has(input[type="radio"]:checked) {
            border-color: #3498db;
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.1), rgba(41, 128, 185, 0.05));
            box-shadow: 0 8px 20px rgba(52, 152, 219, 0.25);
            transform: translateX(8px);
        }

        .custom-option-header {
            display: flex;
            align-items: center;
            font-size: 1.05rem;
            font-weight: 500;
            color: #34495e;
            transition: all 0.3s ease;
        }

        .option-letter {
            background: linear-gradient(135deg, #ecf0f1, #bdc3c7);
            color: #2c3e50;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 20px;
            transition: all 0.3s ease;
            font-size: 1.1rem;
        }

        .custom-option-content:has(input[type="radio"]:checked) .option-letter {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }

        .end-quiz-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 35px;
            text-align: center;
            margin-top: 40px;
            border: 2px dashed #3498db;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .progress-info {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .progress-item {
            text-align: center;
        }

        .progress-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2c3e50;
            display: block;
        }

        .progress-label {
            font-size: 0.9rem;
            color: #7f8c8d;
            margin-top: 5px;
        }

        .save-indicator {
            position: fixed;
            top: 30px;
            right: 30px;
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            padding: 15px 20px;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 600;
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 0 6px 20px rgba(39, 174, 96, 0.3);
        }

        .save-indicator.show {
            opacity: 1;
            transform: translateY(0);
        }

        .btn-end-quiz {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            border: none;
            color: white;
            padding: 15px 35px;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(39, 174, 96, 0.3);
        }

        .btn-end-quiz:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(39, 174, 96, 0.4);
            color: white;
        }

        .btn-end-quiz:disabled {
            background: linear-gradient(135deg, #95a5a6, #7f8c8d);
            cursor: not-allowed;
            box-shadow: none;
        }

        /* Answered question indicator */
        .question-card.answered .question-number {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .authentication-inner {
                padding: 15px;
            }

            .question-card {
                padding: 20px;
            }

            .question-header {
                gap: 15px;
            }

            .question-number {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }

            .custom-option-content {
                padding: 15px 20px;
            }

            .option-letter {
                width: 35px;
                height: 35px;
                margin-right: 15px;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Save Indicator -->
    <div class="save-indicator" id="saveIndicator">
        <i class="ti ti-check me-2"></i>
        Answer Saved Successfully!
    </div>

    <!-- Quiz Header -->
    <div class="quiz-header">
        <!-- Logo -->
        <div class="text-center mb-4">
            <img src="{{ asset('assets/img/branding/logo.png') }}" alt="Logo" style="max-height: 70px;">
        </div>

        <!-- Timer -->
        <div class="quiz-timer" id="timer">
            <i class="ti ti-clock me-2"></i>
            Time Remaining: <span id="time-display">{{ $test->total_time }}:00</span>
        </div>

        <!-- Progress Info -->
        <div class="progress-info">
            <div class="row text-center">
                <div class="col-6 col-md-3">
                    <div class="progress-item">
                        <span class="progress-value" id="answered-count">0</span>
                        <div class="progress-label">Answered</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="progress-item">
                        <span class="progress-value">{{ $test->total_questions }}</span>
                        <div class="progress-label">Total Questions</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="progress-item">
                        <span class="progress-value">{{ $test->total_time }}</span>
                        <div class="progress-label">Minutes</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="progress-item">
                        <span class="progress-value">{{ $test->passing_score }}</span>
                        <div class="progress-label">Passing Score</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Questions -->
    @foreach($test->questions as $index => $question)
        <div class="question-card {{ $question->pivot->selected_option ? 'answered' : '' }}" data-question="{{ $index + 1 }}">
            <div class="question-header">
                <div class="question-number">{{ $index + 1 }}</div>
                <div class="question-text">{{ $question->question }}</div>
            </div>

            <div class="options-container">
                <div class="form-check custom-option custom-option-basic">
                    <label class="form-check-label custom-option-content" for="question_{{ $question->id }}_A">
                        <input name="question_{{ $question->id }}" class="form-check-input quiz-option"
                               type="radio" value="A" id="question_{{ $question->id }}_A"
                               data-question-id="{{ $question->id }}"
                               {{ $question->pivot->selected_option === 'A' ? 'checked' : '' }} />
                        <span class="custom-option-header">
                            <span class="option-letter">A</span>
                            <span>{{ $question->option_a }}</span>
                        </span>
                    </label>
                </div>

                <div class="form-check custom-option custom-option-basic">
                    <label class="form-check-label custom-option-content" for="question_{{ $question->id }}_B">
                        <input name="question_{{ $question->id }}" class="form-check-input quiz-option"
                               type="radio" value="B" id="question_{{ $question->id }}_B"
                               data-question-id="{{ $question->id }}"
                               {{ $question->pivot->selected_option === 'B' ? 'checked' : '' }} />
                        <span class="custom-option-header">
                            <span class="option-letter">B</span>
                            <span>{{ $question->option_b }}</span>
                        </span>
                    </label>
                </div>

                <div class="form-check custom-option custom-option-basic">
                    <label class="form-check-label custom-option-content" for="question_{{ $question->id }}_C">
                        <input name="question_{{ $question->id }}" class="form-check-input quiz-option"
                               type="radio" value="C" id="question_{{ $question->id }}_C"
                               data-question-id="{{ $question->id }}"
                               {{ $question->pivot->selected_option === 'C' ? 'checked' : '' }} />
                        <span class="custom-option-header">
                            <span class="option-letter">C</span>
                            <span>{{ $question->option_c }}</span>
                        </span>
                    </label>
                </div>

                <div class="form-check custom-option custom-option-basic">
                    <label class="form-check-label custom-option-content" for="question_{{ $question->id }}_D">
                        <input name="question_{{ $question->id }}" class="form-check-input quiz-option"
                               type="radio" value="D" id="question_{{ $question->id }}_D"
                               data-question-id="{{ $question->id }}"
                               {{ $question->pivot->selected_option === 'D' ? 'checked' : '' }} />
                        <span class="custom-option-header">
                            <span class="option-letter">D</span>
                            <span>{{ $question->option_d }}</span>
                        </span>
                    </label>
                </div>
            </div>
        </div>
    @endforeach

    <!-- End Quiz Section -->
    <div class="end-quiz-section">
        <h5 class="mb-3">
            <i class="ti ti-flag-check me-2"></i>
            Ready to Submit?
        </h5>
        <p class="text-muted mb-4">
            Make sure you have answered all questions. Once submitted, you cannot change your answers.
        </p>
        <button type="button" class="btn btn-success btn-lg" id="endQuizBtn" disabled>
            <i class="ti ti-send me-2"></i>
            End Quiz Test
        </button>
    </div>

    <!-- Hidden form for final submission -->
    <form id="finalSubmitForm" method="POST" action="{{ route('application.quiz.test.store', $test->testid) }}" style="display: none;">
        @csrf
        <input type="hidden" name="final_submit" value="1">
    </form>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function() {
            // Timer functionality with proper calculation based on started_at
            const totalMinutes = {{ $test->total_time }};
            const startedAt = new Date("{{ $test->started_at ? $test->started_at->toISOString() : now()->toISOString() }}");
            const timerDisplay = $('#time-display');
            const timerElement = $('#timer');
            let answeredQuestions = new Set();

            // Initialize answered questions from existing data
            @foreach($test->questions as $question)
                @if($question->pivot->selected_option)
                    answeredQuestions.add({{ $question->id }});
                @endif
            @endforeach

            function updateTimer() {
                const now = new Date();
                const elapsedMinutes = (now - startedAt) / (1000 * 60); // Convert to minutes
                const remainingMinutes = totalMinutes - elapsedMinutes;
                const timeLeft = Math.max(0, remainingMinutes * 60); // Convert to seconds

                const minutes = Math.floor(timeLeft / 60);
                const seconds = Math.floor(timeLeft % 60);
                timerDisplay.text(`${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`);

                // Change timer appearance based on remaining time
                timerElement.removeClass('warning danger');
                if (timeLeft <= 120) { // 2 minutes
                    timerElement.addClass('danger');
                } else if (timeLeft <= 300) { // 5 minutes
                    timerElement.addClass('warning');
                }

                if (timeLeft <= 0) {
                    // Auto-redirect when time runs out
                    clearInterval(timerInterval);
                    // Auto-submit the quiz
                    $('#finalSubmitForm').submit();
                    return;
                }
            }

            // Update timer every second
            const timerInterval = setInterval(updateTimer, 1000);

            // Initial timer update
            updateTimer();

            // AJAX auto-save functionality
            $('.quiz-option').on('change', function() {
                const questionId = $(this).data('question-id');
                const selectedOption = $(this).val();
                const saveIndicator = $('#saveIndicator');
                const questionCard = $(this).closest('.question-card');

                // Add to answered questions set
                answeredQuestions.add(questionId);

                // Mark question card as answered
                questionCard.addClass('answered');

                updateProgress();

                // Save answer via AJAX
                $.ajax({
                    url: "{{ route('application.quiz.test.save.answer', $test->testid) }}",
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        question_id: questionId,
                        selected_option: selectedOption
                    },
                    success: function(response) {
                        if (response.success) {
                            // Show save indicator
                            saveIndicator.addClass('show');
                            setTimeout(() => {
                                saveIndicator.removeClass('show');
                            }, 2000);
                        }
                    },
                    error: function() {
                        console.log('Failed to save answer');
                        // Remove from answered set if save failed
                        answeredQuestions.delete(questionId);
                        questionCard.removeClass('answered');
                        updateProgress();
                    }
                });
            });

            // Update progress counter and enable/disable submit button
            function updateProgress() {
                const totalQuestions = {{ $test->total_questions }};
                const answeredCount = answeredQuestions.size;

                $('#answered-count').text(answeredCount);

                // Enable submit button if all questions are answered
                if (answeredCount === totalQuestions) {
                    $('#endQuizBtn').prop('disabled', false);
                } else {
                    $('#endQuizBtn').prop('disabled', true);
                }
            }

            // Initial progress update
            updateProgress();

            // End quiz button click
            $('#endQuizBtn').on('click', function() {
                const totalQuestions = {{ $test->total_questions }};
                const answeredCount = answeredQuestions.size;

                if (answeredCount < totalQuestions) {
                    alert(`Please answer all ${totalQuestions} questions before submitting.`);
                    return;
                }

                if (confirm('Are you sure you want to end the quiz? This action cannot be undone.')) {
                    clearInterval(timerInterval);
                    $('#finalSubmitForm').submit();
                }
            });

            // Prevent page refresh/back button
            window.addEventListener('beforeunload', function(e) {
                e.preventDefault();
                e.returnValue = 'Are you sure you want to leave? Your progress will be lost.';
            });

            // Disable right-click context menu
            $(document).on('contextmenu', function(e) {
                e.preventDefault();
            });

            // Disable F12, Ctrl+Shift+I, Ctrl+U
            $(document).on('keydown', function(e) {
                if (e.keyCode === 123 || // F12
                    (e.ctrlKey && e.shiftKey && e.keyCode === 73) || // Ctrl+Shift+I
                    (e.ctrlKey && e.keyCode === 85)) { // Ctrl+U
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection
