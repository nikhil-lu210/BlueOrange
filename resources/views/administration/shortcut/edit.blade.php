@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Edit & Update Shortcut'))

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
    <b class="text-uppercase">{{ __('Edit & Update Shortcut') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Shortcuts') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.shortcut.index') }}">{{ __('My Shortcuts') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Edit & Update Shortcut') }}</li>
@endsection

@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Edit & Update Shortcut</h5>
        
                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.shortcut.index') }}" class="btn btn-sm btn-dark">
                        <span class="tf-icon ti ti-arrow-left ti-xs me-1"></span>
                        Back
                    </a>
                </div>
            </div>
            <!-- Account -->
            <div class="card-body">
                <form id="shortcutForm" action="{{ route('administration.shortcut.update', ['shortcut' => $shortcut]) }}" method="post" enctype="multipart/form-data" autocomplete="off">
                    @csrf                    
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="icon" class="form-label">{{ __('Icon') }}</label>
                            <input type="text" id="icon" name="icon" value="{{ old('icon', $shortcut->icon) }}" placeholder="{{ __('Ex: hash') }}" class="form-control @error('icon') is-invalid @enderror"/>
                            <small>Select the icon name from <b class="text-capitalize"><a href="https://tabler.io/icons" target="_blank">tabler icons</a></b>.</small>
                            @error('icon')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">{{ __('Name') }} <strong class="text-danger">*</strong></label>
                            <input type="text" minlength="0" maxlength="30" id="name" name="name" value="{{ old('name', $shortcut->name) }}" placeholder="{{ __('Ex: Dashboard') }}" class="form-control @error('name') is-invalid @enderror" required/>
                            @error('name')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="url" class="form-label">{{ __('URL') }} <strong class="text-danger">*</strong></label>
                            <input type="url" id="url" name="url" value="{{ old('url', $shortcut->url) }}" placeholder="{{ __('Ex: https://blueorange.test/dashboard') }}" class="form-control @error('url') is-invalid @enderror" required/>
                            @error('url')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-2 float-end">
                        <button type="submit" class="btn btn-primary">Update Shortcut</button>
                    </div>
                </form>
            </div>
            <!-- /Account -->
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
        // 
    </script>
@endsection
