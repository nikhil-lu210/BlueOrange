@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Holidays'))

@section('css_links')
    {{--  External CSS  --}}
    <!-- DataTables css -->
    <link href="{{ asset('assets/css/custom_css/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom_css/datatables/datatable.css') }}" rel="stylesheet" type="text/css" />
    
    {{-- Bootstrap Datepicker --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />

    {{-- Bootstrap Select --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('All Holidays') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('System Settings') }}</li>
    <li class="breadcrumb-item active">{{ __('Holidays') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-4">
        <form action="{{ route('administration.settings.system.holiday.index') }}" method="get" autocomplete="off">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label class="form-label">Holidays Of</label>
                            <input type="text" name="month_year" value="{{ request()->month_year ?? old('month_year') }}" class="form-control month-year-picker" placeholder="MM yyyy" tabindex="-1"/>
                            @error('month_year')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>                        
                    </div>
                    
                    <div class="col-md-12 text-end">
                        @if (request()->month_year) 
                            <a href="{{ route('administration.settings.system.holiday.index') }}" class="btn btn-danger confirm-warning">
                                <span class="tf-icon ti ti-refresh ti-xs me-1"></span>
                                Reset Filters
                            </a>
                        @endif
                        <button type="submit" class="btn btn-primary">
                            <span class="tf-icon ti ti-filter ti-xs me-1"></span>
                            Filter Holidays
                        </button>
                    </div>
                </div>
            </div>
        </form>        
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">All Holidays</h5>
        
                @can ('Holiday Create') 
                    <div class="card-header-elements ms-auto">
                        <a href="javascript:void(0);" class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#importHolidayModal">
                            <span class="tf-icon ti ti-upload ti-xs me-1"></span>
                            Import Holidays
                        </a>
                        <a href="javascript:void(0);" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignNewHolidayModal">
                            <span class="tf-icon ti ti-plus ti-xs me-1"></span>
                            Assign Holiday
                        </a>
                    </div>
                @endcan
            </div>
            <div class="card-body">
                <table class="table data-table table-bordered table-responsive" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>Date</th>
                            <th>Holiday</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($holidays as $key => $holiday) 
                            <tr>
                                <th>{{ serial($holidays, $key) }}</th>
                                <td>{{ show_date($holiday->date) }}</td>
                                <td>{{ $holiday->name }}</td>
                                <td>{{ $holiday->description }}</td>
                                <td>
                                    @php
                                        $status = $holiday->is_active == true ? 'Active' : 'Inactive';
                                        $background = $holiday->is_active == true ? 'bg-success' : 'bg-danger';
                                    @endphp
                                    <span class="badge {{ $background }}">{{ $status }}</span>
                                </td>
                                <td>
                                    @can ('Holiday Everything')
                                        <div class="d-inline-block">
                                            <a href="javascript:void(0);" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="text-primary ti ti-dots-vertical"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end m-0" style="">
                                                <a href="javascript:void(0);" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editHolidayModal" data-holiday="{{ json_encode($holiday) }}">
                                                    <i class="text-primary ti ti-pencil"></i> 
                                                    Edit
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a href="{{ route('administration.settings.system.holiday.destroy', ['holiday' => $holiday]) }}" class="dropdown-item text-danger delete-record confirm-danger">
                                                    <i class="ti ti-trash"></i> 
                                                    Delete
                                                </a>
                                            </div>
                                        </div>
                                    @endcan
                                    <a href="javascript:void(0);" class="btn btn-sm btn-icon item-edit" title="Show Details" data-bs-toggle="modal" data-bs-target="#showHolidayModal" data-holiday="{{ json_encode($holiday) }}">
                                        <i class="text-primary ti ti-info-hexagon"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>        
    </div>
</div>
<!-- End row -->


{{-- Page Modal --}}
@can ('Holiday Create')
    @include('administration.settings.system.holiday.modals.holiday_import')
    @include('administration.settings.system.holiday.modals.holiday_create')
    @include('administration.settings.system.holiday.modals.holiday_edit')
@endcan
@include('administration.settings.system.holiday.modals.holiday_show')

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
    {{-- <!-- Datatable js --> --}}
    <script src="{{ asset('assets/js/custom_js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/datatable.js') }}"></script>

    {{-- <!-- Vendors JS --> --}}
    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>

    {{-- Bootstrap Select --}}
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Custom Script Here
        $(document).ready(function() {
            $('.date-picker').datepicker({
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                autoclose: true,
                orientation: 'auto right'
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

    <script>
        $(document).ready(function() {
            $('#showHolidayModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var holiday = button.data('holiday');

                // Update the modal's content.
                var modal = $(this);
                modal.find('.modal-body .role-title').text('Holiday Details');
                modal.find('.modal-body .text-muted').text('Details of ' + holiday.name);
                modal.find('.modal-body .holiday-title').text(holiday.name);
                modal.find('.modal-body .holiday-date').text(holiday.date);
                modal.find('.modal-body .holiday-description').text(holiday.description);
                
                if (holiday.is_active == true) {
                    modal.find('.modal-body .holiday-status').text('Active');
                } else {
                    modal.find('.modal-body .holiday-status').text('Inactive');
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#editHolidayModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var holiday = button.data('holiday');

                // Update the modal's content.
                var modal = $(this);
                modal.find('input[name="name"]').val(holiday.name);
                modal.find('input[name="date"]').val(holiday.date);
                modal.find('textarea[name="description"]').val(holiday.description);

                // Update the status checkbox
                var statusCheckbox = modal.find('input[name="is_active"]');
                statusCheckbox.prop('checked', holiday.is_active);

                // Update the form action URL dynamically if needed
                var formAction = "{{ route('administration.settings.system.holiday.update', ':id') }}";
                formAction = formAction.replace(':id', holiday.id);
                modal.find('form').attr('action', formAction);
            });
        });
    </script>
@endsection