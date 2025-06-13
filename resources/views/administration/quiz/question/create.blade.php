@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Create Question'))

@section('css_links')
    {{--  External CSS  --}}
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
        /* custom css */
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('Create Question') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('administration.dashboard.index') }}">{{ __('Dashboard') }}</a>
    </li>
    <li class="breadcrumb-item">{{ __('Quiz') }}</li>
    <li class="breadcrumb-item active">{{ __('Create Question') }}</li>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Create New Question') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('administration.quiz.question.store') }}" method="POST" enctype="multipart/form-data" id="quizQuestionForm" autocomplete="off">
                    @csrf

                    <div class="row">
                        <!-- Question -->
                        <div class="mb-3 col-md-12">
                            <label class="form-label">{{ __('Question') }} <strong class="text-danger">*</strong></label>
                            <input type="text" name="question" value="{{ old('question') }}" class="form-control form-control-lg" placeholder="{{ __('Enter the question') }}" required />
                            @error('question')
                                <b class="text-danger">{{ $message }}</b>
                            @enderror
                        </div>

                        <!-- Options -->
                        <div class="mb-3 col-md-6">
                            <label class="form-label">{{ __('Option A') }} <strong class="text-danger">*</strong></label>
                            <input type="text" name="option_a" value="{{ old('option_a') }}" class="form-control" placeholder="{{ __('Enter option A') }}" required />
                            @error('option_a')
                                <b class="text-danger">{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">{{ __('Option B') }} <strong class="text-danger">*</strong></label>
                            <input type="text" name="option_b" value="{{ old('option_b') }}" class="form-control" placeholder="{{ __('Enter option B') }}" required />
                            @error('option_b')
                                <b class="text-danger">{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">{{ __('Option C') }} <strong class="text-danger">*</strong></label>
                            <input type="text" name="option_c" value="{{ old('option_c') }}" class="form-control" placeholder="{{ __('Enter option C') }}" required />
                            @error('option_c')
                                <b class="text-danger">{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">{{ __('Option D') }} <strong class="text-danger">*</strong></label>
                            <input type="text" name="option_d" value="{{ old('option_d') }}" class="form-control" placeholder="{{ __('Enter option D') }}" required />
                            @error('option_d')
                                <b class="text-danger">{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="correct_option" class="form-label">Select Correct Option <strong class="text-danger">*</strong></label>
                            <div class="row">
                                <div class="col-md mb-md-0 mb-2">
                                    <div class="form-check custom-option custom-option-basic">
                                        <label class="form-check-label custom-option-content" for="correct_optionA">
                                            <input name="correct_option" class="form-check-input" type="radio" value="A" id="correct_optionA" required />
                                            <span class="custom-option-header pb-0">
                                                <span class="h6 mb-0">A</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-check custom-option custom-option-basic">
                                        <label class="form-check-label custom-option-content" for="correct_optionB">
                                            <input name="correct_option" class="form-check-input" type="radio" value="B" id="correct_optionB" required />
                                            <span class="custom-option-header pb-0">
                                                <span class="h6 mb-0">B</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-check custom-option custom-option-basic">
                                        <label class="form-check-label custom-option-content" for="correct_optionC">
                                            <input name="correct_option" class="form-check-input" type="radio" value="C" id="correct_optionC" required />
                                            <span class="custom-option-header pb-0">
                                                <span class="h6 mb-0">C</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-check custom-option custom-option-basic">
                                        <label class="form-check-label custom-option-content" for="correct_optionD">
                                            <input name="correct_option" class="form-check-input" type="radio" value="D" id="correct_optionD" required />
                                            <span class="custom-option-header pb-0">
                                                <span class="h6 mb-0">D</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-end gap-2 mt-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i>{{ __('Submit Question') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script_links')
    {{--  External Javascript Links --}}
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function() {
            //
        });
    </script>
@endsection
