@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Announcement'))

@section('css_links')
    {{--  External CSS  --}}
    <!-- DataTables css -->
    <link href="{{ asset('assets/css/custom_css/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom_css/datatables/datatable.css') }}" rel="stylesheet" type="text/css" />
    
    {{-- Select 2 --}}
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
    
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
    <b class="text-uppercase">{{ __('All Announcements') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Announcement') }}</li>
    <li class="breadcrumb-item active">{{ __('All Announcements') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-8">
        <form action="{{ route('administration.announcement.index') }}" method="get" autocomplete="off">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-7">
                            <label for="announcer_id" class="form-label">Select Announcer</label>
                            <select name="announcer_id" id="announcer_id" class="select2 form-select @error('announcer_id') is-invalid @enderror" data-allow-clear="true">
                                <option value="" {{ is_null(request()->announcer_id) ? 'selected' : '' }}>Select Announcer</option>
                                @foreach ($roles as $role)
                                    <optgroup label="{{ $role->name }}">
                                        @foreach ($role->users as $announcer)
                                            <option value="{{ $announcer->id }}" {{ $announcer->id == request()->announcer_id ? 'selected' : '' }}>
                                                {{ $announcer->name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            @error('announcer_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        
                        <div class="mb-3 col-md-5">
                            <label class="form-label">Announcements Of</label>
                            <input type="text" name="created_month_year" value="{{ request()->created_month_year ?? old('created_month_year') }}" class="form-control month-year-picker" placeholder="MM yyyy" tabindex="-1"/>
                            @error('created_month_year')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>                        
                    </div>
                    
                    <div class="col-md-12 text-end">
                        @if (request()->announcer_id || request()->created_month_year) 
                            <a href="{{ route('administration.announcement.index') }}" class="btn btn-danger confirm-warning">
                                <span class="tf-icon ti ti-refresh ti-xs me-1"></span>
                                Reset Filters
                            </a>
                        @endif
                        <button type="submit" class="btn btn-primary">
                            <span class="tf-icon ti ti-filter ti-xs me-1"></span>
                            Filter Announcements
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
                <h5 class="mb-0">All Announcements</h5>
        
                <div class="card-header-elements ms-auto">
                    @can ('Announcement Create') 
                        <a href="{{ route('administration.announcement.create') }}" class="btn btn-sm btn-primary">
                            <span class="tf-icon ti ti-plus ti-xs me-1"></span>
                            Create Announcement
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <table class="table data-table table-bordered table-responsive" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>Announced At</th>
                            <th>Announcement</th>
                            <th>Announcer</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($announcements as $key => $announcement) 
                            <tr>
                                <th>#{{ serial($announcements, $key) }}</th>
                                <td>
                                    <b>{{ show_date($announcement->created_at) }}</b>
                                    <br>
                                    <span>
                                        at
                                        <b>{{ show_time($announcement->created_at) }}</b>
                                    </span>
                                </td>
                                <td>
                                    <b>{{ $announcement->title }}</b>
                                    <br>
                                    @if (!is_null($announcement->recipients))
                                        <small class="text-primary text-bold cursor-pointer text-left" title="
                                            @foreach ($announcement->recipients as $recipient)
                                                <small>{{ show_user_data($recipient, 'name') }}</small>
                                                <br>
                                            @endforeach
                                        ">
                                            {{ count($announcement->recipients) }} Recipients
                                        </small>
                                    @else
                                        <small class="text-muted">All Recipients</small>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('administration.settings.user.show.profile', ['user' => $announcement->announcer]) }}" target="_blank" class="text-bold text-primary">
                                        {{ $announcement->announcer->name }}
                                    </a>
                                    <br>
                                    <small class="text-muted">{{ $announcement->announcer->roles[0]->name }}</small>
                                </td>
                                <td class="text-center">
                                    @can ('Announcement Delete') 
                                        <a href="{{ route('administration.announcement.destroy', ['announcement' => $announcement]) }}" class="btn btn-sm btn-icon btn-danger confirm-danger" data-bs-toggle="tooltip" title="Delete Announcement?">
                                            <i class="text-white ti ti-trash"></i>
                                        </a>
                                    @endcan
                                    @can ('Announcement Update') 
                                        <a href="{{ route('administration.announcement.edit', ['announcement' => $announcement]) }}" class="btn btn-sm btn-icon btn-info" data-bs-toggle="tooltip" title="Edit Announcement?">
                                            <i class="text-white ti ti-pencil"></i>
                                        </a>
                                    @endcan
                                    @can ('Announcement Read') 
                                        <a href="{{ route('administration.announcement.show', ['announcement' => $announcement]) }}" class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" title="Show Details">
                                            <i class="text-white ti ti-info-hexagon"></i>
                                        </a>
                                    @endcan
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

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
    <!-- Datatable js -->
    <script src="{{ asset('assets/js/custom_js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/datatable.js') }}"></script>

    <script src="{{asset('assets/js/form-layouts.js')}}"></script>

    <script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js')}}"></script>
    
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
