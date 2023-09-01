@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Create Role'))

@section('css_links')
    {{--  External CSS  --}}
    <link href="{{ asset('assets/plugins/vertical-timeline/vertical-timeline.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    .button-group-pills .btn {
        border-radius: 20px;
        line-height: 1.2;
        margin-bottom: 15px;
        margin-left: 10px;
        border-color: #bbbbbb;
        background-color: #fff;
        color: #14a4be;
    }
    .button-group-pills .btn.active {
        border-color: #14a4be;
        background-color: #14a4be;
        color: #fff;
        box-shadow: none;
    }
    .button-group-pills .btn:hover {
        border-color: #158b9f;
        background-color: #158b9f;
        color: #fff;
    }
    .custom-control-input:focus ~ .custom-control-label::before {
        box-shadow: none;
    }
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Create Role') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item text-capitalize">{{ __('Settings') }}</li>
    <li class="breadcrumb-item text-capitalize">{{ __('Role') }}</li>
    <li class="breadcrumb-item text-capitalize active">{{ __('Create Role') }}</li>
@endsection


@section('breadcrumb_buttons')
    <a href="{{ route('administration.settings.role.index') }}" class="btn btn-outline-dark btn-outline-custom fw-bolder">
        <i class="feather icon-arrow-left"></i>
        <b>Back</b>
    </a>
@endsection



@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card m-b-30">
            <div class="card-header">                                
                <h5 class="card-title mb-0">Create New Role</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('administration.settings.role.store') }}" method="post" autocomplete="off">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="name">Role Name <span class="required">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Ex: Super Admin" required>
                            @error('name')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <hr>
                            <b>Select Permissions</b>
                            <div class="activities-history mt-3">
                                @foreach ($permissionGroups as $key => $group)
                                    <div class="activities-history-list">
                                        <div class="activities-history-item">
                                            <div class="custom-checkbox-button mt-3 d-flex">
                                                <div class="form-check-inline checkbox-primary">
                                                    <input type="checkbox" id="{{ $group->slug }}" name="{{ $group->slug }}">
                                                    <label class="select-all pl-2" for="{{ $group->slug }}"><h6>{{ $group->name }}</h6></label>
                                                </div>
                                            </div>
                                            <ul class="pl-0 permissions">
                                                @foreach ($group->permissions as $sl => $permission)
                                                    <li class="d-inline-block m-1 border rounded p-1">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="{{ $permission->slug }}" name="{{ $permission->slug }}">
                                                            <label class="custom-control-label" for="{{ $permission->slug }}">{{ $permission->name }}</label>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-outline-primary btn-outline-custom float-right mt-2">
                        <i class="feather icon-save mr-1"></i>
                        <span class="text-bold">Create Role</span>
                    </button>
                </form>
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
        // custom js
        $(document).ready(function () {
            $(".select-all").click(function () {
                var groupCheckbox = $(this).prev("input[type=checkbox]");
                var permissionCheckboxes = $(this).closest(".activities-history-item").find("ul.permissions input[type=checkbox]");
                permissionCheckboxes.prop("checked", groupCheckbox.prop("checked"));
            });

            $("ul.permissions input[type=checkbox]").click(function () {
                var permissionCheckbox = $(this);
                var groupCheckbox = permissionCheckbox.closest(".activities-history-item").find(".select-all").prev("input[type=checkbox]");
                var allPermissionsChecked = permissionCheckbox.closest("ul.permissions").find("input[type=checkbox]:checked").length === permissionCheckbox.closest("ul.permissions").find("input[type=checkbox]").length;
                permissionCheckbox.closest(".activities-history-item").find(".select-all").prop("checked", allPermissionsChecked);
                if (!permissionCheckbox.prop("checked")) {
                    groupCheckbox.prop("checked", false);
                }
            });
        });
    </script>
    
@endsection
