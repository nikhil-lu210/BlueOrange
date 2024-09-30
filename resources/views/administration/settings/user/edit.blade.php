@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Create New User'))

@section('css_links')
    {{--  External CSS  --}}
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
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
    <b class="text-uppercase">{{ __('Create New User') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('User Management') }}</li>
    <li class="breadcrumb-item active">{{ __('Create New User') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12"></div>
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Create New User</h5>
        
                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.settings.user.show.profile', ['user' => $user]) }}" class="btn btn-sm btn-primary">
                        <span class="tf-icon ti ti-arrow-left ti-xs me-1"></span>
                        Back
                    </a>
                </div>
            </div>
            <!-- Account -->
            <div class="card-body">
                <form action="{{ route('administration.settings.user.update', ['user' => $user]) }}" method="post" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        @if ($user->hasMedia('avatar'))
                            <img src="{{ $user->getFirstMediaUrl('avatar') }}" alt="{{ $user->name }} Avatar" class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar">
                        @else
                            <img src="{{ asset('assets/img/avatars/no_image.png') }}" alt="{{ $user->name }} No Avatar" class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar">
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
                        <div class="mb-3 col-md-4">
                            <label for="first_name" class="form-label">{{ __('First Name') }} <strong class="text-danger">*</strong></label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" placeholder="{{ __('First Name') }}" class="form-control @error('first_name') is-invalid @enderror" required/>
                            @error('first_name')
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
                        <div class="mb-3 col-md-4">
                            <label for="alias_name" class="form-label">{{ __('Alias Name') }} <strong class="text-danger">*</strong></label>
                            <input type="text" id="alias_name" name="alias_name" value="{{ old('alias_name', optional($user->employee)->alias_name) }}" placeholder="{{ __('Alias Name') }}" class="form-control @error('alias_name') is-invalid @enderror"/>
                            @error('alias_name')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-2">
                            <label class="form-label">Joining Date <strong class="text-danger">*</strong></label>
                            <input type="text" name="joining_date" value="{{ old('joining_date', optional($user->employee)->joining_date) }}" class="form-control  date-picker" placeholder="YYYY-MM-DD" required/>
                            @error('joining_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="father_name" class="form-label">{{ __('Father Name') }} <strong class="text-danger">*</strong></label>
                            <input type="text" id="father_name" name="father_name" value="{{ old('father_name', optional($user->employee)->father_name) }}" placeholder="{{ __('Father Name') }}" class="form-control @error('father_name') is-invalid @enderror" required/>
                            @error('father_name')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="mother_name" class="form-label">{{ __('Mother Name') }} <strong class="text-danger">*</strong></label>
                            <input type="text" id="mother_name" name="mother_name" value="{{ old('mother_name', optional($user->employee)->mother_name) }}" placeholder="{{ __('Mother Name') }}" class="form-control @error('mother_name') is-invalid @enderror" required/>
                            @error('mother_name')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-2">
                            <label class="form-label">Birthdate <strong class="text-danger">*</strong></label>
                            <input type="text" name="birth_date" value="{{ old('birth_date', optional($user->employee)->birth_date) }}" class="form-control  date-picker" placeholder="YYYY-MM-DD" required/>
                            @error('birth_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="personal_email" class="form-label">{{ __('Personal Email') }} <strong class="text-danger">*</strong></label>
                            <input type="email" id="personal_email" name="personal_email" value="{{ old('personal_email', optional($user->employee)->personal_email) }}" placeholder="{{ __('Personal Email') }}" class="form-control @error('personal_email') is-invalid @enderror" required/>
                            @error('personal_email')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="official_email" class="form-label">{{ __('Official Email') }}</label>
                            <input type="email" id="official_email" name="official_email" value="{{ old('official_email', optional($user->employee)->official_email) }}" placeholder="{{ __('Official Email') }}" class="form-control @error('official_email') is-invalid @enderror"/>
                            @error('official_email')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="personal_contact_no" class="form-label">{{ __('Personal Contact No.') }} <strong class="text-danger">*</strong></label>
                            <input type="tel" id="personal_contact_no" name="personal_contact_no" value="{{ old('personal_contact_no', optional($user->employee)->personal_contact_no) }}" placeholder="{{ __('Personal Contact No.') }}" class="form-control @error('personal_contact_no') is-invalid @enderror" required/>
                            @error('personal_contact_no')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="official_contact_no" class="form-label">{{ __('Official Contact No.') }}</label>
                            <input type="tel" id="official_contact_no" name="official_contact_no" value="{{ old('official_contact_no', optional($user->employee)->official_contact_no) }}" placeholder="{{ __('Official Contact No.') }}" class="form-control @error('official_contact_no') is-invalid @enderror"/>
                            @error('official_contact_no')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-2 float-end">
                        <button type="reset" onclick="return confirm('Sure Want To Reset?');" class="btn btn-outline-danger me-2">Reset Form</button>
                        <button type="submit" class="btn btn-primary">Update User Info</button>
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
    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
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

    <script>
        // Custom Script Here
        $(document).ready(function() {
            $('.date-picker').datepicker({
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                autoclose: true,
                orientation: 'auto right'
            });
        });
    </script>
@endsection
