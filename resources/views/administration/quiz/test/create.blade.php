@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Create Quiz Test'))

@section('css_links')
    {{--  External CSS  --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }

        .quiz-form .form-label {
            font-weight: 600;
        }

        .quiz-form .required::after {
            content: " *";
            color: red;
        }

        .select2-questions {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--multiple {
            min-height: 120px;
            border: 1px solid #d9dee3;
            border-radius: 0.375rem;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #696cff;
            border: 1px solid #696cff;
            color: white;
            border-radius: 0.25rem;
            padding: 2px 8px;
            margin: 2px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white;
            margin-right: 5px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #ffcccc;
        }

        .question-counter {
            font-size: 0.875rem;
            color: #6c757d;
        }

        .auto-select-info {
            background-color: #e7f3ff;
            border: 1px solid #b8daff;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 1rem;
        }
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('Create Quiz Test') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Quiz') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.quiz.test.index') }}">{{ __('All Tests') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Create Test') }}</li>
@endsection

@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">{{ __('Create New Quiz Test') }}</h5>
                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.quiz.test.index') }}" class="btn btn-sm btn-outline-secondary">
                        <span class="tf-icon ti ti-arrow-left ti-xs me-1"></span>
                        {{ __('Back to Tests') }}
                    </a>
                </div>
            </div>

            <div class="card-body quiz-form">
                <form action="{{ route('administration.quiz.test.store') }}" method="POST" id="quizTestForm" autocomplete="off">
                    @csrf

                    <!-- Candidate Information Section -->
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-muted fw-semibold">{{ __('Candidate Information') }}</h6>
                            <hr class="mt-0">
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="candidate_name" class="form-label required">{{ __('Candidate Name') }}</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="ti ti-user"></i></span>
                                <input type="text" id="candidate_name" name="candidate_name"
                                       value="{{ old('candidate_name') }}"
                                       placeholder="{{ __('Enter candidate full name') }}"
                                       class="form-control @error('candidate_name') is-invalid @enderror"
                                       required autofocus />
                            </div>
                            @error('candidate_name')
                                <b class="text-danger"><i class="ti ti-info-circle me-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="candidate_email" class="form-label required">{{ __('Candidate Email') }}</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="ti ti-mail"></i></span>
                                <input type="email" id="candidate_email" name="candidate_email"
                                       value="{{ old('candidate_email') }}"
                                       placeholder="{{ __('Enter candidate email address') }}"
                                       class="form-control @error('candidate_email') is-invalid @enderror"
                                       required />
                            </div>
                            @error('candidate_email')
                                <b class="text-danger"><i class="ti ti-info-circle me-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>

                    <!-- Test Configuration Section -->
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-muted fw-semibold">{{ __('Test Configuration') }}</h6>
                            <hr class="mt-0">
                        </div>

                        <div class="mb-3 col-md-4">
                            <label for="total_questions" class="form-label required">{{ __('Total Questions') }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-list-numbers"></i></span>
                                <input type="number" id="total_questions" name="total_questions"
                                       value="{{ old('total_questions', 10) }}"
                                       min="1" max="100" step="1"
                                       class="form-control @error('total_questions') is-invalid @enderror"
                                       required />
                            </div>
                            <div class="form-text">{{ __('Number of questions for this test (1-100)') }}</div>
                            @error('total_questions')
                                <b class="text-danger"><i class="ti ti-info-circle me-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-4">
                            <label for="total_time" class="form-label required">{{ __('Time Limit (Minutes)') }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-clock"></i></span>
                                <input type="number" id="total_time" name="total_time"
                                       value="{{ old('total_time', 10) }}"
                                       min="1" max="300" step="1"
                                       class="form-control @error('total_time') is-invalid @enderror"
                                       required />
                            </div>
                            <div class="form-text">{{ __('Time limit in minutes (1-300)') }}</div>
                            @error('total_time')
                                <b class="text-danger"><i class="ti ti-info-circle me-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-4">
                            <label for="passing_score" class="form-label required">{{ __('Passing Score') }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-target"></i></span>
                                <input type="number" id="passing_score" name="passing_score"
                                       value="{{ old('passing_score', 6) }}"
                                       min="1" step="1"
                                       class="form-control @error('passing_score') is-invalid @enderror"
                                       required />
                            </div>
                            <div class="form-text">{{ __('Minimum score required to pass') }}</div>
                            @error('passing_score')
                                <b class="text-danger"><i class="ti ti-info-circle me-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>

                    <!-- Question Selection Section -->
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-muted fw-semibold">{{ __('Question Selection') }}</h6>
                            <hr class="mt-0">
                        </div>

                        <div class="col-12">
                            <div class="auto-select-info">
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-info-circle text-info me-2"></i>
                                    <div>
                                        <strong>{{ __('Auto Selection Mode') }}</strong>
                                        <p class="mb-0 text-muted">{{ __('Leave questions unselected to automatically choose random questions from the active question bank. Or manually select specific questions below.') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="question_ids" class="form-label">{{ __('Select Questions (Optional)') }}</label>
                                <div class="question-counter mb-2">
                                    <span id="selected-count">0</span> {{ __('of') }} <span id="max-questions">{{ old('total_questions', 10) }}</span> {{ __('questions can be selected') }}
                                    <span class="text-muted ms-2">({{ $questions->count() }} {{ __('total available') }})</span>
                                </div>

                                @if($questions->count() > 0)
                                    <select name="question_ids[]" id="question_ids" class="form-select select2-questions @error('question_ids') is-invalid @enderror" multiple data-placeholder="{{ __('Search and select questions...') }}">
                                        @foreach($questions as $question)
                                            <option value="{{ $question->id }}"
                                                    {{ in_array($question->id, old('question_ids', [])) ? 'selected' : '' }}
                                                    data-question="{{ $question->question }}"
                                                    data-correct="{{ $question->correct_option }}">
                                                Q{{ $loop->iteration }}: {{ Str::limit($question->question, 100) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">
                                        <i class="ti ti-info-circle me-1"></i>
                                        {{ __('You can search questions by typing. Maximum selections will be limited based on "Total Questions" field.') }}
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        <i class="ti ti-alert-triangle me-2"></i>
                                        {{ __('No active questions available. Please add questions first.') }}
                                        <a href="{{ route('administration.quiz.question.create') }}" class="btn btn-sm btn-warning ms-2">
                                            <i class="ti ti-plus me-1"></i>{{ __('Add Questions') }}
                                        </a>
                                    </div>
                                @endif

                                @error('question_ids')
                                    <b class="text-danger"><i class="ti ti-info-circle me-1"></i>{{ $message }}</b>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="row">
                        <div class="col-12">
                            <hr class="my-4">
                            <div class="d-flex justify-content-end gap-2">
                                <button type="reset" class="btn btn-outline-secondary" onclick="return confirm('{{ __('Are you sure you want to reset the form?') }}')">
                                    <i class="ti ti-refresh me-1"></i>{{ __('Reset Form') }}
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-device-floppy me-1"></i>{{ __('Create Test') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End row -->

@endsection

@section('script_links')
    {{--  External Javascript Links --}}
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function() {
            let maxQuestions = parseInt($('#total_questions').val()) || 10;

            // Initialize Select2 for questions with maximum selection limit
            function initializeQuestionSelect2() {
                $('#question_ids').select2({
                    placeholder: '{{ __('Search and select questions...') }}',
                    allowClear: true,
                    closeOnSelect: false,
                    maximumSelectionLength: maxQuestions,
                    language: {
                        maximumSelected: function (e) {
                            return '{{ __('You can only select') }} ' + e.maximum + ' {{ __('questions based on the Total Questions field') }}';
                        }
                    },
                    templateResult: function(data) {
                        if (!data.id) {
                            return data.text;
                        }

                        // Create custom template for dropdown options
                        var $result = $('<div class="select2-question-option"></div>');
                        $result.html('<strong>' + data.text + '</strong>');

                        return $result;
                    },
                    templateSelection: function(data) {
                        if (!data.id) {
                            return data.text;
                        }

                        // Show just the question number for selected items
                        var questionText = data.text;
                        var questionNumber = questionText.match(/^Q\d+/);
                        return questionNumber ? questionNumber[0] : data.text;
                    }
                }).on('change', function() {
                    updateSelectedCount();
                });
            }

            // Initialize other Select2 elements
            $('.select2:not(#question_ids)').select2({
                placeholder: 'Select value',
                allowClear: true
            });

            // Initialize question Select2
            initializeQuestionSelect2();

            // Update selected count
            function updateSelectedCount() {
                const selectedCount = $('#question_ids').val() ? $('#question_ids').val().length : 0;
                $('#selected-count').text(selectedCount);
            }

            // Update total questions and max selection limit
            $('#total_questions').on('input', function() {
                const totalQuestions = parseInt($(this).val()) || 1;
                maxQuestions = totalQuestions;

                // Update max questions display
                $('#max-questions').text(maxQuestions);

                // Update passing score max value
                $('#passing_score').attr('max', totalQuestions);

                // Adjust passing score if it exceeds total questions
                const currentPassingScore = parseInt($('#passing_score').val()) || 0;
                if (currentPassingScore > totalQuestions) {
                    $('#passing_score').val(totalQuestions);
                }

                // Reinitialize Select2 with new maximum selection limit
                $('#question_ids').select2('destroy');
                initializeQuestionSelect2();
                updateSelectedCount();
            });

            // Validate passing score doesn't exceed total questions
            $('#passing_score').on('input', function() {
                const totalQuestions = parseInt($('#total_questions').val()) || 1;
                const passingScore = parseInt($(this).val()) || 0;

                if (passingScore > totalQuestions) {
                    $(this).val(totalQuestions);
                    alert('{{ __('Passing score cannot be greater than total questions.') }}');
                }
            });

            // Form validation before submit
            $('#quizTestForm').on('submit', function(e) {
                const totalQuestions = parseInt($('#total_questions').val()) || 0;
                const passingScore = parseInt($('#passing_score').val()) || 0;
                const selectedQuestions = $('#question_ids').val() ? $('#question_ids').val().length : 0;

                // Check if we have enough questions
                if (selectedQuestions > 0 && selectedQuestions < totalQuestions) {
                    if (!confirm('{{ __('You have selected fewer questions than the total questions count. The remaining questions will be randomly selected. Continue?') }}')) {
                        e.preventDefault();
                        return false;
                    }
                }

                // Check if no questions are available for auto-selection
                if (selectedQuestions === 0 && totalQuestions > {{ $questions->count() }}) {
                    alert('{{ __('Not enough questions available. Please reduce the total questions count or add more questions.') }}');
                    e.preventDefault();
                    return false;
                }

                // Validate passing score
                if (passingScore > totalQuestions) {
                    alert('{{ __('Passing score cannot be greater than total questions.') }}');
                    e.preventDefault();
                    return false;
                }
            });

            // Set initial values
            $('#total_questions').trigger('input');
            updateSelectedCount();
        });
    </script>
@endsection
