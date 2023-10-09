@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Update Password'))

@section('css_links')
    {{--  External CSS  --}}
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    .editable-input span,
    .editable-input input,
    .editable-input input:focus {
        background-color: #f1f1f161;
    }
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Update Password') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Security') }}</li>
    <li class="breadcrumb-item active">{{ __('Update Password') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Update Password</h5>
            </div>
            <!-- Account -->
            <div class="card-body">
                <form action="{{ route('administration.my.profile.security.password.update') }}" method="post" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-12 form-password-toggle">
                            <label class="form-label" for="old_password">{{ __('Old Password') }} <strong class="text-danger">*</strong></label>
                            <div class="input-group input-group-merge">
                                <input type="password" minlength="8" id="old_password" name="old_password" value="{{ old('old_password', '12345678') }}" class="form-control @error('old_password') is-invalid @enderror" placeholder="**********" required />
                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                            @error('old_password')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12 form-password-toggle">
                            <label class="form-label" for="new_password">{{ __('Password') }} <strong class="text-danger">*</strong></label>
                            <div class="input-group input-group-merge">
                                <input type="password" minlength="8" id="new_password" name="new_password" value="{{ old('new_password', '12345678') }}" class="form-control @error('new_password') is-invalid @enderror" placeholder="**********" required />
                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                            @error('new_password')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12 form-password-toggle">
                            <label class="form-label" for="new_password_confirmation">{{ __('Password Confirmation') }} <strong class="text-danger">*</strong></label>
                            <div class="input-group input-group-merge">
                                <input type="password" minlength="8" id="new_password_confirmation" name="new_password_confirmation" value="{{ old('new_password_confirmation', '12345678') }}" class="form-control @error('new_password_confirmation') is-invalid @enderror" placeholder="**********" required/>
                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                            @error('new_password_confirmation')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-3" />

                    <div class="col-12 mb-4">
                        <h6>Password Requirements:</h6>
                        <ul class="ps-3 mb-0">
                            <li class="mb-1">Minimum 8 characters long - the more, the better</li>
                            <li class="mb-1">At least one lowercase character</li>
                            <li>At least one number, symbol, or whitespace character</li>
                        </ul>
                    </div>

                    <div class="mt-2 float-end">
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </div>
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
@endsection
