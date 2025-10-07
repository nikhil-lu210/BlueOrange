@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Translations'))

@section('css_links')
    {{--  External CSS  --}}
    <!-- DataTables css -->
    <link href="{{ asset('assets/css/custom_css/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom_css/datatables/datatable.css') }}" rel="stylesheet" type="text/css" />
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

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar me-3">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="ti ti-language ti-md"></i>
                        </span>
                    </div>
                    <div>
                        <p class="mb-0">Total Translations</p>
                        <h4 class="mb-0">{{ $statistics['total_translations'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-3">Translations by Locale</h6>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($statistics['translations_by_locale'] as $locale => $count)
                        @php
                            $localeInfo = $localeDetails[$locale] ?? null;
                            $displayName = $localeInfo ? "{$localeInfo['name']} ({$localeInfo['original']})" : $locale;
                        @endphp
                        <span class="badge bg-label-primary">
                            {{ $displayName }}: {{ $count }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Start row -->
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">All Translations</h5>

                <div class="card-header-elements ms-auto">
                    @can('App Setting Create')
                        <a href="{{ route('administration.settings.system.app_setting.translation.create') }}" class="btn btn-sm btn-primary">
                            <span class="tf-icon ti ti-plus ti-xs me-1"></span>
                            Create Translation
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <table class="table data-table table-bordered table-responsive" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>Locale</th>
                            <th>Source Text</th>
                            <th>Translated Text</th>
                            <th>Created</th>
                            <th>Action</th>
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
                                    <span class="badge bg-label-info">
                                        {{ $displayName }}
                                    </span>
                                </td>
                                <td>
                                    <div class="translation-source" title="{{ $translation->source_text }}">
                                        {{ $translation->source_text }}
                                    </div>
                                </td>
                                <td>
                                    <div class="translation-text" title="{{ $translation->translated_text }}">
                                        {{ $translation->translated_text }}
                                    </div>
                                </td>
                                <td>{{ $translation->created_at->format('d M Y, h:i A') }}</td>
                                <td>
                                    <div class="d-inline-block">
                                        <a href="javascript:void(0);" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="text-primary ti ti-dots-vertical"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end m-0" style="">
                                            @can('App Setting Read')
                                                <a href="{{ route('administration.settings.system.app_setting.translation.show', ['translation' => $translation]) }}" class="dropdown-item">
                                                    <i class="text-primary ti ti-eye"></i>
                                                    View
                                                </a>
                                            @endcan
                                            @can('App Setting Update')
                                                <a href="{{ route('administration.settings.system.app_setting.translation.edit', ['translation' => $translation]) }}" class="dropdown-item">
                                                    <i class="text-primary ti ti-pencil"></i>
                                                    Edit
                                                </a>
                                            @endcan
                                            @can('App Setting Delete')
                                                <div class="dropdown-divider"></div>
                                                <form action="{{ route('administration.settings.system.app_setting.translation.destroy', ['translation' => $translation]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this translation?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="ti ti-trash"></i>
                                                        Delete
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $translations->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End row -->

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
    <!-- Datatable js -->
    <script src="{{ asset('assets/js/custom_js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/datatable.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Custom Script Here
    </script>
@endsection

