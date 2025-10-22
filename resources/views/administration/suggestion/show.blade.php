@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Suggestion Details'))

@section('css_links')
    {{--  External CSS  --}}
@endsection

@section('custom_css')
    {{--  External CSS  --}}
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('Suggestion Details') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Suggestion') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.suggestion.my') }}">{{ __('All Suggestions') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Suggestion Details') }}</li>
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
                            <div class="avatar-initial {{ str_replace('text-', 'bg-', $suggestion->type_badge_color) }} rounded">
                                <i class="ti ti-exclamation-circle ti-lg"></i>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h4 class="mb-1">Suggestion Details</h4>
                    </div>
                </div>

                <div class="card-header-elements ms-auto">
                    @can ('Suggestion Read')
                        <a href="{{ URL::previous() }}" class="btn btn-sm btn-secondary">
                            <span class="tf-icon ti ti-arrow-left ti-xs me-1"></span>
                            Back
                        </a>
                    @endcan
                    @can ('Suggestion Delete')
                        <a href="{{ route('administration.suggestion.destroy', $suggestion) }}" class="btn btn-sm btn-danger confirm-danger">
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
                <!-- Meta info: User, Type, Module, Title -->
                <div class="row g-3 align-items-center mb-3">
                    <div class="col-md-6 d-flex align-items-center">
                        {!! show_user_name_and_avatar($suggestion->user) !!}
                    </div>
                    <div class="col-md-6 text-md-end">
                        <span class="badge {{ $suggestion->type_badge_color }} me-1">
                            <i class="ti ti-tag me-1"></i> {{ $suggestion->type_name }}
                        </span>
                        <span class="badge {{ $suggestion->module_badge_color }}">
                            <i class="ti ti-layout-grid me-1"></i> {{ $suggestion->module_name }}
                        </span>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card box-shadow-none border-0 bg-label-primary">
                            <div class="card-body py-3">
                                <h5 class="mb-0 d-flex align-items-center text-primary">
                                    <i class="ti ti-bulb me-2"></i>
                                    <span>{{ $suggestion->title }}</span>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Message Section -->
                <div class="row mt-2">
                    <div class="col-md-12">
                        <div class="card box-shadow-none bg-label-light border-0">
                            <div class="card-header">
                                <h6 class="mb-0 text-bold">
                                    <i class="ti ti-message-circle me-2"></i>
                                    {{ __('Message') }}
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="comment-content text-dark">
                                    {!! $suggestion->message !!}
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
