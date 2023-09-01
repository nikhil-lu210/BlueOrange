@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Roles'))

@section('css_links')
    {{--  External CSS  --}}
    <link href="{{ asset('assets/plugins/switchery/switchery.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/plugins/vertical-timeline/vertical-timeline.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    .timeline-date {
        width: auto !important;
    }
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('All Roles') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item text-capitalize">{{ __('Settings') }}</li>
    <li class="breadcrumb-item text-capitalize">{{ __('Role') }}</li>
    <li class="breadcrumb-item text-capitalize active">{{ __('All Roles') }}</li>
@endsection


@section('breadcrumb_buttons')
    <a href="{{ route('administration.settings.role.create') }}" class="btn btn-outline-dark btn-outline-custom fw-bolder">
        <i class="feather icon-plus"></i>
        <b>Create Role</b>
    </a>
@endsection



@section('content')

<!-- Start row -->
<div class="row">
    <div class="col-md-4">
        <div class="card m-b-30">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-7">
                        <h5 class="card-title mb-0 text-bold">Role Name</h5>
                    </div>
                    <div class="col-5">
                        <button class="btn btn-outline-primary btn-sm float-right font-13">
                            <i class="la la-pencil"></i>
                            Edit
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="activities-history">
                    <div class="activities-history-list">
                        <div class="activities-history-item">
                            <h6>Permission_Group_Name</h6>
                            <p class="mb-0">
                                <span class="badge badge-dark p-2">Create</span>
                                <span class="badge badge-info p-2">Read</span>
                                <span class="badge badge-primary p-2">Update</span>
                                <span class="badge badge-danger p-2">Delete</span>
                            </p>
                        </div>
                    </div>
                    <div class="activities-history-list">
                        <div class="activities-history-item">
                            <h6>Permission_Group_Name</h6>
                            <p class="mb-0">
                                <span class="badge badge-dark p-2">Create</span>
                                <span class="badge badge-info p-2">Read</span>
                                <span class="badge badge-primary p-2">Update</span>
                                <span class="badge badge-danger p-2">Delete</span>
                            </p>
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
    <!-- Timeline js -->
    <script src="{{ asset('assets/plugins/vertical-timeline/vertical-timeline.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Custom Script Here
    </script>
@endsection
