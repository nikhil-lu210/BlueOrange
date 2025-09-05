@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Work Schedule Details'))

@section('css_links')
    {{--  External CSS  --}}
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    .work-type-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    .work-type-client { background-color: #28a745; color: white; }
    .work-type-internal { background-color: #007bff; color: white; }
    .work-type-bench { background-color: #ffc107; color: #212529; }
    .info-card {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .work-item-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        background-color: white;
    }
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('Work Schedule Details') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Work Schedule') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.work_schedule.index') }}">{{ __('All Work Schedules') }}</a>
    </li>
    <li class="breadcrumb-item">{{ __('Schedule Details') }}</li>
    <li class="breadcrumb-item active">{{ get_employee_name($workSchedule->user) }} - {{ $workSchedule->weekday }}</li>
@endsection

@section('content')

<!-- Start row -->
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Work Schedule Details</h5>

                <div class="card-header-elements ms-auto">
                    @can('User Update')
                        <a href="{{ route('administration.work_schedule.edit', $workSchedule) }}" class="btn btn-sm btn-info me-2">
                            <span class="tf-icon ti ti-pencil ti-xs me-1"></span>
                            Edit Schedule
                        </a>
                    @endcan
                    <a href="{{ route('administration.work_schedule.index') }}" class="btn btn-sm btn-primary">
                        <span class="tf-icon ti ti-arrow-left ti-xs me-1"></span>
                        Back to All Schedules
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Employee Information -->
                <div class="info-card">
                    <h6 class="mb-3"><i class="ti ti-user me-2"></i>Employee Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Name:</strong> {{ get_employee_name($workSchedule->user) }}
                        </div>
                        <div class="col-md-6">
                            <strong>Email:</strong> {{ $workSchedule->user->email }}
                        </div>
                    </div>
                </div>

                <!-- Schedule Information -->
                <div class="info-card">
                    <h6 class="mb-3"><i class="ti ti-calendar me-2"></i>Schedule Information</h6>
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Work Date:</strong><br>
                            <span class="badge bg-primary">{{ show_date($workSchedule->work_date) }}</span>
                        </div>
                        <div class="col-md-3">
                            <strong>Weekday:</strong><br>
                            <span class="badge bg-info">{{ $workSchedule->weekday }}</span>
                        </div>
                        <div class="col-md-3">
                            <strong>Status:</strong><br>
                            @if($workSchedule->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <strong>Total Duration:</strong><br>
                            <span class="badge bg-warning">{{ $workSchedule->formatted_total_duration }}</span>
                        </div>
                    </div>
                </div>

                <!-- Shift Information -->
                <div class="info-card">
                    <h6 class="mb-3"><i class="ti ti-clock me-2"></i>Shift Information</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Start Time:</strong> {{ $workSchedule->employeeShift->start_time }}
                        </div>
                        <div class="col-md-4">
                            <strong>End Time:</strong> {{ $workSchedule->employeeShift->end_time }}
                        </div>
                        <div class="col-md-4">
                            <strong>Total Shift Time:</strong> {{ $workSchedule->employeeShift->total_time }}
                        </div>
                    </div>
                </div>

                <!-- Work Items -->
                <div class="info-card">
                    <h6 class="mb-3"><i class="ti ti-briefcase me-2"></i>Work Items ({{ $workSchedule->workScheduleItems->count() }})</h6>

                    @if($workSchedule->workScheduleItems->count() > 0)
                        @foreach($workSchedule->workScheduleItems as $item)
                            <div class="work-item-card">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span class="work-type-badge work-type-{{ strtolower($item->work_type) }}">
                                            {{ $item->work_type }}
                                        </span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>{{ $item->work_title }}</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <i class="ti ti-clock me-1"></i>
                                        {{ $item->start_time }} - {{ $item->end_time }}
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Duration:</strong> {{ floor($item->duration_minutes / 60) }}h {{ $item->duration_minutes % 60 }}m
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="ti ti-briefcase me-2"></i>
                            No work items assigned
                        </div>
                    @endif
                </div>

                <!-- Work Breakdown by Type -->
                @if($workSchedule->workScheduleItems->count() > 0)
                    <div class="info-card">
                        <h6 class="mb-3"><i class="ti ti-chart-pie me-2"></i>Work Breakdown by Type</h6>
                        <div class="row">
                            @foreach($workSchedule->work_breakdown as $type => $breakdown)
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <span class="work-type-badge work-type-{{ strtolower($type) }}">
                                            {{ $type }}
                                        </span>
                                        <br>
                                        <strong>{{ $breakdown['count'] }} items</strong>
                                        <br>
                                        <small class="text-muted">{{ $breakdown['formatted_duration'] }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Timestamps -->
                <div class="info-card">
                    <h6 class="mb-3"><i class="ti ti-info-circle me-2"></i>Timestamps</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Created:</strong> {{ show_date($workSchedule->created_at) }}
                        </div>
                        <div class="col-md-6">
                            <strong>Last Updated:</strong> {{ show_date($workSchedule->updated_at) }}
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
