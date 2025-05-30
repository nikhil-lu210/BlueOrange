@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('User Management'))

@section('css_links')
    {{--  External CSS  --}}
    <!-- DataTables css -->
    <link href="{{ asset('assets/css/custom_css/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom_css/datatables/datatable.css') }}" rel="stylesheet" type="text/css" />

    {{-- Select 2 --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
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
    .filter-section {
        border-left: 3px solid #007bff;
        padding-left: 15px;
        margin-bottom: 20px;
    }

    .filter-section h6 {
        font-weight: 600;
        margin-bottom: 15px;
    }

    .table th {
        font-weight: 600;
        background-color: #f8f9fa;
    }

    .badge {
        font-size: 0.75rem;
    }

    .card-header h5 {
        font-weight: 600;
    }

    .alert-info {
        border-left: 4px solid #17a2b8;
    }

    .display-4 {
        font-size: 3rem;
    }
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Users Advance Filter') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('User Management') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.settings.user.index') }}">{{ __('All Users') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Users Advance Filter') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row">
    <div class="col-md-12">
        <form action="{{ route('administration.settings.user.advance_filter.index') }}" method="get">
            @csrf
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <span class="tf-icon ti ti-filter ti-xs me-1"></span>
                        Advanced User Filters
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Basic User Information -->
                    <div class="row mb-3 filter-section">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="ti ti-user me-1"></i>
                                Basic User Information
                            </h6>
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="userid" class="form-label">User ID</label>
                            <input type="text" id="userid" name="userid" value="{{ old('userid', request()->userid) }}" placeholder="Search by User ID" class="form-control @error('userid') is-invalid @enderror" />
                            @error('userid')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name', request()->name) }}" placeholder="Search by Name" class="form-control @error('name') is-invalid @enderror" />
                            @error('name')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email', request()->email) }}" placeholder="Search by Email" class="form-control @error('email') is-invalid @enderror" />
                            @error('email')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select bootstrap-select w-100 @error('status') is-invalid @enderror" data-style="btn-default">
                                <option value="">Select Status</option>
                                @foreach ($statuses as $statusOption)
                                    <option value="{{ $statusOption }}" {{ request()->status == $statusOption ? 'selected' : '' }}>
                                        {{ $statusOption }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>

                    <!-- Role & Team Information -->
                    <div class="row mb-3 filter-section">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="ti ti-users me-1"></i>
                                Role & Team Information
                            </h6>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="role_id" class="form-label">Role</label>
                            <select name="role_id" id="role_id" class="select2 form-select @error('role_id') is-invalid @enderror" data-allow-clear="true">
                                <option value="">Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" {{ $role->id == request()->role_id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="team_leader_id" class="form-label">Team Leader</label>
                            <select name="team_leader_id" id="team_leader_id" class="select2 form-select @error('team_leader_id') is-invalid @enderror" data-allow-clear="true">
                                <option value="">Select Team Leader</option>
                                @foreach ($teamLeaders as $leader)
                                    <option value="{{ $leader->id }}" {{ $leader->id == request()->team_leader_id ? 'selected' : '' }}>
                                        {{ get_employee_name($leader) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('team_leader_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>

                    <!-- Employee Personal Information -->
                    <div class="row mb-3 filter-section">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="ti ti-id me-1"></i>
                                Employee Personal Information
                            </h6>
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="alias_name" class="form-label">Alias Name</label>
                            <input type="text" id="alias_name" name="alias_name" value="{{ old('alias_name', request()->alias_name) }}" placeholder="Search by Alias Name" class="form-control @error('alias_name') is-invalid @enderror" />
                            @error('alias_name')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select name="gender" id="gender" class="form-select bootstrap-select w-100 @error('gender') is-invalid @enderror" data-style="btn-default">
                                <option value="">Select Gender</option>
                                @foreach ($genders as $genderOption)
                                    <option value="{{ $genderOption }}" {{ request()->gender == $genderOption ? 'selected' : '' }}>
                                        {{ $genderOption }}
                                    </option>
                                @endforeach
                            </select>
                            @error('gender')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="blood_group" class="form-label">Blood Group</label>
                            <select name="blood_group" id="blood_group" class="form-select bootstrap-select w-100 @error('blood_group') is-invalid @enderror" data-style="btn-default">
                                <option value="">Select Blood Group</option>
                                @foreach ($bloodGroups as $bloodGroupOption)
                                    <option value="{{ $bloodGroupOption }}" {{ request()->blood_group == $bloodGroupOption ? 'selected' : '' }}>
                                        {{ $bloodGroupOption }}
                                    </option>
                                @endforeach
                            </select>
                            @error('blood_group')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="religion_id" class="form-label">Religion</label>
                            <select name="religion_id" id="religion_id" class="select2 form-select @error('religion_id') is-invalid @enderror" data-allow-clear="true">
                                <option value="">Select Religion</option>
                                @foreach ($religions as $religion)
                                    <option value="{{ $religion->id }}" {{ $religion->id == request()->religion_id ? 'selected' : '' }}>
                                        {{ $religion->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('religion_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>

                    <!-- Date Filters -->
                    <div class="row mb-3 filter-section">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="ti ti-calendar me-1"></i>
                                Date Filters
                            </h6>
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="joining_date_from" class="form-label">Joining Date From</label>
                            <input type="date" id="joining_date_from" name="joining_date_from" value="{{ old('joining_date_from', request()->joining_date_from) }}" class="form-control @error('joining_date_from') is-invalid @enderror" />
                            @error('joining_date_from')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="joining_date_to" class="form-label">Joining Date To</label>
                            <input type="date" id="joining_date_to" name="joining_date_to" value="{{ old('joining_date_to', request()->joining_date_to) }}" class="form-control @error('joining_date_to') is-invalid @enderror" />
                            @error('joining_date_to')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="birth_date_from" class="form-label">Birth Date From</label>
                            <input type="date" id="birth_date_from" name="birth_date_from" value="{{ old('birth_date_from', request()->birth_date_from) }}" class="form-control @error('birth_date_from') is-invalid @enderror" />
                            @error('birth_date_from')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="birth_date_to" class="form-label">Birth Date To</label>
                            <input type="date" id="birth_date_to" name="birth_date_to" value="{{ old('birth_date_to', request()->birth_date_to) }}" class="form-control @error('birth_date_to') is-invalid @enderror" />
                            @error('birth_date_to')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>

                    <!-- Academic Information -->
                    <div class="row mb-3 filter-section">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="ti ti-school me-1"></i>
                                Academic Information
                            </h6>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="institute_id" class="form-label">Institute</label>
                            <select name="institute_id" id="institute_id" class="select2 form-select @error('institute_id') is-invalid @enderror" data-allow-clear="true">
                                <option value="">Select Institute</option>
                                @foreach ($institutes as $institute)
                                    <option value="{{ $institute->id }}" {{ $institute->id == request()->institute_id ? 'selected' : '' }}>
                                        {{ $institute->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('institute_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="education_level_id" class="form-label">Education Level</label>
                            <select name="education_level_id" id="education_level_id" class="select2 form-select @error('education_level_id') is-invalid @enderror" data-allow-clear="true">
                                <option value="">Select Education Level</option>
                                @foreach ($educationLevels as $educationLevel)
                                    <option value="{{ $educationLevel->id }}" {{ $educationLevel->id == request()->education_level_id ? 'selected' : '' }}>
                                        {{ $educationLevel->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('education_level_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-2">
                            <label for="passing_year_from" class="form-label">Passing Year From</label>
                            <input type="number" id="passing_year_from" name="passing_year_from" value="{{ old('passing_year_from', request()->passing_year_from) }}" placeholder="2020" min="1950" max="{{ date('Y') }}" class="form-control @error('passing_year_from') is-invalid @enderror" />
                            @error('passing_year_from')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-2">
                            <label for="passing_year_to" class="form-label">Passing Year To</label>
                            <input type="number" id="passing_year_to" name="passing_year_to" value="{{ old('passing_year_to', request()->passing_year_to) }}" placeholder="2024" min="1950" max="{{ date('Y') }}" class="form-control @error('passing_year_to') is-invalid @enderror" />
                            @error('passing_year_to')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="row mb-3 filter-section">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="ti ti-phone me-1"></i>
                                Contact Information
                            </h6>
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="personal_email" class="form-label">Personal Email</label>
                            <input type="email" id="personal_email" name="personal_email" value="{{ old('personal_email', request()->personal_email) }}" placeholder="Search by Personal Email" class="form-control @error('personal_email') is-invalid @enderror" />
                            @error('personal_email')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="official_email" class="form-label">Official Email</label>
                            <input type="email" id="official_email" name="official_email" value="{{ old('official_email', request()->official_email) }}" placeholder="Search by Official Email" class="form-control @error('official_email') is-invalid @enderror" />
                            @error('official_email')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="personal_contact_no" class="form-label">Personal Contact</label>
                            <input type="text" id="personal_contact_no" name="personal_contact_no" value="{{ old('personal_contact_no', request()->personal_contact_no) }}" placeholder="Search by Personal Contact" class="form-control @error('personal_contact_no') is-invalid @enderror" />
                            @error('personal_contact_no')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="official_contact_no" class="form-label">Official Contact</label>
                            <input type="text" id="official_contact_no" name="official_contact_no" value="{{ old('official_contact_no', request()->official_contact_no) }}" placeholder="Search by Official Contact" class="form-control @error('official_contact_no') is-invalid @enderror" />
                            @error('official_contact_no')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>

                    <!-- Shift Information -->
                    <div class="row mb-3 filter-section">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="ti ti-clock me-1"></i>
                                Shift Information
                            </h6>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="start_time" class="form-label">Shift Start Time</label>
                            <input type="text" id="start_time" name="start_time" value="{{ old('start_time', request()->start_time) }}" placeholder="HH:MM" class="form-control time-picker @error('start_time') is-invalid @enderror" />
                            @error('start_time')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="end_time" class="form-label">Shift End Time</label>
                            <input type="text" id="end_time" name="end_time" value="{{ old('end_time', request()->end_time) }}" placeholder="HH:MM" class="form-control time-picker @error('end_time') is-invalid @enderror" />
                            @error('end_time')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>

                    <!-- System Date Filters -->
                    <div class="row mb-3 filter-section">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="ti ti-database me-1"></i>
                                System Date Filters
                            </h6>
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="created_from" class="form-label">Created From</label>
                            <input type="date" id="created_from" name="created_from" value="{{ old('created_from', request()->created_from) }}" class="form-control @error('created_from') is-invalid @enderror" />
                            @error('created_from')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="created_to" class="form-label">Created To</label>
                            <input type="date" id="created_to" name="created_to" value="{{ old('created_to', request()->created_to) }}" class="form-control @error('created_to') is-invalid @enderror" />
                            @error('created_to')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="updated_from" class="form-label">Updated From</label>
                            <input type="date" id="updated_from" name="updated_from" value="{{ old('updated_from', request()->updated_from) }}" class="form-control @error('updated_from') is-invalid @enderror" />
                            @error('updated_from')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="updated_to" class="form-label">Updated To</label>
                            <input type="date" id="updated_to" name="updated_to" value="{{ old('updated_to', request()->updated_to) }}" class="form-control @error('updated_to') is-invalid @enderror" />
                            @error('updated_to')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-md-12 text-end">
                            @php
                                $hasFilters = collect(request()->all())->filter(function($value, $key) {
                                    return !empty($value) && $key !== '_token';
                                })->isNotEmpty();
                            @endphp

                            @if ($hasFilters)
                                <a href="{{ route('administration.settings.user.advance_filter.index') }}" class="btn btn-danger me-2">
                                    <span class="tf-icon ti ti-refresh ti-xs me-1"></span>
                                    Reset All Filters
                                </a>
                            @endif
                            <button type="submit" class="btn btn-primary">
                                <span class="tf-icon ti ti-filter ti-xs me-1"></span>
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                @php
                    $hasFilters = collect(request()->all())->filter(function($value, $key) {
                        return !empty($value) && $key !== '_token';
                    })->isNotEmpty();
                @endphp
                <h5 class="mb-0">
                    @if ($hasFilters)
                        <span class="text-success">Filtered Results</span>
                    @else
                        <span class="text-muted">No Filters Applied</span>
                    @endif
                </h5>

                <div class="card-header-elements ms-auto">
                    @if ($hasFilters)
                        <span class="badge bg-primary ms-2">{{ $users->count() }} {{ $users->count() === 1 ? 'User' : 'Users' }} Found</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if ($hasFilters && $users->count() > 0)
                    <div class="table-responsive-md table-responsive-sm w-100">
                        <table class="table data-table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sl.</th>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Contact Information</th>
                                    <th>Other Info</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $key => $user)
                                    <tr>
                                        <th>#{{ $key + 1 }}</th>
                                        <th>
                                            <b class="text-primary fs-5">{{ $user->userid }}</b>
                                            <br>
                                            <small>
                                                <a href="{{ route('administration.settings.user.user_interaction.index', ['user' => $user]) }}" target="_blank" class="mb-1 text-capitalize text-bold text-dark" title="Team Leader">
                                                    @isset ($user->active_team_leader)
                                                        {{ $user->active_team_leader->employee->alias_name }}
                                                    @else
                                                        {{ __('Not Assigned') }}
                                                    @endisset
                                                </a>
                                            </small>
                                        </th>
                                        <td>
                                            {!! show_user_name_and_avatar($user) !!}
                                        </td>
                                        <td>
                                            <div class="mb-1">
                                                <strong>Official:</strong>
                                                @if ($user->employee && $user->employee->official_email)
                                                    <a href="mailto:{{ $user->employee->official_email }}" class="text-primary">
                                                        {{ $user->employee->official_email }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">Not provided</span>
                                                @endif
                                            </div>
                                            @if ($user->current_shift)
                                                <div class="mt-1">
                                                    <small class="text-muted">
                                                        Shift: {{ show_time($user->current_shift->start_time) }} - {{ show_time($user->current_shift->end_time) }}
                                                    </small>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-left">
                                            @if ($user->employee)
                                                <div>
                                                    <strong>Blood Group:</strong>
                                                    {{ $user->employee->blood_group ?? 'N/A' }}
                                                </div>
                                                <div>
                                                    <strong>Religion:</strong>
                                                    {{ $user->employee->religion->name ?? 'N/A' }}
                                                </div>
                                                @if ($user->employee->joining_date)
                                                    <div>
                                                        <strong>Joined:</strong>
                                                        <small>{{ show_date($user->employee->joining_date) }}</small>
                                                    </div>
                                                @endif
                                            @else
                                                <span class="text-muted">No employee data</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @canany (['User Update', 'User Delete'])
                                                <div class="d-inline-block">
                                                    <a href="javascript:void(0);" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="text-primary ti ti-dots-vertical"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end m-0">
                                                        @can ('User Update')
                                                            <a href="{{ route('administration.settings.user.edit', ['user' => $user]) }}" class="dropdown-item">
                                                                <i class="text-primary ti ti-pencil"></i>
                                                                Edit
                                                            </a>
                                                        @endcan
                                                        @can ('User Delete')
                                                            <div class="dropdown-divider"></div>
                                                            <a href="{{ route('administration.settings.user.destroy', ['user' => $user]) }}" class="dropdown-item text-danger delete-record confirm-danger">
                                                                <i class="ti ti-trash"></i>
                                                                Delete
                                                            </a>
                                                        @endcan
                                                    </div>
                                                </div>
                                            @endcanany
                                            <a href="{{ route('administration.settings.user.show.profile', ['user' => $user]) }}" class="btn btn-sm btn-icon btn-primary item-edit" data-bs-toggle="tooltip" title="Show Details">
                                                <i class="ti ti-info-hexagon"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @elseif ($hasFilters && $users->count() === 0)
                    <!-- No results found -->
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="ti ti-search-off display-4 text-muted"></i>
                        </div>
                        <h5 class="text-muted">No Users Found</h5>
                        <p class="text-muted">
                            No users match your current filter criteria. Try adjusting your filters or
                            <a href="{{ route('administration.settings.user.advance_filter.index') }}" class="text-primary">reset all filters</a>.
                        </p>
                    </div>
                @else
                    <!-- No filters applied -->
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="ti ti-filter display-4 text-muted"></i>
                        </div>
                        <h5 class="text-muted">Apply Filters to Search Users</h5>
                        <p class="text-muted">
                            Use the advanced filters above to search for specific users based on various criteria such as name, role, department, and more.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- End row -->

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
    <!-- Datatable js -->
    <script src="{{ asset('assets/js/custom_js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/datatable.js') }}"></script>

    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    {{-- <!-- Vendors JS --> --}}
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
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
            $('.bootstrap-select').each(function() {
                if (!$(this).data('bs.select')) { // Check if it's already initialized
                    $(this).selectpicker();
                }
            });
        });
    </script>
@endsection
