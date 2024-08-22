@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Attendance'))

@section('css_links')
    {{--  External CSS  --}}
    {{-- Select 2 --}}
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
    
    {{-- Bootstrap Datepicker --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Assign Attendance') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Attendance') }}</li>
    <li class="breadcrumb-item active">{{ __('Assign Attendance') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-8">
        <form action="{{ route('administration.attendance.store') }}" method="POST" autocomplete="off">
            @csrf
            <div class="card mb-4">
                <div class="card-header header-elements">
                    <h5 class="mb-0">Assign Attendance</h5>
            
                    <div class="card-header-elements ms-auto">
                        <a href="{{ route('administration.attendance.index') }}" class="btn btn-sm btn-primary">
                            <span class="tf-icon ti ti-circle ti-xs me-1"></span>
                            All Attendances
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="user_id" class="form-label">Select Employee <strong class="text-danger">*</strong></label>
                            <select name="user_id" id="user_id" class="select2 form-select @error('user_id') is-invalid @enderror" data-allow-clear="true" required>
                                <option value="" selected>Select Employee</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Clockin Date <strong class="text-danger">*</strong></label>
                            <input type="text" name="clock_in_date" value="{{ old('clock_in_date') }}" class="form-control  date-picker" placeholder="YYYY-MM-DD" required/>
                            @error('clock_in_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="clock_in" class="form-label">{{ __('Clockin Time') }} <strong class="text-danger">*</strong></label>
                            <input type="text" id="clock_in" name="clock_in" value="{{ old('clock_in') }}" placeholder="HH:MM" class="form-control time-picker @error('clock_in') is-invalid @enderror" required/>
                            @error('clock_in')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="clock_out" class="form-label">{{ __('Clockout Time') }} <strong class="text-danger">*</strong></label>
                            <input type="text" id="clock_out" name="clock_out" value="{{ old('clock_out') }}" placeholder="HH:MM" class="form-control time-picker @error('clock_out') is-invalid @enderror" required/>
                            @error('clock_out')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="type" class="form-label">Select Clockin Type <strong class="text-danger">*</strong></label>
                            <select name="type" id="type" class="form-select bootstrap-select w-100 @error('type') is-invalid @enderror"  data-style="btn-default" required>
                                <option value="" selected disabled>Select Type</option>
                                <option value="Regular" {{ old('type') == 'Regular' ? 'selected' : '' }}>Regular</option>
                                <option value="Overtime" {{ old('type') == 'Overtime' ? 'selected' : '' }}>Overtime</option>
                            </select>
                            @error('type')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn btn-success">
                            <span class="tf-icon ti ti-check ti-xs me-1"></span>
                            Create Attendance
                        </button>
                    </div>
                </div>
            </div>
        </form>        
    </div>
</div>
<!-- End row -->

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
    <script src="{{asset('assets/js/form-layouts.js')}}"></script>

    <script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js')}}"></script>
    
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}"></script>
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

            $('.date-picker').datepicker({
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                autoclose: true,
                orientation: 'auto right'
            });

            $('.time-picker').flatpickr({
                enableTime: true,
                noCalendar: true
            }); 
        });
    </script>
@endsection