@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Update Profile'))

@section('css_links')
    {{--  External CSS  --}}
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
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
    <b class="text-uppercase">{{ __('Update Profile') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item active">{{ __('Update Profile') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12"></div>
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Update Your Profile</h5>
        
                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.my.profile') }}" class="btn btn-sm btn-primary">
                        <span class="tf-icon ti ti-arrow-left ti-xs me-1"></span>
                        Back
                    </a>
                </div>
            </div>
            <!-- Account -->
            <div class="card-body">
                <form action="{{ route('administration.my.profile.update') }}" method="post" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        @if ($user->hasMedia('avatar'))
                            <img src="{{ $user->getFirstMediaUrl('avatar') }}" alt="{{ $user->name }} Avatar" class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar">
                        @else
                            <img src="https://fakeimg.pl/300/dddddd/?text=No-Image" alt="{{ $user->name }} No Avatar" class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar">
                        @endif
                        <div class="button-wrapper">
                            <label for="upload" class="btn btn-primary me-2 mb-3" tabindex="0">
                                <span class="d-none d-sm-block">Upload Avatar</span>
                                <i class="ti ti-upload d-block d-sm-none"></i>
                                <input type="file" name="avatar" id="upload" class="account-file-input" hidden accept="image/png, image/jpeg, image/jpg"/>
                            </label>
                            <button type="button" class="btn btn-label-secondary account-image-reset mb-3">
                                <i class="ti ti-refresh-dot d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Reset</span>
                            </button>

                            <div class="text-muted">Allowed JPG, JPEG or PNG. Max size of 2MB</div>
                            @error('avatar')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-3" />
                    
                    <div class="row">
                        @if (auth()->user()->hasRole('Developer') || auth()->user()->hasRole('Super Admin')) 
                            <div class="mb-3 col-md-6">
                                <label for="role_id" class="form-label">Select Role <strong class="text-danger">*</strong></label>
                                <select name="role_id" class="select2 form-select @error('role_id') is-invalid @enderror" data-allow-clear="true" required autofocus>
                                    <option value="" selected disabled>Select Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" @selected(old('role_id') == $role->id || ($user->roles[0]->id == $role->id))>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label d-block" for="email">
                                    <span class="float-start">
                                        Login Email <strong class="text-danger">*</strong>
                                    </span>
                                    <span class="float-end">
                                        <a href="javascript:void(0);" id="editEmail" class="text-primary">
                                            Edit Email
                                        </a>
                                        <a href="javascript:void(0);" id="doneEditEmail" class="text-primary d-none">
                                            Done
                                        </a>
                                    </span>
                                </label>
                                <div class="input-group input-group-merge mt-4 editable-input" id="editableInput">
                                    <span class="input-group-text"><i class="ti ti-mail"></i></span>
                                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" placeholder="{{ __('Email') }}" class="form-control @error('email') is-invalid @enderror" readonly required/>
                                </div>
                                @error('email')
                                    <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                                @enderror
                            </div>
                        @endif

                        <div class="mb-3 col-md-4">
                            <label for="first_name" class="form-label">{{ __('First Name') }} <strong class="text-danger">*</strong></label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" placeholder="{{ __('First Name') }}" class="form-control @error('first_name') is-invalid @enderror" required/>
                            @error('first_name')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="middle_name" class="form-label">{{ __('Middle Name') }}</label>
                            <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}" placeholder="{{ __('Middle Name') }}" class="form-control @error('middle_name') is-invalid @enderror"/>
                            @error('middle_name')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="last_name" class="form-label">{{ __('Surname / Family Name / Last Name') }} <strong class="text-danger">*</strong></label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" placeholder="{{ __('Surname / Family Name / Last Name') }}" class="form-control @error('last_name') is-invalid @enderror" required/>
                            @error('last_name')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-2 float-end">
                        <button type="reset" onclick="return confirm('Sure Want To Reset?');" class="btn btn-outline-danger me-2">Reset Form</button>
                        <button type="submit" class="btn btn-primary">Update Profile</button>
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
    <script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
    <script src="{{asset('assets/js/form-layouts.js')}}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function () {
            let accountUserImage = $("#uploadedAvatar");
            const fileInput = $(".account-file-input");
            const resetFileInput = $(".account-image-reset");

            if (accountUserImage.length > 0) {
                const resetImage = accountUserImage.attr("src");
                fileInput.on("change", function () {
                    if (this.files[0]) {
                        accountUserImage.attr("src", window.URL.createObjectURL(this.files[0]));
                    }
                });
                resetFileInput.on("click", function () {
                    fileInput.val("");
                    accountUserImage.attr("src", resetImage);
                });
            }
        });
    </script>
    
    <script>
        $(document).ready(function () {
            $("#editEmail").on("click", function () {
                $(this).addClass("d-none");
                $("#doneEditEmail").removeClass("d-none");
                $("#editableInput").removeClass("editable-input");
                
                $("#email").prop("readonly", false);
            });
            
            $("#doneEditEmail").on("click", function () {
                $(this).addClass("d-none");
                $("#editEmail").removeClass("d-none");
                $("#editableInput").addClass("editable-input");
                
                $("#email").prop("readonly", true);
            });
        });
    </script>
@endsection
