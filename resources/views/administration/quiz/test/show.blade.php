@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Quiz Test Details'))

@section('css_links')
    {{--  External CSS  --}}
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
        /* Custom CSS Here */
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Quiz Test Details') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Quiz') }}</li>
    <li class="breadcrumb-item">{{ __('Quiz Tests') }}</li>
    <li class="breadcrumb-item">
        @canany(['Quiz Everything', 'Quiz Update', 'Quiz Delete'])
            <a href="{{ route('administration.quiz.test.index') }}">{{ __('All Tests') }}</a>
        @endcan
    </li>
    <li class="breadcrumb-item active">{{ __('Quiz Test Details') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">
                    <span class="text-bold">{{ $test->candidate_name }}'s</span> Quiz Test Details
                </h5>

                @canany(['Quiz Everything', 'Quiz Create', 'Quiz Update'])
                    <div class="card-header-elements ms-auto">
                        @if ($test->status == 'Pending')
                            <button type="button"
                                    value="{{ route('application.quiz.test.show', ['testid' => $test->testid]) }}"
                                    class="btn btn-icon btn-outline-dark waves-effect waves-light"
                                    id="copyTestLink"
                                    title="Click to Copy Test Link"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top">
                                <span class="tf-icon ti ti-copy"></span>
                            </button>
                        @endif
                    </div>
                @endcanany
            </div>
            <div class="card-body">
                <div class="row justify-content-left">
                    <div class="col-md-6">
                        @include('administration.quiz.test.includes.test_info')
                    </div>

                    <div class="col-md-6">
                        <div class="card card-action mb-4">
                            <div class="card-header align-items-center pb-1 pt-3">
                                <h5 class="card-action-title mb-0">Question And Answers</h5>
                            </div>
                            <div class="card-body">
                                <div class="demo-inline-spacing mt-1">
                                    <div class="list-group">
                                        @forelse ($test->questions as $question)
                                            <a href="javascript:void(0);" class="list-group-item list-group-item-action d-flex justify-content-between" data-bs-toggle="modal" data-bs-target="#showQuestionAnswerModal" data-question="{{ json_encode($question) }}">
                                                <div class="li-wrapper d-flex justify-content-start align-items-center">
                                                    <div class="avatar avatar-sm me-3">
                                                        <span class="avatar-initial rounded-circle bg-label-{{ $question->pivot->is_correct ? 'success' : 'danger' }}">
                                                            <i class="ti ti-{{ $question->pivot->is_correct ? 'check' : 'x' }}"></i>
                                                        </span>
                                                    </div>
                                                    <div class="list-content">
                                                        <h6 class="mb-1">{{ $question->question }}</h6>
                                                        <small class="text-muted">{{ $question->pivot->answered_at ? show_date_time($question->pivot->answered_at) : 'Not Answered' }}</small>
                                                    </div>
                                                </div>
                                                @if ($question->pivot->selected_option)
                                                    <small class="text-bold">
                                                        <span class="text-{{ $question->pivot->selected_option == $question->correct_option ? 'success' : 'danger' }}">{{ $question->pivot->selected_option }}</span> /
                                                        <span class="text-success">{{ $question->correct_option }}</span>
                                                    </small>
                                                @else
                                                    <small class="text-bold">
                                                        <span class="text-muted">Not Answered</span>
                                                    </small>
                                                @endif
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End row -->


{{-- Question And Answers Modal --}}
@include('administration.quiz.test.modals.question_answer_details_modal')

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(function () {
            // Initialize Bootstrap tooltip
            $('[data-bs-toggle="tooltip"]').tooltip();
        });

        $(document).on('click', '#copyTestLink', function () {
            const $btn = $(this);
            const link = $btn.val();

            // Copy to clipboard using modern API
            navigator.clipboard.writeText(link).then(() => {
                // Update tooltip content
                $btn.attr('data-bs-original-title', 'Link Copied').tooltip('show');

                // Change button class
                $btn.removeClass('btn-outline-dark').addClass('btn-dark');

                // Revert tooltip after 2 seconds
                setTimeout(() => {
                    $btn.attr('data-bs-original-title', 'Click to Copy Test Link');
                    $btn.removeClass('btn-dark').addClass('btn-outline-dark');
                }, 2000);
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            // Handle question answer modal data population
            $('#showQuestionAnswerModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var questionData = button.data('question'); // Extract question data from data-* attributes

                // Clear previous data
                $('.question-title').text('');
                $('.option-a, .option-b, .option-c, .option-d').text('');
                $('.question-creator').text('');
                $('.correct-answer').text('');
                $('.selected-answer').html('');
                $('.answered-at').text('');

                if (questionData) {
                    try {
                        // Populate question details
                        $('.question-title').text(questionData.question || 'N/A');
                        $('.option-a').text(questionData.option_a || 'N/A');
                        $('.option-b').text(questionData.option_b || 'N/A');
                        $('.option-c').text(questionData.option_c || 'N/A');
                        $('.option-d').text(questionData.option_d || 'N/A');

                        // Populate creator information
                        var creatorName = 'Unknown';
                        if (questionData.creator && questionData.creator.name) {
                            creatorName = questionData.creator.name;
                        }
                        $('.question-creator').text(creatorName);

                        // Populate correct answer
                        if (questionData.correct_option) {
                            var correctOption = questionData.correct_option.toUpperCase();
                            var correctOptionText = questionData['option_' + correctOption.toLowerCase()] || 'N/A';
                            var correctAnswer = correctOption + '. ' + correctOptionText;
                            $('.correct-answer').text(correctAnswer);
                        }

                        // Populate answer details
                        var selectedAnswer = 'Not Answered';
                        if (questionData.pivot && questionData.pivot.selected_option) {
                            var selectedOption = questionData.pivot.selected_option.toUpperCase();
                            var optionText = questionData['option_' + selectedOption.toLowerCase()] || 'N/A';
                            selectedAnswer = selectedOption + '. ' + optionText;

                            // Add color coding for correct/incorrect answers
                            var isCorrect = questionData.pivot.is_correct;
                            var answerClass = isCorrect ? 'text-success' : 'text-danger';
                            $('.selected-answer').html('<span class="' + answerClass + '">' + selectedAnswer + '</span>');
                        } else {
                            $('.selected-answer').html('<span class="text-muted">' + selectedAnswer + '</span>');
                        }

                        // Populate answered at time
                        var answeredAt = 'Not Answered';
                        if (questionData.pivot && questionData.pivot.answered_at) {
                            // Format the date if needed
                            answeredAt = questionData.pivot.answered_at;
                        }
                        $('.answered-at').text(answeredAt);

                    } catch (error) {
                        console.error('Error populating modal data:', error);
                        $('.question-title').text('Error loading question data');
                    }
                } else {
                    $('.question-title').text('No question data available');
                }
            });
        });
    </script>
@endsection
