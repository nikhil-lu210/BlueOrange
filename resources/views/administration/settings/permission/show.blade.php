@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Permission Details'))

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
    <b class="text-uppercase">{{ __('Permission Details') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Role & Permission') }}</li>
    <li class="breadcrumb-item">{{ __('Permission') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.settings.rolepermission.permission.index') }}">{{ __('All Permissions') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Permission Details') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0"><strong>{{ $module->name }}</strong> Permission's Details</h5>
        
                <div class="card-header-elements ms-auto">
                    <a href="#" class="btn btn-sm btn-primary">
                        <span class="tf-icon ti ti-edit ti-xs me-1"></span>
                        Edit Permission
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-md-8 text-center">
                        @foreach ($module->permissions as $permission) 
                            <div class="divider">
                                <span class="divider-text fw-bold border py-1">{{ $permission->name }}</span>
                            </div>
                            @foreach ($permission->roles as $role) 
                                <a href="{{ route('administration.settings.rolepermission.role.show', ['role' => $role]) }}" class="btn btn-outline-primary btn-sm">
                                    {{ $role->name }}
                                </a>
                            @endforeach
                        @endforeach
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
