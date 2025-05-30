@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Create New User'))

@section('css_links')
    {{--  External CSS  --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    {{-- <!-- Vendors CSS --> --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}" />
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
                    <a href="{{ route('administration.settings.user.import.index') }}" class="btn btn-sm btn-primary">
                        <span class="tf-icon ti ti-upload ti-xs me-1"></span>
                        {{ __('Import Users') }}
                    </a>
                </div>
            </div>
            <!-- Account -->
            <div class="card-body">
                <form action="{{ route('administration.settings.user.store') }}" method="post" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <img src="https://fakeimg.pl/100/dddddd/?text=Upload-Image" alt="user-avatar" class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar" />
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
                            <label class="form-label d-block" for="userid">
                                <span class="float-start">
                                    User ID <strong class="text-danger">*</strong>
                                </span>
                                <span class="float-end">
                                    <a href="javascript:void(0);" id="editID" class="text-primary">
                                        Edit ID
                                    </a>
                                    <a href="javascript:void(0);" id="doneEditID" class="text-primary d-none">
                                        Done
                                    </a>
                                </span>
                            </label>
                            <div class="input-group input-group-merge mt-4 editable-input" id="editableInput">
                                <span class="input-group-text" style="padding-right: 2px;">UID</span>
                                <input type="text" id="userid" name="userid" class="form-control @error('userid') is-invalid @enderror" value="{{ old('userid', date('Ymd')) }}" placeholder="20230201" readonly required/>
                            </div>
                            @error('userid')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="role_id" class="form-label">Select Role <strong class="text-danger">*</strong></label>
                            <select name="role_id" class="select2 form-select @error('role_id') is-invalid @enderror" data-allow-clear="true" required autofocus>
                                <option value="" selected disabled>Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" @selected(old('role_id') == $role->id)>{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="first_name" class="form-label">{{ __('First Name') }} <strong class="text-danger">*</strong></label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="{{ __('First Name') }}" class="form-control @error('first_name') is-invalid @enderror" required/>
                            @error('first_name')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="last_name" class="form-label">{{ __('Surname / Family Name / Last Name') }} <strong class="text-danger">*</strong></label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="{{ __('Surname / Family Name / Last Name') }}" class="form-control @error('last_name') is-invalid @enderror" required/>
                            @error('last_name')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="email" class="form-label">{{ __('Login Email') }} <strong class="text-danger">*</strong></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="ti ti-mail"></i></span>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="{{ __('Login Email') }}" class="form-control @error('email') is-invalid @enderror" required/>
                            </div>
                            @error('email')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4 form-password-toggle">
                            <label class="form-label" for="password">{{ __('Password') }} <strong class="text-danger">*</strong></label>
                            <div class="input-group input-group-merge">
                                <input type="password" minlength="8" id="password" name="password" value="{{ old('password', '12345678') }}" class="form-control @error('password') is-invalid @enderror" placeholder="**********" required/>
                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                        </div>
                        <div class="mb-3 col-md-4 form-password-toggle">
                            <label class="form-label" for="password_confirmation">{{ __('Password Confirmation') }} <strong class="text-danger">*</strong></label>
                            <div class="input-group input-group-merge">
                                <input type="password" minlength="8" id="password_confirmation" name="password_confirmation" value="{{ old('password_confirmation', '12345678') }}" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="**********" required/>
                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                        </div>

                        <div class="mb-3 col-md-5">
                            <label for="alias_name" class="form-label">{{ __('Alias Name') }} <strong class="text-danger">*</strong></label>
                            <input type="text" id="alias_name" name="alias_name" value="{{ old('alias_name') }}" placeholder="{{ __('Alias Name') }}" class="form-control @error('alias_name') is-invalid @enderror" required/>
                            @error('alias_name')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">Joining Date <strong class="text-danger">*</strong></label>
                            <input type="text" name="joining_date" value="{{ old('joining_date') }}" class="form-control  date-picker" placeholder="YYYY-MM-DD" required/>
                            @error('joining_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="start_time" class="form-label">{{ __('Shift Start & End Time') }} <strong class="text-danger">*</strong></label>
                            <div class="input-group">
                                <input type="text" id="start_time" name="start_time" value="{{ old('start_time') }}" placeholder="HH:MM" class="form-control time-picker @error('start_time') is-invalid @enderror" required/>
                                <small class="input-group-text text-muted text-uppercase fs-tiny">To</small>
                                <input type="text" id="end_time" name="end_time" value="{{ old('end_time') }}" placeholder="HH:MM" class="form-control time-picker @error('end_time') is-invalid @enderror" required/>
                            </div>
                            @error('start_time')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                            @error('end_time')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-5">
                            <label for="father_name" class="form-label">{{ __('Father Name') }} <strong class="text-danger">*</strong></label>
                            <input type="text" id="father_name" name="father_name" value="{{ old('father_name') }}" placeholder="{{ __('Father Name') }}" class="form-control @error('father_name') is-invalid @enderror" required/>
                            @error('father_name')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-5">
                            <label for="mother_name" class="form-label">{{ __('Mother Name') }} <strong class="text-danger">*</strong></label>
                            <input type="text" id="mother_name" name="mother_name" value="{{ old('mother_name') }}" placeholder="{{ __('Mother Name') }}" class="form-control @error('mother_name') is-invalid @enderror" required/>
                            @error('mother_name')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-2">
                            <label class="form-label">Birthdate <strong class="text-danger">*</strong></label>
                            <input type="text" name="birth_date" value="{{ old('birth_date') }}" class="form-control date-picker" placeholder="YYYY-MM-DD" required/>
                            @error('birth_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="personal_email" class="form-label">{{ __('Personal Email') }} <strong class="text-danger">*</strong></label>
                            <input type="email" id="personal_email" name="personal_email" value="{{ old('personal_email') }}" placeholder="{{ __('Personal Email') }}" class="form-control @error('personal_email') is-invalid @enderror" required/>
                            @error('personal_email')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="official_email" class="form-label">{{ __('Official Email') }}</label>
                            <input type="email" id="official_email" name="official_email" value="{{ old('official_email') }}" placeholder="{{ __('Official Email') }}" class="form-control @error('official_email') is-invalid @enderror"/>
                            @error('official_email')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="personal_contact_no" class="form-label">{{ __('Personal Contact No.') }} <strong class="text-danger">*</strong></label>
                            <input type="tel" id="personal_contact_no" name="personal_contact_no" value="{{ old('personal_contact_no') }}" placeholder="{{ __('Personal Contact No.') }}" class="form-control @error('personal_contact_no') is-invalid @enderror" required/>
                            @error('personal_contact_no')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="official_contact_no" class="form-label">{{ __('Official Contact No.') }}</label>
                            <input type="tel" id="official_contact_no" name="official_contact_no" value="{{ old('official_contact_no') }}" placeholder="{{ __('Official Contact No.') }}" class="form-control @error('official_contact_no') is-invalid @enderror"/>
                            @error('official_contact_no')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>

                    {{-- Academic Information Section --}}
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-muted fw-semibold">Academic Information</h6>
                            <hr class="mt-0">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="institute_id" class="form-label">{{ __('Institute') }}</label>
                            <select name="institute_id" id="institute_id" class="form-select select2-tags @error('institute_id') is-invalid @enderror" data-allow-clear="true" data-tags="true" data-placeholder="Select or type to add new institute">
                                <option value="">{{ __('Select Institute') }}</option>
                                @foreach ($institutes as $institute)
                                    <option value="{{ $institute->id }}" {{ old('institute_id') == $institute->id ? 'selected' : '' }}>
                                        {{ $institute->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">You can type to add a new institute if not found in the list</small>
                            @error('institute_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="education_level_id" class="form-label">{{ __('Education Level') }}</label>
                            <select name="education_level_id" id="education_level_id" class="form-select select2-tags @error('education_level_id') is-invalid @enderror" data-allow-clear="true" data-tags="true" data-placeholder="Select or type to add new education level">
                                <option value="">{{ __('Select Education Level') }}</option>
                                @foreach ($educationLevels as $level)
                                    <option value="{{ $level->id }}" {{ old('education_level_id') == $level->id ? 'selected' : '' }}>
                                        {{ $level->title }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">You can type to add a new education level if not found in the list</small>
                            @error('education_level_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-2">
                            <label for="passing_year" class="form-label">{{ __('Passing Year / Exp. Year') }}</label>
                            <input type="number" id="passing_year" name="passing_year" value="{{ old('passing_year') }}" placeholder="{{ __('e.g., 2020') }}" min="1950" max="{{ date('Y') + 10 }}" class="form-control @error('passing_year') is-invalid @enderror"/>
                            @error('passing_year')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-2 float-end">
                        <button type="reset" onclick="return confirm('Sure Want To Reset?');" class="btn btn-outline-danger me-2">Reset Form</button>
                        <button type="submit" class="btn btn-primary confirm-form-success">Create User</button>
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
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>
    {{-- <!-- Vendors JS --> --}}
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}"></script>
    {{-- <!-- Page JS --> --}}
    {{-- <script src="{{ asset('assets/js/forms-pickers.js') }}"></script> --}}
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
            $("#editID").on("click", function () {
                $(this).addClass("d-none");
                $("#doneEditID").removeClass("d-none");
                $("#editableInput").removeClass("editable-input");

                $("#userid").prop("readonly", false);
            });

            $("#doneEditID").on("click", function () {
                $(this).addClass("d-none");
                $("#editID").removeClass("d-none");
                $("#editableInput").addClass("editable-input");

                $("#userid").prop("readonly", true);
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            $('.time-picker').flatpickr({
                enableTime: true,
                noCalendar: true
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

    <script>
        // Select2 with tagging functionality for academic fields
        $(document).ready(function() {
            // Initialize Select2 with tagging for institutes
            $('#institute_id').select2({
                tags: true,
                tokenSeparators: [], // Remove space and comma separators
                createTag: function (params) {
                    var term = $.trim(params.term);
                    if (term === '') {
                        return null;
                    }
                    return {
                        id: 'new:' + term,
                        text: term + ' (New Institute)',
                        newTag: true
                    };
                },
                templateResult: function (data) {
                    var $result = $('<span></span>');
                    $result.text(data.text);
                    if (data.newTag) {
                        $result.append(' <em>(will be created)</em>');
                    }
                    return $result;
                },
                insertTag: function (data, tag) {
                    // Only insert if user explicitly selects the tag
                    data.push(tag);
                }
            });

            // Initialize Select2 with tagging for education levels
            $('#education_level_id').select2({
                tags: true,
                tokenSeparators: [], // Remove space and comma separators
                createTag: function (params) {
                    var term = $.trim(params.term);
                    if (term === '') {
                        return null;
                    }
                    return {
                        id: 'new:' + term,
                        text: term + ' (New Education Level)',
                        newTag: true
                    };
                },
                templateResult: function (data) {
                    var $result = $('<span></span>');
                    $result.text(data.text);
                    if (data.newTag) {
                        $result.append(' <em>(will be created)</em>');
                    }
                    return $result;
                },
                insertTag: function (data, tag) {
                    // Only insert if user explicitly selects the tag
                    data.push(tag);
                }
            });
        });
    </script>
@endsection
