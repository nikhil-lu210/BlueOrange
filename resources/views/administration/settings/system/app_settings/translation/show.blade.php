@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Translation Details'))

@section('css_links')
    {{--  External CSS  --}}
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    .detail-label {
        font-weight: 600;
        color: #566a7f;
        margin-bottom: 0.5rem;
    }
    .detail-value {
        color: #697a8d;
        margin-bottom: 1.5rem;
    }
    .translation-box {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 6px;
        border-left: 3px solid #696cff;
        word-wrap: break-word;
    }
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Translation Details') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('System Settings') }}</li>
    <li class="breadcrumb-item">{{ __('App Settings') }}</li>
    <li class="breadcrumb-item"><a href="{{ route('administration.settings.system.app_setting.translation.index') }}">{{ __('Translations') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Details') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Translation Details</h5>
                <div>
                    @can('App Setting Update')
                        <a href="{{ route('administration.settings.system.app_setting.translation.edit', ['translation' => $translation]) }}" class="btn btn-sm btn-primary me-2">
                            <span class="tf-icon ti ti-pencil ti-xs me-1"></span>
                            Edit
                        </a>
                    @endcan
                    <a href="{{ route('administration.settings.system.app_setting.translation.index') }}" class="btn btn-sm btn-secondary">
                        <span class="tf-icon ti ti-arrow-left ti-xs me-1"></span>
                        Back to List
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Locale Information -->
                    <div class="col-md-12 mb-4">
                        <div class="detail-label">Locale</div>
                        <div class="detail-value">
                            <span class="badge bg-label-info">
                                {{ $translation->locale }}
                            </span>
                        </div>
                    </div>

                    <!-- Source Text -->
                    <div class="col-md-12 mb-4">
                        <div class="detail-label">Source Text (English)</div>
                        <div class="translation-box">
                            {{ $translation->source_text }}
                        </div>
                    </div>

                    <!-- Translated Text -->
                    <div class="col-md-12 mb-4">
                        <div class="detail-label">Translated Text</div>
                        <div class="translation-box" style="border-left-color: #71dd37;">
                            {{ $translation->translated_text }}
                        </div>
                    </div>

                    <!-- Metadata -->
                    <div class="col-md-6 mb-3">
                        <div class="detail-label">Created At</div>
                        <div class="detail-value">
                            <i class="ti ti-calendar ti-xs me-1"></i>
                            {{ $translation->created_at->format('d M Y, h:i A') }}
                            <small class="text-muted">({{ $translation->created_at->diffForHumans() }})</small>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="detail-label">Last Updated</div>
                        <div class="detail-value">
                            <i class="ti ti-calendar ti-xs me-1"></i>
                            {{ $translation->updated_at->format('d M Y, h:i A') }}
                            <small class="text-muted">({{ $translation->updated_at->diffForHumans() }})</small>
                        </div>
                    </div>

                    <!-- Character Count Information -->
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <div class="d-flex justify-content-between">
                                <span><strong>Source Text Length:</strong> {{ strlen($translation->source_text) }} characters</span>
                                <span><strong>Translated Text Length:</strong> {{ strlen($translation->translated_text) }} characters</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                @can('App Setting Delete')
                    <div class="mt-4 pt-3 border-top">
                        <form action="{{ route('administration.settings.system.app_setting.translation.destroy', ['translation' => $translation]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this translation? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <span class="tf-icon ti ti-trash ti-xs me-1"></span>
                                Delete Translation
                            </button>
                        </form>
                    </div>
                @endcan
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

