@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Create Permission'))

@section('css_links')
    {{--  External CSS  --}}
    <!-- Select2 css -->
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Create Permission') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item text-capitalize">{{ __('Settings') }}</li>
    <li class="breadcrumb-item text-capitalize">{{ __('Permission') }}</li>
    <li class="breadcrumb-item text-capitalize active">{{ __('Create Permission') }}</li>
@endsection


@section('breadcrumb_buttons')
    <a href="{{ route('administration.settings.permission.index') }}" class="btn btn-outline-dark btn-outline-custom fw-bolder">
        <i class="feather icon-arrow-left"></i>
        <b>Back</b>
    </a>
@endsection



@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card m-b-30">
            <div class="card-header">                                
                <h5 class="card-title mb-0">Create New Permission</h5>
            </div>
            <div class="card-body">
                <form action="#" method="post" autocomplete="off">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="permission_group_id">Permission Group <span class="required">*</span></label>
                            <select class="select2-single form-control @error('permission_group_id') is-invalid @enderror" name="permission_group_id" required>
                                <option value="">Select Permission Group</option>
                                <option value="1">group_name</option>
                            </select>
                            @error('permission_group_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="icon_class">
                                Feather Icon Class 
                                <sup>
                                    <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" data-html="true" title="Write the Feather Icon Class Name Only. <br> [For Example: <b class='text-info'>icon-info</b>]">
                                        <i class="feather icon-info"></i>
                                    </a>
                                </sup>
                            </label>
                            <input type="text" class="form-control @error('icon_class') is-invalid @enderror" name="icon_class" placeholder="Ex: icon-info" required>
                            @error('icon_class')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="title">Permission Title <sup class="required">*</sup></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" placeholder="Ex: Post" required>
                            @error('title')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="col-md-12 text-center">
                            <hr>
                            <b>Default Permissions</b>
                            <p>
                                <span class="badge badge-outline-dark p-2 border">
                                    <i class="feather icon-plus"></i>
                                    Create
                                </span>
                                <span class="badge badge-outline-info p-2 border">
                                    <i class="feather icon-info"></i>
                                    Read
                                </span>
                                <span class="badge badge-outline-primary p-2 border">
                                    <i class="feather icon-edit"></i>
                                    Update
                                </span>
                                <span class="badge badge-outline-danger p-2 border">
                                    <i class="feather icon-trash-2"></i>
                                    Delete
                                </span>
                            </p>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-outline-primary btn-outline-custom float-right mt-2">
                        <i class="feather icon-save mr-1"></i>
                        <span class="text-bold">Create Permission</span>
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
    <!-- Select2 js -->
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom/custom-form-select.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Custom Script Here
        /* -- Bootstrap Tooltip -- */
        $('[data-toggle="tooltip"]').tooltip();
    </script>
@endsection
