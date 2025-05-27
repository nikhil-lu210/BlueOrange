@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Edit User Info'))

@section('css_links')
    {{--  External CSS  --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />

    {{-- Bootstrap Select --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
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
    <b class="text-uppercase">{{ __('Edit User Info') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('User Management') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.settings.user.index') }}">{{ __('All Users') }}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.settings.user.show.profile', ['user' => $user]) }}">{{ $user->userid }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Edit User Info') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12"></div>
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Edit User Info of <b class="text-primary">{{ $user->name .' ('. $user->employee->alias_name .')' }}</b></h5>

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

                            <div class="text-muted">Upload a <b class="text-dark">Square Image (1:1 ratio) in JPG, JPEG, or PNG</b> format. Maximum size: <b class="text-dark">2MB</b>.</div>
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
                                    <option value="{{ $role->id }}" @selected(old('role_id') == $role->id || ($user->role->id == $role->id))>{{ $role->name }}</option>
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
                            <input type="text" id="alias_name" name="alias_name" value="{{ old('alias_name', optional($user->employee)->alias_name) }}" placeholder="{{ __('Alias Name') }}" class="form-control @error('alias_name') is-invalid @enderror" required/>
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
                        <div class="mb-3 col-md-4">
                            <label for="religion_id" class="form-label">{{ __('Select Religion') }} <strong class="text-danger">*</strong></label>
                            <select name="religion_id" id="religion_id" class="form-select bootstrap-select w-100 @error('religion_id') is-invalid @enderror"  data-style="btn-default" required>
                                <option value="">{{ __('Select Religion') }}</option>
                                @foreach ($religions as $religion)
                                    <option value="{{ $religion->id }}" @selected(optional($user->religion)->id == $religion->id)>{{ $religion->name }}</option>
                                @endforeach
                            </select>
                            @error('religion_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="gender" class="form-label">{{ __('Select Gender') }} <strong class="text-danger">*</strong></label>
                            <select name="gender" id="gender" class="form-select bootstrap-select w-100 @error('gender') is-invalid @enderror"  data-style="btn-default" required>
                                <option value="">{{ __('Select Gender') }}</option>
                                <option value="Male" @selected($user->employee->gender === 'Male')>Male</option>
                                <option value="Female" @selected($user->employee->gender === 'Female')>Female</option>
                                <option value="Other" @selected($user->employee->gender === 'Other')>Other</option>
                            </select>
                            @error('gender')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="blood_group" class="form-label">
                                {{ __('Blood Group') }}
                            </label>
                            <select name="blood_group" class="form-select select2">
                                <option value="" @selected($user->employee->blood_group == '')>Select Blood Group</option>

                                @foreach ($groupedBloodGroups as $groupLabel => $groupOptions)
                                    <optgroup label="{{ $groupLabel }}">
                                        @foreach ($groupOptions as $bloodOption)
                                            <option value="{{ $bloodOption->value }}" @selected($user->employee->blood_group === $bloodOption->value)>
                                                {{ $bloodOption->value }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach

                                <option value="Unknown" @selected($user->employee->blood_group === 'Unknown')>
                                    Don't Know (Unknown)
                                </option>
                            </select>

                            @error('blood_group')
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
                                    <option value="{{ $institute->id }}" {{ old('institute_id', optional($user->employee)->institute_id) == $institute->id ? 'selected' : '' }}>
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
                                    <option value="{{ $level->id }}" {{ old('education_level_id', optional($user->employee)->education_level_id) == $level->id ? 'selected' : '' }}>
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
                            <input type="number" id="passing_year" name="passing_year" value="{{ old('passing_year', optional($user->employee)->passing_year) }}" placeholder="{{ __('e.g., 2020') }}" min="1950" max="{{ date('Y') + 10 }}" class="form-control @error('passing_year') is-invalid @enderror"/>
                            @error('passing_year')
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
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>

    {{-- Bootstrap Select --}}
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function() {
            $('.bootstrap-select').each(function() {
                if (!$(this).data('bs.select')) { // Check if it's already initialized
                    $(this).selectpicker();
                }
            });
        });
    </script>
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

