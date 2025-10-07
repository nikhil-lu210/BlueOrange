@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Create Translation'))

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
    <b class="text-uppercase">{{ __('Create New Translation') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('System Settings') }}</li>
    <li class="breadcrumb-item">{{ __('App Settings') }}</li>
    <li class="breadcrumb-item"><a href="{{ route('administration.settings.system.app_setting.translation.index') }}">{{ __('Translations') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Create') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Create New Translation</h5>
                <a href="{{ route('administration.settings.system.app_setting.translation.index') }}" class="btn btn-sm btn-secondary">
                    <span class="tf-icon ti ti-arrow-left ti-xs me-1"></span>
                    Back to List
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('administration.settings.system.app_setting.translation.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <!-- Locale -->
                        <div class="col-md-12 mb-3">
                            <label for="locale" class="form-label">Locale <span class="text-danger">*</span></label>
                            <select name="locale" id="locale" class="form-select @error('locale') is-invalid @enderror" required>
                                <option value="">Select Locale</option>
                                @foreach($localeDetails as $code => $locale)
                                    @if($code !== 'en' && $locale['will_use'])
                                        <option value="{{ $code }}" {{ old('locale') == $code ? 'selected' : '' }}>
                                            {{ $locale['name'] }} ({{ $locale['original'] }}) - {{ strtoupper($code) }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('locale')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Source Text -->
                        <div class="col-md-12 mb-3">
                            <label for="source_text" class="form-label">Source Text (English) <span class="text-danger">*</span></label>
                            <textarea 
                                name="source_text" 
                                id="source_text" 
                                rows="4" 
                                class="form-control @error('source_text') is-invalid @enderror" 
                                placeholder="Enter the original English text..."
                                required>{{ old('source_text') }}</textarea>
                            @error('source_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Maximum {{ config('translation.character_limits.source_text', 5000) }} characters</small>
                        </div>

                        <!-- Translated Text -->
                        <div class="col-md-12 mb-3">
                            <label for="translated_text" class="form-label">Translated Text <span class="text-danger">*</span></label>
                            <textarea 
                                name="translated_text" 
                                id="translated_text" 
                                rows="4" 
                                class="form-control @error('translated_text') is-invalid @enderror" 
                                placeholder="Enter the translated text..."
                                required>{{ old('translated_text') }}</textarea>
                            @error('translated_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Maximum {{ config('translation.character_limits.translated_text', 10000) }} characters</small>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary me-2">
                            <span class="tf-icon ti ti-device-floppy ti-xs me-1"></span>
                            Create Translation
                        </button>
                        <a href="{{ route('administration.settings.system.app_setting.translation.index') }}" class="btn btn-label-secondary">
                            Cancel
                        </a>
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
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Custom Script Here
    </script>
@endsection

