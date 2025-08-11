@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('ERS Report'))

@section('css_links')
    {{--  External CSS  --}}
    <!-- DataTables css -->
    <link href="{{ asset('assets/css/custom_css/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom_css/datatables/datatable.css') }}" rel="stylesheet" type="text/css" />

    {{-- Select 2 --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />

    {{-- Bootstrap Datepicker --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('All ERS Reports') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Recognition') }}</li>
    <li class="breadcrumb-item active">{{ __('All ERS Reports') }}</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <form action="{{ route('administration.employee_recognition.reports') }}" method="get" autocomplete="off">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-5">
                            <label class="form-label">{{ __('Reports Of') }}</label>
                                <input type="text" name="month" value="{{ $month->format('M Y') ?? old('month') }}" class="form-control month-year-picker" placeholder="MM yyyy" tabindex="-1"/>
                                @error('month')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        <div class="mb-3 col-md-7">
                            <label for="badge" class="form-label">{{ __('Select Badge') }}</label>
                            <select name="badge" id="badge" class="form-select bootstrap-select w-100 @error('badge') is-invalid @enderror"  data-style="btn-default">
                                <option value="">{{ __('All Badges') }}</option>
                                @foreach($badgeOptions as $code => $text)
                                    <option value="{{ $code }}" {{ (isset($badge) && $badge===$code) ? 'selected' : '' }}>{{ $text }}</option>
                                @endforeach
                            </select>
                            @error('badge')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12 text-end">
                        <button type="submit" name="filter_reports" value="true" class="btn btn-primary">
                            <span class="tf-icon ti ti-filter ti-xs me-1"></span>
                            {{ __('Filter Reports') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@if (!empty($teamComparison) && $teamComparison->count() > 0) 
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-border-shadow-primary mb-4 border-0">
                <div class="card-body row">
                    <div class="d-flex justify-content-between flex-wrap gap-3 me-3">
                        @foreach($teamComparison as $team)
                            <div class="d-flex align-items-center gap-3">
                                <span class="bg-label-{{ ers_average_color($team->avg_score) }} p-1 rounded">
                                    {!! show_user_avatar($team->teamLeader, 'rounded') !!}
                                </span>
                                <div class="content-right">
                                    <h5 class="text-{{ ers_average_color($team->avg_score) }} text-bold mb-0">{{ number_format($team->avg_score, 2) }}</h5>
                                    <small class="mb-0 text-muted">
                                        Team -
                                        <a href="{{ route('administration.settings.user.show.profile', ['user' => $team->teamLeader]) }}" target="_blank" class="text-bold text-primary">
                                            {{ $team->teamLeader->alias_name }}
                                        </a>
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Top Performers of') }} <b class="text-bold text-dark">{{ $month->format('F Y') }}</b></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive-md table-responsive-sm w-100">
                            <table class="table data-table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('Employee') }}</th>
                                        <th>{{ __('Team Leader') }}</th>
                                        <th class="text-center">{{ __('Score') }}</th>
                                        <th class="text-center">{{ __('Badge') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topPerformers as $i => $row)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{!! show_user_name_and_avatar($row->employee, role: null) !!}</td>
                                            <td>{!! show_user_name_and_avatar($row->teamLeader, name: null) !!}</td>
                                            @php
                                                $bd = $topBadges[$row->id] ?? null;
                                            @endphp
                                            <td class="text-center">
                                                <span class="badge {{ ers_badge_class($bd['code']) }}">{{ $row->total_score }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge {{ ers_badge_class($bd['code']) }}">
                                                    {{ $bd ? ($bd['emoji'].' '. __($bd['label'])) : '' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">{{ __('No data') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



@section('script_links')
    {{--  External Javascript Links --}}
    <!-- Datatable js -->
    <script src="{{ asset('assets/js/custom_js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/datatable.js') }}"></script>

    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
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

            $('.month-year-picker').datepicker({
                format: 'MM yyyy',         // Display format to show full month name and year
                minViewMode: 'months',     // Only allow month selection
                todayHighlight: true,
                autoclose: true,
                orientation: 'auto right'
            });
        });
    </script>
@endsection
