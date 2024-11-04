@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Leave History Details'))

@section('css_links')
    {{--  External CSS  --}}
    {{-- <!-- Vendors CSS --> --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Leave History Details') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Leave History') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.leave.history.my') }}">{{ __('My Leave Histories') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Leave History Details') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0"><a href="{{ route('administration.settings.user.leave_allowed.index', ['user' => $leaveHistory->user]) }}" target="_blank" class="text-bold">{{ $leaveHistory->user->name }}</a> Leave History's Details</h5>
        
                @canany(['Leave History Update', 'Leave History Delete'])
                    <div class="card-header-elements ms-auto">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#approveLeaveModal" class="btn btn-sm btn-success">
                            <span class="tf-icon ti ti-check ti-xs me-1"></span>
                            Approve
                        </button>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#rejectLeaveModal" class="btn btn-sm btn-danger">
                            <span class="tf-icon ti ti-check ti-xs me-1"></span>
                            Reject
                        </button>
                    </div>
                @endcanany
            </div>
            <div class="card-body">
                <div class="row justify-content-left">
                    <div class="col-md-6">
                        @include('administration.leave.includes.leave_history_details')
                    </div>

                    <div class="col-md-6">
                        @include('administration.leave.includes.leave_reason')

                        @if ($leaveHistory->files->count() > 0) 
                            @include('administration.leave.includes.leave_proof_files')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End row -->

{{-- Approve Modal --}}
@include('administration.leave.modals.approve')
{{-- Reject Modal --}}
@include('administration.leave.modals.reject')

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
    {{-- <!-- Vendors JS --> --}}
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js')}}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function () {
            $('.bootstrap-select').each(function() {
                if (!$(this).data('bs.select')) { // Check if it's already initialized
                    $(this).selectpicker();
                }
            });

            $('.time-picker').flatpickr({
                enableTime: true,
                noCalendar: true,
            });
        });
    </script>    
@endsection
