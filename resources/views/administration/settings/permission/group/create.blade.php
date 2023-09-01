@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Create Permission Group'))

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
    <b class="text-uppercase">{{ __('Create Permission Group') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item text-capitalize">{{ __('Settings') }}</li>
    <li class="breadcrumb-item text-capitalize">{{ __('Permission') }}</li>
    <li class="breadcrumb-item text-capitalize active">{{ __('Create Permission Group') }}</li>
@endsection


@section('breadcrumb_buttons')
    <a href="{{ route('administration.settings.permission.group.index') }}" class="btn btn-outline-dark btn-outline-custom fw-bolder">
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
                <h5 class="card-title mb-0">Create New Permission Group</h5>
            </div>
            <div class="card-body">
                <form action="#" method="post" autocomplete="off">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="title">Group Title <span class="required">*</span></label>
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
                        <span class="text-bold">Create Group</span>
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
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Custom Script Here
    </script>
@endsection
