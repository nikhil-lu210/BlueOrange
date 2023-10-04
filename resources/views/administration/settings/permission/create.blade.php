@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Create New Permission'))

@section('css_links')
    {{--  External CSS  --}}
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Create New Permission') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Role & Permission') }}</li>
    <li class="breadcrumb-item">{{ __('Permission') }}</li>
    <li class="breadcrumb-item active">{{ __('Create New Permission') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Create New Permission</h5>
        
                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.settings.rolepermission.permission.index') }}" class="btn btn-sm btn-primary">
                        <span class="tf-icon ti ti-plus ti-xs me-1"></span>
                        All Permissions
                    </a>
                </div>
            </div>
            <form action="{{ route('administration.settings.rolepermission.permission.store') }}" method="post" autocomplete="off" name="sumbit_form" id="submitForm">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label">Select Module <strong class="text-danger">*</strong></label>
                            <select class="select2 form-select" name="permission_module_id" data-allow-clear="true" required>
                                <option value="" selected disabled>Select Module</option>
                                @foreach ($modules as $module) 
                                    <option value="{{ $module->id }}">{{ $module->name }}</option>
                                @endforeach
                            </select>
                            <small class="float-end pt-2">
                                Didn't Find Module? 
                                <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#addNewPermissionModuleModal" class="text-primary text-bold">Create Module</a>
                            </small>
                        </div>                        
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="d-flex">
                                <div class="form-check me-3 me-lg-5">
                                    <input class="form-check-input" type="checkbox" checked name="name[Create]" id="permissionCreate" />
                                    <label class="form-check-label" for="permissionCreate">
                                        Create
                                    </label>
                                </div>
                                <div class="form-check me-3 me-lg-5">
                                    <input class="form-check-input" type="checkbox" checked name="name[Read]" id="permissionRead" />
                                    <label class="form-check-label" for="permissionRead">
                                        Read
                                    </label>
                                </div>
                                <div class="form-check me-3 me-lg-5">
                                    <input class="form-check-input" type="checkbox" checked name="name[Update]" id="permissionUpdate" />
                                    <label class="form-check-label" for="permissionUpdate">
                                        Update
                                    </label>
                                </div>
                                <div class="form-check me-3 me-lg-5">
                                    <input class="form-check-input" type="checkbox" checked name="name[Delete]" id="permissionDelete" />
                                    <label class="form-check-label" for="permissionDelete">
                                        Delete
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary float-end">Create Permissions</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>        
    </div>
</div>
<!-- End row -->


<!-- Add New Module Modal -->
<div class="modal fade" data-bs-backdrop="static" id="addNewPermissionModuleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">Add New Permission</h3>
                    <p class="text-muted">Create A New Permission Module</p>
                </div>
                <!-- Add New Module form -->
                <form method="post" action="{{ route('administration.settings.rolepermission.permission.module.store') }}" class="row g-3" autocomplete="off">
                    @csrf
                    <div class="col-12 mb-4">
                        <label class="form-label">Module Name <strong class="text-danger">*</strong></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Enter a Name" tabindex="-1" required/>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Create Module</button>
                    </div>
                </form>
                <!--/ Add New Module form -->
            </div>
        </div>
    </div>
</div>
<!--/ Add New Module Modal -->

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
    <script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
    <script src="{{asset('assets/js/form-layouts.js')}}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Custom Script Here
    </script>
@endsection
