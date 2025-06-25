@extends('layouts.public.app')

@section('page_title', __('QUIZ TEST'))

@section('custom_css')
    <style>
        /* 🚀 ULTRA MODERN QUIZ DESIGN WITH BOOTSTRAP GRID */
        .authentication-wrapper {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        .authentication-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.2) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-30px) rotate(1deg); }
            66% { transform: translateY(-20px) rotate(-1deg); }
        }

        .authentication-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            position: relative;
            z-index: 2;
        }

        .d-none.d-lg-flex { display: none !important; }
        .col-lg-5 { flex: 0 0 100% !important; max-width: 100% !important; }
        .w-px-400 { width: 100% !important; max-width: 100% !important; }

        /* 💾 Save Indicator */
        .save-indicator {
            position: fixed;
            top: 30px;
            right: 30px;
            background: linear-gradient(135deg, #00d4aa, #00b894);
            color: white;
            padding: 16px 28px;
            border-radius: 50px;
            box-shadow: 0 15px 35px rgba(0, 212, 170, 0.4);
            transform: translateX(400px);
            transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            z-index: 1050;
            font-weight: 700;
            font-size: 1rem;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .save-indicator.show {
            transform: translateX(0);
        }

        /* 🎯 Quiz Header */
        .quiz-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(25px);
            border-radius: 28px;
            padding: 3rem;
            margin-bottom: 3rem;
            box-shadow:
                0 25px 50px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
        }

        .quiz-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #667eea, #764ba2, #667eea);
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .quiz-logo {
            max-height: 90px;
            filter: drop-shadow(0 8px 16px rgba(0, 0, 0, 0.15));
            transition: transform 0.3s ease;
        }

        .quiz-logo:hover {
            transform: scale(1.05);
        }

        /* ⏰ Timer */
        .quiz-timer {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            border-radius: 25px;
            padding: 1.5rem 2.5rem;
            text-align: center;
            margin-bottom: 2rem;
            font-size: 1.5rem;
            font-weight: 800;
            box-shadow: 0 20px 40px rgba(231, 76, 60, 0.4);
            position: relative;
            overflow: hidden;
            border: 3px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .quiz-timer::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s;
        }

        .quiz-timer:hover::before {
            left: 100%;
        }

        .quiz-timer.warning {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            animation: pulse-warning 2s infinite;
        }

        .quiz-timer.danger {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            animation: pulse-danger 1s infinite;
        }

        @keyframes pulse-warning {
            0%, 100% { transform: scale(1); box-shadow: 0 20px 40px rgba(243, 156, 18, 0.4); }
            50% { transform: scale(1.02); box-shadow: 0 25px 50px rgba(243, 156, 18, 0.6); }
        }

        @keyframes pulse-danger {
            0%, 100% { transform: scale(1); box-shadow: 0 20px 40px rgba(231, 76, 60, 0.4); }
            50% { transform: scale(1.03); box-shadow: 0 30px 60px rgba(231, 76, 60, 0.7); }
        }

        /* 📊 Progress Info */
        .progress-info {
            background: rgba(255, 255, 255, 0.85);
            border-radius: 24px;
            padding: 2.5rem;
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .progress-item {
            text-align: center;
            padding: 2rem 1.5rem;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid rgba(255, 255, 255, 0.5);
            position: relative;
            overflow: hidden;
        }

        .progress-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .progress-item:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            border-color: rgba(102, 126, 234, 0.3);
        }

        .progress-item:hover::before {
            opacity: 1;
        }

        .progress-icon {
            font-size: 2rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
            display: block;
        }

        .progress-value {
            display: block;
            font-size: 2.5rem;
            font-weight: 900;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .progress-label {
            font-size: 0.95rem;
            color: #666;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            z-index: 1;
        }

        /* 🎯 Question Cards */
        .question-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 28px;
            padding: 3rem;
            margin-bottom: 2rem;
            box-shadow:
                0 20px 40px rgba(0, 0, 0, 0.1),
                0 0 0 1px rgba(255, 255, 255, 0.3);
            border: 2px solid rgba(255, 255, 255, 0.2);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .question-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.02), rgba(118, 75, 162, 0.02));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .question-card:hover {
            transform: translateY(-8px) scale(1.01);
            box-shadow:
                0 30px 60px rgba(0, 0, 0, 0.2),
                0 0 0 1px rgba(102, 126, 234, 0.3);
            border-color: rgba(102, 126, 234, 0.3);
        }

        .question-card:hover::before {
            opacity: 1;
        }

        .question-card.answered {
            border-color: rgba(0, 184, 148, 0.4);
            background: rgba(255, 255, 255, 0.98);
        }

        .question-card.answered::before {
            background: linear-gradient(135deg, rgba(0, 184, 148, 0.05), rgba(0, 206, 201, 0.05));
            opacity: 1;
        }

        .question-badge {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 1.4rem;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
            flex-shrink: 0;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .question-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .question-card.answered .question-badge {
            background: linear-gradient(135deg, #00b894, #00cec9);
            box-shadow: 0 10px 25px rgba(0, 184, 148, 0.4);
        }

        .question-card:hover .question-badge::before {
            left: 100%;
        }

        .question-text {
            font-size: 1.25rem;
            font-weight: 700;
            color: #2d3436;
            line-height: 1.7;
            position: relative;
            z-index: 1;
            flex: 1;
        }

        /* 🎨 Options Container */
        .options-container {
            margin-top: 2rem;
        }

        /* 🔘 Ultra Modern Radio Options */
        .form-check.custom-option {
            margin-bottom: 0;
        }

        .custom-option-content {
            display: block;
            padding: 1.5rem 2rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(255, 255, 255, 0.9);
            position: relative;
            backdrop-filter: blur(10px);
            overflow: hidden;
            height: 100%;
        }

        .custom-option-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .custom-option-content:hover {
            border-color: rgba(102, 126, 234, 0.5);
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.2);
        }

        .custom-option-content:hover::before {
            opacity: 1;
        }

        .custom-option-content input[type="radio"] {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .custom-option-content input[type="radio"]:checked + .custom-option-header {
            color: #667eea;
        }

        .custom-option-content:has(input[type="radio"]:checked) {
            border-color: #667eea;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
            transform: translateY(-8px) scale(1.03);
        }

        .custom-option-content:has(input[type="radio"]:checked)::before {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.15), rgba(118, 75, 162, 0.15));
            opacity: 1;
        }

        .custom-option-header {
            display: flex;
            align-items: center;
            font-size: 1.1rem;
            font-weight: 600;
            color: #2d3436;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .option-letter {
            background: linear-gradient(135deg, #ddd6fe, #c4b5fd);
            color: #667eea;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            margin-right: 1.5rem;
            transition: all 0.3s ease;
            font-size: 1.2rem;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
            flex-shrink: 0;
        }

        .option-text {
            flex: 1;
            line-height: 1.5;
        }

        .custom-option-content:has(input[type="radio"]:checked) .option-letter {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            transform: scale(1.1);
        }

        /* 🏁 End Quiz Section */
        .end-quiz-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 28px;
            padding: 3rem;
            text-align: center;
            margin-top: 3rem;
            border: 3px dashed rgba(102, 126, 234, 0.3);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            position: relative;
            overflow: hidden;
        }

        .end-quiz-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .end-quiz-section:hover::before {
            opacity: 1;
        }

        .end-quiz-icon {
            font-size: 3rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .btn-end-quiz {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            color: white;
            padding: 1rem 3rem;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 700;
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-end-quiz::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .btn-end-quiz:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.5);
        }

        .btn-end-quiz:hover::before {
            left: 100%;
        }

        .btn-end-quiz:disabled {
            background: linear-gradient(135deg, #95a5a6, #7f8c8d);
            box-shadow: 0 10px 25px rgba(149, 165, 166, 0.3);
            cursor: not-allowed;
            transform: none;
        }

        .btn-end-quiz:disabled:hover {
            transform: none;
            box-shadow: 0 10px 25px rgba(149, 165, 166, 0.3);
        }

        /* 📱 Responsive Design */
        @media (max-width: 768px) {
            .authentication-inner {
                padding: 15px;
            }

            .quiz-header {
                padding: 2rem;
                margin-bottom: 2rem;
            }

            .quiz-timer {
                font-size: 1.3rem;
                padding: 1.25rem 2rem;
            }

            .question-card {
                padding: 2rem;
            }

            .question-badge {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
            }

            .progress-item {
                padding: 1.5rem 1rem;
            }

            .progress-value {
                font-size: 2rem;
            }

            .custom-option-content {
                padding: 1.25rem 1.5rem;
            }

            .option-letter {
                width: 40px;
                height: 40px;
                font-size: 1.1rem;
                margin-right: 1rem;
            }

            .end-quiz-section {
                padding: 2rem;
                margin-top: 2rem;
            }

            .btn-end-quiz {
                padding: 0.875rem 2.5rem;
                font-size: 1rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Save Indicator -->
        <div class="save-indicator" id="saveIndicator">
            <i class="ti ti-check me-2"></i>
            Answer Saved Successfully!
        </div>

        <!-- Quiz Header -->
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="quiz-header">
                    <!-- Logo -->
                    <div class="row">
                        <div class="col-12 text-center mb-4">
                            <img src="{{ asset('assets/img/branding/logo.png') }}" alt="Logo" class="quiz-logo">
                        </div>
                    </div>

                    <!-- Timer -->
                    <div class="row">
                        <div class="col-12">
                            <div class="quiz-timer" id="timer">
                                <i class="ti ti-clock me-2"></i>
                                Time Remaining: <span id="time-display">{{ $test->total_time }}:00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Info -->
                    <div class="row">
                        <div class="col-12">
                            <div class="progress-info">
                                <div class="row g-3">
                                    <div class="col-6 col-md-3">
                                        <div class="progress-item">
                                            <div class="progress-icon">
                                                <i class="ti ti-check-circle"></i>
                                            </div>
                                            <span class="progress-value" id="answered-count">0</span>
                                            <div class="progress-label">Answered</div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="progress-item">
                                            <div class="progress-icon">
                                                <i class="ti ti-list-numbers"></i>
                                            </div>
                                            <span class="progress-value">{{ $test->total_questions }}</span>
                                            <div class="progress-label">Total Questions</div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="progress-item">
                                            <div class="progress-icon">
                                                <i class="ti ti-clock-hour-4"></i>
                                            </div>
                                            <span class="progress-value">{{ $test->total_time }}</span>
                                            <div class="progress-label">Minutes</div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="progress-item">
                                            <div class="progress-icon">
                                                <i class="ti ti-target"></i>
                                            </div>
                                            <span class="progress-value">{{ $test->passing_score }}</span>
                                            <div class="progress-label">Passing Score</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Questions Section -->
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">
                @foreach($test->questions as $index => $question)
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="question-card {{ $question->pivot->selected_option ? 'answered' : '' }}" data-question="{{ $index + 1 }}">
                                <!-- Question Header -->
                                <div class="row align-items-center mb-4">
                                    <div class="col-auto">
                                        <div class="question-badge">{{ $index + 1 }}</div>
                                    </div>
                                    <div class="col">
                                        <div class="question-text">{{ $question->question }}</div>
                                    </div>
                                </div>

                                <!-- Options -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="options-container">
                                            <div class="row g-3">
                                                <div class="col-12 col-md-6">
                                                    <div class="form-check custom-option">
                                                        <label class="form-check-label custom-option-content" for="question_{{ $question->id }}_A">
                                                            <input name="question_{{ $question->id }}" class="form-check-input quiz-option"
                                                                   type="radio" value="A" id="question_{{ $question->id }}_A"
                                                                   data-question-id="{{ $question->id }}"
                                                                   {{ $question->pivot->selected_option === 'A' ? 'checked' : '' }} />
                                                            <span class="custom-option-header">
                                                                <span class="option-letter">A</span>
                                                                <span class="option-text">{{ $question->option_a }}</span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="form-check custom-option">
                                                        <label class="form-check-label custom-option-content" for="question_{{ $question->id }}_B">
                                                            <input name="question_{{ $question->id }}" class="form-check-input quiz-option"
                                                                   type="radio" value="B" id="question_{{ $question->id }}_B"
                                                                   data-question-id="{{ $question->id }}"
                                                                   {{ $question->pivot->selected_option === 'B' ? 'checked' : '' }} />
                                                            <span class="custom-option-header">
                                                                <span class="option-letter">B</span>
                                                                <span class="option-text">{{ $question->option_b }}</span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="form-check custom-option">
                                                        <label class="form-check-label custom-option-content" for="question_{{ $question->id }}_C">
                                                            <input name="question_{{ $question->id }}" class="form-check-input quiz-option"
                                                                   type="radio" value="C" id="question_{{ $question->id }}_C"
                                                                   data-question-id="{{ $question->id }}"
                                                                   {{ $question->pivot->selected_option === 'C' ? 'checked' : '' }} />
                                                            <span class="custom-option-header">
                                                                <span class="option-letter">C</span>
                                                                <span class="option-text">{{ $question->option_c }}</span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="form-check custom-option">
                                                        <label class="form-check-label custom-option-content" for="question_{{ $question->id }}_D">
                                                            <input name="question_{{ $question->id }}" class="form-check-input quiz-option"
                                                                   type="radio" value="D" id="question_{{ $question->id }}_D"
                                                                   data-question-id="{{ $question->id }}"
                                                                   {{ $question->pivot->selected_option === 'D' ? 'checked' : '' }} />
                                                            <span class="custom-option-header">
                                                                <span class="option-letter">D</span>
                                                                <span class="option-text">{{ $question->option_d }}</span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            <!-- End Quiz Section -->
            <div class="row justify-content-center">
                <div class="col-12 col-lg-8 col-xl-6">
                    <div class="end-quiz-section">
                        <div class="row text-center">
                            <div class="col-12">
                                <div class="end-quiz-icon mb-3">
                                    <i class="ti ti-flag-check"></i>
                                </div>
                                <h4 class="mb-3">Ready to Submit?</h4>
                                <p class="text-muted mb-4">Make sure you have answered all questions. Once submitted, you cannot change your answers.</p>
                                <button type="button" class="btn btn-end-quiz" id="endQuizBtn" disabled>
                                    <i class="ti ti-send me-2"></i>
                                    End Quiz Test
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
