@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Create Recognition'))

@section('css_links')
    {{--  External CSS  --}}
    {{-- Select 2 --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
        .score-slider {
            width: 100%;
        }
        .score-display {
            font-size: 24px;
            font-weight: bold;
            color: #2c2c54;
            text-align: center;
            margin: 10px 0;
        }
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('Create Recognition') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Recognition') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.recognition.index') }}">{{ __('All Recognitions') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Create Recognition') }}</li>
@endsection

@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-border-shadow-primary mb-4 border-0">
            <div class="card-header header-elements">
                <h5 class="mb-0">Create New Recognition</h5>

                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.recognition.index') }}" class="btn btn-sm btn-primary">
                        <span class="tf-icon ti ti-circle ti-xs me-1"></span>
                        All Recognitions
                    </a>
                </div>
            </div>
            <!-- Account -->
            <div class="card-body">
                <form id="recognitionForm" action="{{ route('administration.recognition.store') }}" method="post" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-8">
                            <label for="user_id" class="form-label">{{ __('Employee') }} <strong class="text-danger">*</strong></label>
                            <select name="user_id" id="user_id" class="select2 form-select @error('user_id') is-invalid @enderror" data-allow-clear="true" required>
                                <option value="" disabled selected>{{ __('Select Employee') }}</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->alias_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-4">
                            <label for="category" class="form-label">{{ __('Category') }} <strong class="text-danger">*</strong></label>
                            <select name="category" id="category" class="form-select select2 @error('category') is-invalid @enderror" required>
                                <option value="" disabled selected>{{ __('Select Category') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-12">
                            <label for="total_mark" class="form-label">{{ __('Recognition Score') }} <strong class="text-danger">*</strong></label>
                            <div class="score-display p-2 bg-label-primary rounded" id="scoreDisplay">{{ config('recognition.marks.min') }}</div>
                            <input type="range" name="total_mark" id="total_mark" 
                                   min="{{ config('recognition.marks.min') }}" 
                                   max="{{ config('recognition.marks.max') }}" 
                                   step="{{ config('recognition.marks.step', 1) }}"
                                   value="{{ old('total_mark', config('recognition.marks.min')) }}"
                                   class="form-range score-slider @error('total_mark') is-invalid @enderror" 
                                   required />
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">{{ config('recognition.marks.min') }}</small>
                                <small class="text-muted">{{ config('recognition.marks.max') }}</small>
                            </div>
                            @error('total_mark')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-12">
                            <label for="comment" class="form-label">{{ __('Comment') }} <strong class="text-danger">*</strong></label>
                            <textarea name="comment" id="comment" rows="4" 
                                      class="form-control @error('comment') is-invalid @enderror" 
                                      placeholder="Please provide details about why this employee deserves recognition..."
                                      required>{{ old('comment') }}</textarea>
                            <div class="form-text">
                                <span id="commentCount">0</span> / 1000 characters
                            </div>
                            @error('comment')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-end">
                            <a href="{{ route('administration.recognition.index') }}" class="btn btn-secondary me-2">
                                <span class="tf-icon ti ti-x ti-xs me-1"></span>
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <span class="tf-icon ti ti-check ti-xs me-1"></span>
                                Create Recognition
                            </button>
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
            // Score slider functionality
            const scoreSlider = document.getElementById('total_mark');
            const scoreDisplay = document.getElementById('scoreDisplay');
            
            scoreSlider.addEventListener('input', function() {
                scoreDisplay.textContent = this.value;
            });

            // Comment character counter
            const commentTextarea = document.getElementById('comment');
            const commentCount = document.getElementById('commentCount');
            
            commentTextarea.addEventListener('input', function() {
                const length = this.value.length;
                commentCount.textContent = length;
                
                if (length > 1000) {
                    commentCount.style.color = 'red';
                } else if (length > 800) {
                    commentCount.style.color = 'orange';
                } else {
                    commentCount.style.color = 'inherit';
                }
            });

            // Initialize character count on page load
            commentTextarea.dispatchEvent(new Event('input'));
        });
    </script>
@endsection
