@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Recognition Details'))

@section('css_links')
    {{--  External CSS  --}}
@endsection

@section('custom_css')
    {{--  External CSS  --}}
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('Recognition Details') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Recognition') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.recognition.index') }}">{{ __('All Recognitions') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Recognition Details') }}</li>
@endsection

@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <!-- Recognition Header Card -->
        <div class="card card-border-shadow-primary mb-4 border-0">
            <div class="card-header header-elements">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <div class="avatar avatar-lg">
                            <div class="avatar-initial {{ str_replace('text-', 'bg-', $recognition->score_badge_color) }} rounded">
                                <i class="ti ti-award ti-lg"></i>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h4 class="mb-1">Recognition Details</h4>
                        <p class="text-muted mb-0">{{ $recognition->category }} Recognition</p>
                    </div>
                </div>

                <div class="card-header-elements ms-auto">
                    @can ('Recognition Update')
                        <a href="{{ route('administration.recognition.edit', $recognition) }}" class="btn btn-sm btn-info">
                            <span class="tf-icon ti ti-pencil ti-xs me-1"></span>
                            Edit
                        </a>
                    @endcan
                    @can ('Recognition Delete')
                        <a href="{{ route('administration.recognition.destroy', $recognition) }}" class="btn btn-sm btn-danger confirm-danger">
                            <span class="tf-icon ti ti-trash ti-xs me-1"></span>
                            Delete
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Main Recognition Card -->
        <div class="card card-border-shadow-primary mb-4 border-0">
            <div class="card-body">
                <div class="row">
                    <!-- Score Display -->
                    <div class="col-md-4 text-center mb-4">
                        <div class="card box-shadow-none {{ str_replace('text-', 'bg-', $recognition->score_badge_color) }} border-0 mx-auto" style="width: 140px; height: 140px;">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                <h1 class="mb-0 fw-bold {{ $recognition->score_badge_color }}">{{ $recognition->total_mark }}</h1>
                                <small class="text-muted">/ {{ config('recognition.marks.max') }}</small>
                                <div class="mt-2">
                                    <span class="badge {{ $recognition->score_badge_color }} fs-6">{{ $recognition->score_percentage }}%</span>
                                </div>
                            </div>
                        </div>
                        <h6 class="mt-3 text-muted fw-bold">Recognition Score</h6>
                    </div>

                    <!-- Recognition Info -->
                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card box-shadow-none bg-label-light border-0 h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                <div class="avatar-initial bg-label-primary rounded">
                                                    <i class="ti ti-user ti-sm"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Employee</h6>
                                                <div class="d-flex align-items-center">
                                                    {!! show_user_name_and_avatar($recognition->user, name: null) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card box-shadow-none bg-label-light border-0 h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                <div class="avatar-initial bg-label-info rounded">
                                                    <i class="ti ti-user-check ti-sm"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Recognizer</h6>
                                                <div class="d-flex align-items-center">
                                                    {!! show_user_name_and_avatar($recognition->recognizer, name: null) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card box-shadow-none bg-label-light border-0 h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                <div class="avatar-initial {{ str_replace('text-', 'bg-', $recognition->category_badge_color) }} rounded">
                                                    <i class="{{ $recognition->category_icon }} ti-sm {{ $recognition->category_badge_color }}"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Category</h6>
                                                <span class="badge {{ $recognition->category_badge_color }} fs-6">
                                                    <i class="{{ $recognition->category_icon }} me-1"></i>
                                                    {{ $recognition->category }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card box-shadow-none bg-label-light border-0 h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                <div class="avatar-initial bg-label-warning rounded">
                                                    <i class="ti ti-calendar ti-sm"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Recognition Date</h6>
                                                <div>
                                                    <b class="text-dark">{{ show_date($recognition->created_at) }}</b>
                                                    <br>
                                                    <small class="text-muted">{{ show_time($recognition->created_at) }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Comment Section -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card box-shadow-none bg-label-light border-0">
                            <div class="card-header">
                                <h6 class="mb-0 text-bold">
                                    <i class="ti ti-message-circle me-2"></i>
                                    Recognition Comment
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="comment-content text-dark">
                                    {!! $recognition->comment !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recognition Statistics -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card mb-4 border-0">
            <div class="card-header header-elements">
                <h5 class="mb-0">
                    <i class="ti ti-chart-bar me-2"></i>
                    Employee Recognition Statistics
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="card box-shadow-none bg-label-primary border-0 h-100">
                            <div class="card-body text-center">
                                <div class="d-flex align-items-center justify-content-center mb-3">
                                    <span class="bg-label-primary p-2 rounded">
                                        <i class="ti ti-award ti-xl"></i>
                                    </span>
                                </div>
                                <h3 class="text-primary mb-1">{{ $recognition->user->received_recognitions->count() }}</h3>
                                <small class="text-muted">Total Recognitions</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card box-shadow-none bg-label-success border-0 h-100">
                            <div class="card-body text-center">
                                <div class="d-flex align-items-center justify-content-center mb-3">
                                    <span class="bg-label-success p-2 rounded">
                                        <i class="ti ti-chart-line ti-xl"></i>
                                    </span>
                                </div>
                                <h3 class="text-success mb-1">{{ number_format($recognition->user->received_recognitions->avg('total_mark'), 1) }}</h3>
                                <small class="text-muted">Average Score</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card box-shadow-none bg-label-info border-0 h-100">
                            <div class="card-body text-center">
                                <div class="d-flex align-items-center justify-content-center mb-3">
                                    <span class="bg-label-info p-2 rounded">
                                        <i class="ti ti-sum ti-xl"></i>
                                    </span>
                                </div>
                                <h3 class="text-info mb-1">{{ $recognition->user->received_recognitions->sum('total_mark') }}</h3>
                                <small class="text-muted">Total Score</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card box-shadow-none bg-label-warning border-0 h-100">
                            <div class="card-body text-center">
                                <div class="d-flex align-items-center justify-content-center mb-3">
                                    <span class="bg-label-warning p-2 rounded">
                                        <i class="ti ti-trophy ti-xl"></i>
                                    </span>
                                </div>
                                <h3 class="text-warning mb-1">{{ $recognition->user->received_recognitions->max('total_mark') ?? 0 }}</h3>
                                <small class="text-muted">Highest Score</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Recognitions for this Employee -->
@if($recognition->user->received_recognitions->count() > 1)
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card card-border-shadow-primary mb-4 border-0">
            <div class="card-header header-elements">
                <h5 class="mb-0">
                    <i class="ti ti-history me-2"></i>
                    Other Recognitions for {{ $recognition->user->alias_name }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach($recognition->user->received_recognitions->where('id', '!=', $recognition->id)->take(6) as $otherRecognition)
                        <div class="col-md-6 col-lg-4">
                            <div class="card box-shadow-none border-1 h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <div class="avatar-initial {{ str_replace('text-', 'bg-', $otherRecognition->category_badge_color) }} rounded">
                                                    <i class="{{ $otherRecognition->category_icon }} ti-sm {{ $otherRecognition->category_badge_color }}"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $otherRecognition->category }}</h6>
                                                <small class="text-muted">{{ show_date($otherRecognition->created_at) }}</small>
                                            </div>
                                        </div>
                                        <span class="badge {{ $otherRecognition->score_badge_color }} fs-6">
                                            {{ $otherRecognition->total_mark }}/{{ config('recognition.marks.max') }}
                                        </span>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            {!! show_user_name_and_avatar($otherRecognition->recognizer, name: null) !!}
                                        </div>
                                        <a href="{{ route('administration.recognition.show', $otherRecognition) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="ti ti-eye ti-xs"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- End row -->

@endsection

@section('script_links')
    {{--  External Javascript Links --}}
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Custom Script Here
    </script>
@endsection
