@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Attendance Details'))

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
    <b class="text-uppercase">{{ __('Attendance Details') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Attendance') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.attendance.index') }}">{{ __('All Attendances') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Attendance Details') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0"><strong>{{ $attendance->user->name }}</strong> Attendance's Details</h5>
        
                <div class="card-header-elements ms-auto">
                    <a href="#" class="btn btn-sm btn-primary">
                        <span class="tf-icon ti ti-edit ti-xs me-1"></span>
                        Edit Attendance
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row justify-content-left">
                    <div class="col-md-4">
                        <div class="card">
                          <div class="card-body">
                            <div class="rounded-3 text-center mb-2">
                                @if ($attendance->user->hasMedia('avatar'))
                                    <img src="{{ $attendance->user->getFirstMediaUrl('avatar', 'profile_view') }}" alt="{{ $attendance->user->name }} Avatar" class="img-fluid rounded-3" width="100%">
                                @else
                                    <img src="https://fakeimg.pl/300/dddddd/?text=No-Image" alt="{{ $attendance->user->name }} No Avatar" class="img-fluid">
                                @endif
                            </div>
                            <h4 class="mb-2 pb-1 text-center">{{ $attendance->user->name }}</h4>
                            <h6 class="mb-2 pb-1 text-center">{{ $attendance->user->roles[0]->name }}</h6>
                          </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card mb-4">
                            <div class="card-body">
                                <small class="card-text text-uppercase">Attendance Details</small>
                                <dl class="row mt-3 mb-1">
                                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-calendar-event text-heading"></i>
                                        <span class="fw-medium mx-2 text-heading">Date:</span>
                                    </dt>
                                    <dd class="col-sm-8">
                                        <span>{{ show_date($attendance->clock_in_date) }}</span>
                                    </dd>
                                </dl>
                                <dl class="row mb-1">
                                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-clock-plus text-heading"></i>
                                        <span class="fw-medium mx-2 text-heading">Clock In:</span>
                                    </dt>
                                    <dd class="col-sm-8">
                                        <span>{{ show_time($attendance->clock_in) }}</span>
                                    </dd>
                                </dl>
                                <dl class="row mb-1">
                                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-clock-minus text-heading"></i>
                                        <span class="fw-medium mx-2 text-heading">Clock Out:</span>
                                    </dt>
                                    <dd class="col-sm-8">
                                        <span>
                                            @isset($attendance->clock_out)
                                                @if (show_date($attendance->clock_in) != show_date($attendance->clock_out))
                                                    <span class="text-warning text-bold">
                                                        {{ show_date_time($attendance->clock_out) }}
                                                    </span>
                                                @else
                                                    {{ show_time($attendance->clock_out) }}
                                                @endif
                                            @else
                                                <b class="text-success text-uppercase">Running</b>
                                            @endisset
                                        </span>
                                    </dd>
                                </dl>
                                <dl class="row mb-1">
                                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-hourglass text-heading"></i>
                                        <span class="fw-medium mx-2 text-heading">Total Worked:</span>
                                    </dt>
                                    <dd class="col-sm-8">
                                        @isset($attendance->total_time)
                                            <b>
                                                {!! total_time($attendance->total_time) !!}
                                            </b>
                                        @else
                                            <b class="text-success text-uppercase">Running</b>
                                        @endisset
                                    </dd>
                                </dl>
                                <dl class="row mb-1">
                                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-access-point text-heading"></i>
                                        <span class="fw-medium mx-2 text-heading">Clock-In IP:</span>
                                    </dt>
                                    <dd class="col-sm-8">
                                        <span>{{ $attendance->ip_address }}</span>
                                    </dd>
                                </dl>
                                <dl class="row mb-1">
                                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-world-latitude text-heading"></i>
                                        <span class="fw-medium mx-2 text-heading">Latitude:</span>
                                    </dt>
                                    <dd class="col-sm-8">
                                        <span>{{ $attendance->latitude }}</span>
                                    </dd>
                                </dl>
                                <dl class="row mb-1">
                                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-world-longitude text-heading"></i>
                                        <span class="fw-medium mx-2 text-heading">Longitude:</span>
                                    </dt>
                                    <dd class="col-sm-8">
                                        <span>{{ $attendance->longitude }}</span>
                                    </dd>
                                </dl>
                                <dl class="row mb-1">
                                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-world-check text-heading"></i>
                                        <span class="fw-medium mx-2 text-heading">Country:</span>
                                    </dt>
                                    <dd class="col-sm-8">
                                        <span>{{ $attendance->country }}</span>
                                    </dd>
                                </dl>
                                <dl class="row mb-1">
                                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-location text-heading"></i>
                                        <span class="fw-medium mx-2 text-heading">City:</span>
                                    </dt>
                                    <dd class="col-sm-8">
                                        <span>{{ $attendance->city }}</span>
                                    </dd>
                                </dl>
                                <dl class="row mb-1">
                                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-zip text-heading"></i>
                                        <span class="fw-medium mx-2 text-heading">Zip Code:</span>
                                    </dt>
                                    <dd class="col-sm-8">
                                        <span>{{ $attendance->zip_code }}</span>
                                    </dd>
                                </dl>
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
        $(document).ready(function () {
            // 
        });
    </script>    
@endsection
