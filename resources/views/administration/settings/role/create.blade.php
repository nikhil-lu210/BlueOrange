@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Create New Permission'))

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
    <b class="text-uppercase">{{ __('Create New Permission') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Role & Permission') }}</li>
    <li class="breadcrumb-item">{{ __('Permission') }}</li>
    <li class="breadcrumb-item active">{{ __('Create New Permission') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row">
    <div class="col-md-12">
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
            <div class="card-body">
                
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
