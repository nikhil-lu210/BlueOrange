@extends('layouts.public.app')

@section('page_title', __('QUIZ TEST'))

@section('custom_css')
    <style>
        .quiz-header {
            background: #685dd8;
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .quiz-timer {
            background: #dc3545;
            color: white;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .question-card {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            background: white;
        }

        .question-number {
            background: #685dd8;
            color: white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .option-label {
            cursor: pointer;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
            display: block;
        }

        .option-label:hover {
            border-color: #685dd8;
            background-color: #f8f9fa;
        }

        .option-label input[type="radio"]:checked + .option-text {
            color: #685dd8;
            font-weight: bold;
        }

        .option-label:has(input[type="radio"]:checked) {
            border-color: #685dd8;
            background-color: #f0f0ff;
        }

        .submit-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin-top: 30px;
        }
    </style>
@endsection

@section('content')
    <div class="quiz-header">
        <h3 class="mb-2 text-white text-center">
            <b>{{ config('app.name') }} - Quiz Test</b>
        </h3>
        <div class="row text-center">
            <div class="col-md-3">
                <small>Candidate:</small><br>
                <strong>{{ $test->candidate_name }}</strong>
            </div>
            <div class="col-md-3">
                <small>Email:</small><br>
                <strong>{{ $test->candidate_email }}</strong>
            </div>
            <div class="col-md-3">
                <small>Total Questions:</small><br>
                <strong>{{ $test->total_questions }}</strong>
            </div>
            <div class="col-md-3">
                <small>Passing Score:</small><br>
                <strong>{{ $test->passing_score }}/{{ $test->total_questions }}</strong>
            </div>
        </div>
    </div>

    <div class="quiz-timer" id="timer">
        <i class="ti ti-clock me-2"></i>
        Time Remaining: <span id="time-display">{{ $test->total_time }}:00</span>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="quizForm" method="POST" action="{{ route('application.quiz.test.store', $test->testid) }}">
        @csrf
        
        @foreach($test->questions as $index => $question)
            <div class="question-card">
                <div class="question-number">{{ $index + 1 }}</div>
                
                <h5 class="mb-3">{{ $question->question }}</h5>
                
                <div class="options">
                    <label class="option-label">
                        <input type="radio" name="answers[{{ $question->id }}]" value="A" 
                               {{ old("answers.{$question->id}") == 'A' ? 'checked' : '' }} required>
                        <span class="option-text">
                            <strong>A.</strong> {{ $question->option_a }}
                        </span>
                    </label>
                    
                    <label class="option-label">
                        <input type="radio" name="answers[{{ $question->id }}]" value="B" 
                               {{ old("answers.{$question->id}") == 'B' ? 'checked' : '' }} required>
                        <span class="option-text">
                            <strong>B.</strong> {{ $question->option_b }}
                        </span>
                    </label>
                    
                    <label class="option-label">
                        <input type="radio" name="answers[{{ $question->id }}]" value="C" 
                               {{ old("answers.{$question->id}") == 'C' ? 'checked' : '' }} required>
                        <span class="option-text">
                            <strong>C.</strong> {{ $question->option_c }}
                        </span>
                    </label>
                    
                    <label class="option-label">
                        <input type="radio" name="answers[{{ $question->id }}]" value="D" 
                               {{ old("answers.{$question->id}") == 'D' ? 'checked' : '' }} required>
                        <span class="option-text">
                            <strong>D.</strong> {{ $question->option_d }}
                        </span>
                    </label>
                </div>
            </div>
        @endforeach

        <div class="submit-section">
            <p class="mb-3">
                <i class="ti ti-alert-triangle text-warning me-1"></i>
                <strong>Important:</strong> Once you submit, you cannot change your answers.
            </p>
            <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                <i class="ti ti-check me-2"></i>
                Submit Quiz
            </button>
        </div>
    </form>
@endsection

@section('custom_js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Timer functionality
            const totalMinutes = {{ $test->total_time }};
            let timeLeft = totalMinutes * 60; // Convert to seconds
            const timerDisplay = document.getElementById('time-display');
            const form = document.getElementById('quizForm');
            
            function updateTimer() {
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                timerDisplay.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                
                // Change color when time is running out
                const timerElement = document.getElementById('timer');
                if (timeLeft <= 300) { // 5 minutes
                    timerElement.style.background = '#dc3545'; // Red
                } else if (timeLeft <= 600) { // 10 minutes
                    timerElement.style.background = '#fd7e14'; // Orange
                }
                
                if (timeLeft <= 0) {
                    // Auto-submit when time runs out
                    alert('Time is up! Your quiz will be submitted automatically.');
                    form.submit();
                    return;
                }
                
                timeLeft--;
            }
            
            // Update timer every second
            const timerInterval = setInterval(updateTimer, 1000);
            
            // Form submission confirmation
            form.addEventListener('submit', function(e) {
                const answered = form.querySelectorAll('input[type="radio"]:checked').length;
                const total = {{ $test->total_questions }};
                
                if (answered < total) {
                    if (!confirm(`You have only answered ${answered} out of ${total} questions. Are you sure you want to submit?`)) {
                        e.preventDefault();
                        return;
                    }
                }
                
                clearInterval(timerInterval);
            });
            
            // Prevent page refresh/back button
            window.addEventListener('beforeunload', function(e) {
                e.preventDefault();
                e.returnValue = 'Are you sure you want to leave? Your progress will be lost.';
            });
        });
    </script>
@endsection
