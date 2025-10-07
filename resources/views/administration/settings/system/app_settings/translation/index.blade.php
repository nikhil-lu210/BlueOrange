@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Translations'))

@section('css_links')
    {{--  External CSS  --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    .translation-source {
        max-width: 300px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .translation-text {
        max-width: 300px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .stat-card {
        border-left: 3px solid #696cff;
    }
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('All Translations') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('System Settings') }}</li>
    <li class="breadcrumb-item">{{ __('App Settings') }}</li>
    <li class="breadcrumb-item active">{{ __('Translations') }}</li>
@endsection


@section('content')


<!-- Filter Card -->
<div class="row justify-content-center">
    <div class="col-md-10">
        <form action="{{ route('administration.settings.system.app_setting.translation.index') }}" method="get" autocomplete="off">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-8">
                            <label class="form-label">Search Translations</label>
                            <input type="text" name="search" value="{{ request()->search ?? old('search') }}" class="form-control" placeholder="Search in source text or translated text..."/>
                            @error('search')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">Filter by Language</label>
                            <select name="locale" class="form-select bootstrap-select w-100" data-style="btn-default">
                                <option value="">All Languages</option>
                                @foreach($localeDetails as $code => $locale)
                                    @if($code !== 'en' && $locale['will_use'])
                                        <option value="{{ $code }}" {{ request()->locale == $code ? 'selected' : '' }}>
                                            {{ $locale['name'] }} ({{ $locale['original'] }}) - {{ strtoupper($code) }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>                       
                    </div>
                    
                    <div class="col-md-12 text-end">
                        @if (request()->search || request()->locale) 
                            <a href="{{ route('administration.settings.system.app_setting.translation.index') }}" class="btn btn-danger confirm-warning">
                                <span class="tf-icon ti ti-refresh ti-xs me-1"></span>
                                Reset Filters
                            </a>
                        @endif
                        <button type="submit" class="btn btn-primary">
                            <span class="tf-icon ti ti-filter ti-xs me-1"></span>
                            Filter Translations
                        </button>
                    </div>
                </div>
            </div>
        </form>        
    </div>
</div>

<!-- Start row -->
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">All Translations</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-responsive" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>Locale</th>
                            <th>Source Text</th>
                            <th>Translated Text</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($translations as $key => $translation)
                            <tr>
                                <th>{{ $translations->firstItem() + $key }}</th>
                                <td>
                                    @php
                                        $localeInfo = $localeDetails[$translation->locale] ?? null;
                                        $displayName = $localeInfo 
                                            ? "{$localeInfo['name']} ({$localeInfo['original']})" 
                                            : $translation->locale;
                                    @endphp
                                    <span class="badge bg-label-primary">
                                        {{ $displayName }}
                                    </span>
                                </td>
                                <td>
                                    <div class="translation-source">
                                        {{ $translation->source_text }}
                                    </div>
                                </td>
                                <td>
                                    <div class="translation-text">
                                        {{ $translation->translated_text }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    @can('App Setting Everything')
                                        <a href="{{ route('administration.settings.system.app_setting.translation.destroy', ['translation' => $translation]) }}" class="btn btn-danger btn-sm btn-icon confirm-danger" title="Delete Translation">
                                            <i class="ti ti-trash"></i>
                                        </a>
                                        <a href="{{ route('administration.settings.system.app_setting.translation.destroy', ['translation' => $translation]) }}" class="btn btn-primary btn-sm btn-icon" title="Edit Translation" data-bs-toggle="modal" data-bs-target="#editTranslationModal" data-translation="{{ json_encode($translation) }}">
                                            <i class="ti ti-pencil"></i>
                                        </a>
                                    @endcan
                                    
                                    <a href="javascript:void(0);" class="btn btn-dark btn-sm btn-icon" title="Show Details" data-bs-toggle="modal" data-bs-target="#showTranslationModal" data-translation="{{ json_encode($translation) }}">
                                        <i class="ti ti-info-hexagon"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-3">
                    {!! pagination($translations, 'end', 'primary') !!}
                </div>
            </div>
        </div>        
    </div>
</div>
<!-- End row -->

{{-- Page Modals --}}
@can('App Setting Read')
    @include('administration.settings.system.app_settings.translation.modals.translation_show')
@endcan
@can('App Setting Update')
    @include('administration.settings.system.app_settings.translation.modals.translation_edit')
@endcan

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Custom Script Here
        $(document).ready(function() {
            $('.bootstrap-select').each(function() {
                if (!$(this).data('bs.select')) { // Check if it's already initialized
                    $(this).selectpicker();
                }
            });
        });
    </script>
    
    <script>
        $(document).ready(function() {
            $('#showTranslationModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var translation = button.data('translation');

                // Update the modal's content.
                var modal = $(this);
                modal.find('.modal-body .role-title').text('Translation Details');
                modal.find('.modal-body .text-muted').text('Details of translation');
                modal.find('.modal-body .translation-locale').text(translation.locale);
                modal.find('.modal-body .translation-source').text(translation.source_text);
                modal.find('.modal-body .translation-translated').text(translation.translated_text);
                modal.find('.modal-body .translation-created').text(translation.created_at);
                modal.find('.modal-body .translation-updated').text(translation.updated_at);
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#editTranslationModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var translation = button.data('translation');

                // Update the modal's content.
                var modal = $(this);
                modal.find('select[name="locale"]').val(translation.locale);
                modal.find('textarea[name="source_text"]').val(translation.source_text);
                modal.find('textarea[name="translated_text"]').val(translation.translated_text);

                // Update the form action URL dynamically
                var formAction = "{{ route('administration.settings.system.app_setting.translation.update', ':id') }}";
                formAction = formAction.replace(':id', translation.id);
                modal.find('form').attr('action', formAction);
            });
        });
    </script>
@endsection

