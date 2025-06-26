@extends('layouts.public.quiz.app')

@section('page_title', __('QUIZ TEST'))

@section('custom_css')
    <style>
        /* Save Indicator */
        .save-indicator {
            position: fixed;
            top: 30px;
            right: 30px;
            background: #28c76f;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            transform: translateX(400px);
            transition: all 0.3s ease;
            z-index: 1050;
            font-weight: 600;
        }

        .save-indicator.show {
            transform: translateX(0);
        }

        /* Timer styling */
        .quiz-timer {
            background: #ea5455;
            color: white;
            border-radius: 8px;
            padding: 1rem 1.5rem;
            text-align: center;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .quiz-timer.warning {
            background: #ff9f43;
        }

        .quiz-timer.danger {
            background: #ea5455;
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }

        /* Simple radio button styling */
        .option-label {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            padding: 16px;
            border: 2px solid #dbdade;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s ease;
            background-color: #fff;
            margin-bottom: 12px;
        }

        .option-label:hover {
            border-color: #7367f0;
            background-color: #f8f7fa;
        }

        .option-label input[type="radio"] {
            display: none;
        }

        .option-letter {
            display: inline-block;
            min-width: 32px;
            height: 32px;
            line-height: 32px;
            text-align: center;
            font-weight: bold;
            color: #fff;
            background-color: #7367f0;
            border-radius: 50%;
            font-size: 16px;
            flex-shrink: 0;
        }

        .option-text {
            font-size: 16px;
            line-height: 1.4;
            flex: 1;
            color: #5d596c;
        }

        input[type="radio"]:checked + .option-letter {
            background-color: #28c76f;
        }

        input[type="radio"]:checked ~ .option-text {
            color: #28c76f;
            font-weight: 600;
        }

        /* Answered state */
        .card.answered {
            border-color: #28c76f;
        }

        /* Question styling */
        .question-title {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #5d596c;
        }
    </style>
@endsection
    
@section('content')
    {{-- <!-- Save Indicator --> --}}
    <div class="save-indicator" id="saveIndicator">
        <i class="ti ti-check me-2"></i>
        Answer Saved Successfully!
    </div>

    {{-- <!-- Quiz Header --> --}}
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="row justify-content-center">
                <!-- Logo -->
                <div class="col-10">
                    <div class="app-brand mb-4 text-center">
                        <a href="{{ url('/') }}" class="app-brand-link gap-2">
                            <img src="{{ asset(config('app.logo')) }}" alt="{{ config('app.name') }}" width="20%" style="margin: auto;">
                        </a>
                    </div>
                </div>
                <!-- /Logo -->

                <!-- Timer -->
                <div class="col-6">
                    <div class="quiz-timer mb-4" id="timer">
                        <i class="ti ti-clock me-2"></i>
                        Time Remaining: <span id="time-display">{{ $test->total_time }}:00</span>
                    </div>
                </div>

                <!-- Progress Info -->
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="text-center">
                                        <div class="mb-2">
                                            <i class="ti ti-user text-primary" style="font-size: 2rem;"></i>
                                        </div>
                                        <h4 class="mb-1">{{ $test->candidate_name }}</h4>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-center">
                                        <div class="mb-2">
                                            <i class="ti ti-mail text-primary" style="font-size: 2rem;"></i>
                                        </div>
                                        <h4 class="mb-1">{{ $test->candidate_email }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-6 col-md-3">
                                    <div class="text-center">
                                        <div class="mb-2">
                                            <i class="ti ti-list-numbers text-primary" style="font-size: 2rem;"></i>
                                        </div>
                                        <h4 class="mb-1">{{ $test->total_questions }}</h4>
                                        <small class="text-muted">Total Questions</small>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="text-center">
                                        <div class="mb-2">
                                            <i class="ti ti-check text-success" style="font-size: 2rem;"></i>
                                        </div>
                                        <h4 class="mb-1" id="answered-count">0</h4>
                                        <small class="text-muted">Answered</small>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="text-center">
                                        <div class="mb-2">
                                            <i class="ti ti-clock-hour-4 text-warning" style="font-size: 2rem;"></i>
                                        </div>
                                        <h4 class="mb-1">{{ $test->total_time }}</h4>
                                        <small class="text-muted">Total Minutes</small>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="text-center">
                                        <div class="mb-2">
                                            <i class="ti ti-target text-info" style="font-size: 2rem;"></i>
                                        </div>
                                        <h4 class="mb-1">{{ $test->passing_score }}</h4>
                                        <small class="text-muted">Passing Score</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <!-- Questions Section --> --}}
    <div class="row justify-content-center">
        <div class="col-md-10">
            @foreach($test->questions as $index => $question)
                <div class="card mb-4 {{ $question->pivot->selected_option ? 'answered' : '' }}" data-question="{{ $index + 1 }}">
                    <div class="card-body">
                        <div class="question-title">
                            {{ $index + 1 }}. {{ $question->question }}
                        </div>

                        <div class="options">
                            <label class="option-label">
                                <input type="radio" name="question_{{ $question->id }}" value="A"
                                        class="quiz-option" data-question-id="{{ $question->id }}"
                                        {{ $question->pivot->selected_option === 'A' ? 'checked' : '' }}>
                                <span class="option-letter">A</span>
                                <span class="option-text">{{ $question->option_a }}</span>
                            </label>

                            <label class="option-label">
                                <input type="radio" name="question_{{ $question->id }}" value="B"
                                        class="quiz-option" data-question-id="{{ $question->id }}"
                                        {{ $question->pivot->selected_option === 'B' ? 'checked' : '' }}>
                                <span class="option-letter">B</span>
                                <span class="option-text">{{ $question->option_b }}</span>
                            </label>

                            <label class="option-label">
                                <input type="radio" name="question_{{ $question->id }}" value="C"
                                        class="quiz-option" data-question-id="{{ $question->id }}"
                                        {{ $question->pivot->selected_option === 'C' ? 'checked' : '' }}>
                                <span class="option-letter">C</span>
                                <span class="option-text">{{ $question->option_c }}</span>
                            </label>

                            <label class="option-label">
                                <input type="radio" name="question_{{ $question->id }}" value="D"
                                        class="quiz-option" data-question-id="{{ $question->id }}"
                                        {{ $question->pivot->selected_option === 'D' ? 'checked' : '' }}>
                                <span class="option-letter">D</span>
                                <span class="option-text">{{ $question->option_d }}</span>
                            </label>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- End Quiz Section -->
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="ti ti-flag-check text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h4 class="mb-3">Ready to Submit?</h4>
                    <p class="text-muted mb-4">Make sure you have answered all questions. Once submitted, you cannot change your answers.</p>
                    <button type="button" class="btn btn-primary btn-lg" id="endQuizBtn" disabled>
                        <i class="ti ti-send me-2"></i>
                        End Quiz Test
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- <!-- Hidden form for final submission --> --}}
    <form id="finalSubmitForm" method="POST" action="{{ route('application.quiz.test.store', $test->testid) }}" style="display: none;">
        @csrf
        <input type="hidden" name="final_submit" value="1">
    </form>
    {{-- <!-- / Content --> --}}
