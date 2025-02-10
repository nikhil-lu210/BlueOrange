@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Attendance Issue Details'))

@section('css_links')
    {{--  External CSS  --}}
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    .btn-block {
        width: 100%;
    }
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Attendance Issue Details') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Attendance') }}</li>
    <li class="breadcrumb-item">{{ __('Attendance Issue') }}</li>
    <li class="breadcrumb-item">
        @can ('Update Attenance Issue') 
            <a href="{{ route('administration.attendance.issue.index') }}">{{ __('All Issues') }}</a>
        @else
            <a href="{{ route('administration.attendance.issue.my') }}">{{ __('My Issues') }}</a>
        @endcan
    </li>
    <li class="breadcrumb-item active">{{ __('Attendance Issue Details') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                <div class="flex-grow-1 mt-4">
                    <div class="d-flex align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                        <div class="user-profile-info">
                            <h4 class="mb-0">{{ $issue->title }}</h4>
                            <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                <li class="list-inline-item d-flex gap-1" data-bs-toggle="tooltip" title="Issue Created By" data-bs-placement="bottom">
                                    <i class="ti ti-crown"></i> 
                                    {{ $issue->user->name }}
                                </li>
                                <li class="list-inline-item d-flex gap-1" data-bs-toggle="tooltip" title="Issue Creation Date & Time">
                                    <i class="ti ti-calendar"></i> 
                                    {{ show_date_time($issue->created_at) }}
                                </li>
                            </ul>
                        </div>
                        @if ($issue->status === 'Pending') 
                            @can ('Announcement Update') 
                                <div class="card-header-elements ms-auto">
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectAttendanceIssueModal">
                                        <span class="tf-icon ti ti-ban ti-xs me-1"></span>
                                        {{ __('Reject') }}
                                    </button>
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#approveAttendanceIssueModal">
                                        <span class="tf-icon ti ti-check ti-xs me-1"></span>
                                        {{ __('Approve') }}
                                    </button>
                                </div>
                            @endcan
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Attendance Issue Details --}}
    <div class="col-md-7">
        @include('administration.attendance.issue.partials._issue_details')
    </div>

    @if ($issue->attendance) 
        <div class="col-md-5">
            @include('administration.attendance.issue.partials._attendance_details')
        </div>
    @endif
</div>


{{-- Approve Issue Modal --}}
@include('administration.attendance.issue.modals.approve_modal')
{{-- reject Issue Modal --}}
@include('administration.attendance.issue.modals.reject_modal')
@endsection


@section('script_links')
    {{--  External Javascript Links --}}
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
@endsection
