@extends('layouts.public.quiz.app')

@section('page_title', __('QUIZ TEST RESULTS'))

@section('custom_css')
    <style>
        /* Override public layout styles for results */
        .authentication-wrapper {
            background: #f8f9fa !important;
        }

        .authentication-inner {
            max-width: 800px;
            margin: 0 auto;
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
            max-width: 700px !important;
        }

        .results-container {
            text-align: center;
            padding: 40px 30px;
        }

        .result-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: 1px solid #e3e6f0;
            margin-bottom: 30px;
        }

        .result-icon {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 3rem;
            color: white;
        }

        .result-icon.passed {
            background: linear-gradient(135deg, #28a745, #20c997);
            animation: successPulse 2s ease-in-out infinite;
        }

        .result-icon.failed {
            background: linear-gradient(135deg, #dc3545, #fd7e14);
        }

        @keyframes successPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .result-title {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .result-title.passed {
            color: #28a745;
        }

        .result-title.failed {
            color: #dc3545;
        }

        .result-subtitle {
            font-size: 1.2rem;
            color: #6c757d;
            margin-bottom: 30px;
        }

        .score-display {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
        }

        .score-number {
            font-size: 3rem;
            font-weight: bold;
            color: #2c3e50;
        }

        .score-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }

        .score-item {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .score-item-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2c3e50;
        }

        .score-item-label {
            font-size: 0.9rem;
            color: #6c757d;
            margin-top: 5px;
        }

        .action-buttons {
            margin-top: 40px;
        }

        .btn-home {
            background: linear-gradient(135deg, #685dd8, #5a4fcf);
            border: none;
            color: white;
            padding: 15px 30px;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(104, 93, 216, 0.3);
            color: white;
        }

        .completion-time {
            background: #e3f2fd;
            color: #1976d2;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 0.9rem;
            display: inline-block;
            margin-top: 20px;
        }

        .motivational-message {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #ffc107;
        }

        .motivational-message.success {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            border-left-color: #28a745;
        }

        .motivational-message.failure {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            border-left-color: #dc3545;
        }
    </style>
@endsection

@section('content')
    <div class="results-container">
        @php
            $passed = $test->total_score >= $test->passing_score;
            $percentage = round(($test->total_score / $test->total_questions) * 100);
            $timeTaken = $test->started_at && $test->ended_at ?
                $test->started_at->diffInMinutes($test->ended_at) : 0;
        @endphp

        <div class="result-card">
            <!-- Result Icon -->
            <div class="result-icon {{ $passed ? 'passed' : 'failed' }}">
                @if($passed)
                    <i class="ti ti-trophy"></i>
                @else
                    <i class="ti ti-x"></i>
                @endif
            </div>

            <!-- Result Title -->
            <h1 class="result-title {{ $passed ? 'passed' : 'failed' }}">
                @if($passed)
                    Congratulations!
                @else
                    Better Luck Next Time
                @endif
            </h1>

            <!-- Result Subtitle -->
            <p class="result-subtitle">
                @if($passed)
                    You have successfully passed the quiz test.
                @else
                    You didn't meet the passing criteria this time.
                @endif
            </p>

            <!-- Score Display -->
            <div class="score-display">
                <div class="score-number">
                    {{ $test->total_score }}/{{ $test->total_questions }}
                </div>
                <div class="mt-2">
                    <strong>{{ $percentage }}%</strong> Score
                </div>
            </div>

            <!-- Score Details -->
            <div class="score-details">
                <div class="score-item">
                    <div class="score-item-value">{{ $test->total_score }}</div>
                    <div class="score-item-label">Correct Answers</div>
                </div>
                <div class="score-item">
                    <div class="score-item-value">{{ $test->total_questions - $test->total_score }}</div>
                    <div class="score-item-label">Wrong Answers</div>
                </div>
                <div class="score-item">
                    <div class="score-item-value">{{ $test->passing_score }}</div>
                    <div class="score-item-label">Required Score</div>
                </div>
                <div class="score-item">
                    <div class="score-item-value">{{ $timeTaken }}m</div>
                    <div class="score-item-label">Time Taken</div>
                </div>
            </div>

            <!-- Motivational Message -->
            <div class="motivational-message {{ $passed ? 'success' : 'failure' }}">
                @if($passed)
                    <strong>Excellent work!</strong> Your dedication and knowledge have paid off.
                    You've demonstrated strong understanding of the subject matter.
                @else
                    <strong>Don't give up!</strong> Every expert was once a beginner.
                    Use this as a learning opportunity and try again after some more preparation.
                @endif
            </div>

            <!-- Completion Time -->
            <div class="completion-time">
                <i class="ti ti-calendar me-1"></i>
                Completed on {{ $test->ended_at ? $test->ended_at->format('M d, Y \a\t h:i A') : 'N/A' }}
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ url('/') }}" class="btn-home">
                    <i class="ti ti-home me-2"></i>
                    Back to Home
                </a>
            </div>

            <!-- Next Quiz Info -->
            <div class="mt-4">
                <small class="text-muted">
                    <i class="ti ti-info-circle me-1"></i>
                    You can take another quiz after 1 hour from completion.
                </small>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function() {
            // Disable back button functionality
            history.pushState(null, null, location.href);
            window.onpopstate = function () {
                history.go(1);
            };

            // Confetti animation for passed results
            @if($passed)
                // Simple confetti effect
                function createConfetti() {
                    const colors = ['#ff6b6b', '#4ecdc4', '#45b7d1', '#96ceb4', '#ffeaa7'];
                    for (let i = 0; i < 50; i++) {
                        setTimeout(() => {
                            const confetti = $('<div>').css({
                                position: 'fixed',
                                top: '-10px',
                                left: Math.random() * 100 + '%',
                                width: '10px',
                                height: '10px',
                                backgroundColor: colors[Math.floor(Math.random() * colors.length)],
                                borderRadius: '50%',
                                zIndex: 9999,
                                pointerEvents: 'none'
                            });

                            $('body').append(confetti);

                            confetti.animate({
                                top: $(window).height() + 'px',
                                left: '+=' + (Math.random() * 200 - 100) + 'px'
                            }, 3000, function() {
                                $(this).remove();
                            });
                        }, i * 100);
                    }
                }

                // Trigger confetti after a short delay
                setTimeout(createConfetti, 500);
            @endif
        });
    </script>
@endsection