@endsection


@section('custom_script')
    <script>
        $(document).ready(function() {
            // console.log('Document ready, jQuery loaded:', typeof $ !== 'undefined'); // Debug log
            // console.log('Quiz options found:', $('.quiz-option').length); // Debug log

            // Setup CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Debug CSRF token
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            // console.log('CSRF Token:', csrfToken ? 'Found' : 'Missing', csrfToken); // Debug log

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
                // console.log('Quiz option changed!'); // Debug log
                const questionId = $(this).data('question-id');
                const selectedOption = $(this).val();
                const saveIndicator = $('#saveIndicator');
                const questionCard = $(this).closest('.card');

                // console.log('Question ID:', questionId, 'Selected Option:', selectedOption); // Debug log

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
                        question_id: questionId,
                        selected_option: selectedOption
                    },
                    beforeSend: function() {
                        // Show loading indicator
                        saveIndicator.addClass('show').text('Saving You Answer...');
                    },
                    success: function(response) {
                        if (response.success) {
                            // Show save indicator
                            saveIndicator.text('Your answer has been saved successfully.').addClass('show');
                            setTimeout(() => {
                                saveIndicator.removeClass('show');
                            }, 2000);

                            // Show SweetAlert success notification
                            // Swal.fire({
                            //     icon: 'success',
                            //     title: 'Answer Saved!',
                            //     text: 'Your answer has been saved successfully.',
                            //     timer: 1500,
                            //     showConfirmButton: false,
                            //     toast: true,
                            //     position: 'top-end'
                            // });
                        } else {
                            // Handle server-side error
                            saveIndicator.text('Failed to save your answer. Please try again.').addClass('show');
                            setTimeout(() => {
                                saveIndicator.removeClass('show');
                            }, 2000);

                            // Swal.fire({
                            //     icon: 'error',
                            //     title: 'Save Failed',
                            //     text: response.message || 'Failed to save your answer. Please try again.',
                            //     timer: 2000,
                            //     showConfirmButton: false,
                            //     toast: true,
                            //     position: 'top-end'
                            // });

                            // Remove from answered set if save failed
                            answeredQuestions.delete(questionId);
                            questionCard.removeClass('answered');
                            updateProgress();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('AJAX Error:', error);
                        console.log('Response:', xhr.responseText);

                        saveIndicator.text('Failed to save').addClass('show');
                        setTimeout(() => {
                            saveIndicator.removeClass('show');
                        }, 2000);

                        // Show error notification
                        Swal.fire({
                            icon: 'error',
                            title: 'Connection Error',
                            text: 'Failed to save your answer. Please check your connection and try again.',
                            timer: 3000,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });

                        // Remove from answered set if save failed
                        answeredQuestions.delete(questionId);
                        questionCard.removeClass('answered');
                        updateProgress();
                    }
                });
            });

            // Fallback event listener using event delegation
            $(document).on('change', '.quiz-option', function() {
                // console.log('Fallback event listener triggered!'); // Debug log
                // This will trigger if the above event listener doesn't work
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
