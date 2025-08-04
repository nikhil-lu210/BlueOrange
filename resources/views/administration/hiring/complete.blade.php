@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Complete Hiring'))

@section('css_links')
    {{--  External CSS  --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('Complete Hiring Process') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('administration.dashboard.index') }}">{{ __('Dashboard') }}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.hiring.index') }}">{{ __('Employee Hiring') }}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.hiring.show', $hiring_candidate) }}">{{ $hiring_candidate->name }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Complete Hiring') }}</li>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">
        <!-- Candidate Summary -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Candidate Summary') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>{{ $hiring_candidate->name }}</h6>
                        <p class="text-muted mb-1">{{ $hiring_candidate->email }}</p>
                        <p class="text-muted mb-1">{{ $hiring_candidate->phone }}</p>
                        <p class="text-muted mb-0">{{ __('Expected Role') }}: {{ $hiring_candidate->expected_role }}</p>
                    </div>
                    <div class="col-md-6">
                        <div class="text-end">
                            <span class="badge bg-success fs-6">{{ __('Ready for Hiring') }}</span>
                            <p class="text-muted mt-2 mb-0">{{ __('All stages completed successfully') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Account Creation Form -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Create User Account') }}</h5>
                <a href="{{ route('administration.hiring.show', $hiring_candidate) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="ti ti-arrow-left"></i> {{ __('Back to Details') }}
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('administration.hiring.complete', $hiring_candidate) }}" method="POST" id="hiringForm">
                    @csrf

                    <div class="row g-3">
                        <!-- Account Information -->
                        <div class="col-12">
                            <h6 class="text-primary mb-3">{{ __('Account Information') }}</h6>
                        </div>

                        <div class="col-md-6">
                            <label for="userid" class="form-label">{{ __('User ID') }} <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('userid') is-invalid @enderror" 
                                   id="userid" 
                                   name="userid" 
                                   value="{{ old('userid') }}" 
                                   placeholder="{{ __('e.g., john.doe') }}"
                                   required>
                            @error('userid')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">{{ __('Unique identifier for login (letters, numbers, dots, hyphens, underscores only)') }}</div>
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $hiring_candidate->email) }}" 
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="first_name" class="form-label">{{ __('First Name') }} <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('first_name') is-invalid @enderror" 
                                   id="first_name" 
                                   name="first_name" 
                                   value="{{ old('first_name', explode(' ', $hiring_candidate->name)[0] ?? '') }}" 
                                   required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="last_name" class="form-label">{{ __('Last Name') }} <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('last_name') is-invalid @enderror" 
                                   id="last_name" 
                                   name="last_name" 
                                   value="{{ old('last_name', implode(' ', array_slice(explode(' ', $hiring_candidate->name), 1)) ?: '') }}" 
                                   required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="form-label">{{ __('Password') }} <span class="text-danger">*</span></label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">{{ __('Minimum 8 characters') }}</div>
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }} <span class="text-danger">*</span></label>
                            <input type="password" 
                                   class="form-control @error('password_confirmation') is-invalid @enderror" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Employee Information -->
                        <div class="col-12">
                            <h6 class="text-primary mb-3 mt-4">{{ __('Employee Information') }}</h6>
                        </div>

                        <div class="col-md-6">
                            <label for="joining_date" class="form-label">{{ __('Joining Date') }} <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control @error('joining_date') is-invalid @enderror" 
                                   id="joining_date" 
                                   name="joining_date" 
                                   value="{{ old('joining_date', date('Y-m-d')) }}" 
                                   max="{{ date('Y-m-d') }}"
                                   required>
                            @error('joining_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="alias_name" class="form-label">{{ __('Alias Name') }}</label>
                            <input type="text" 
                                   class="form-control @error('alias_name') is-invalid @enderror" 
                                   id="alias_name" 
                                   name="alias_name" 
                                   value="{{ old('alias_name') }}" 
                                   placeholder="{{ __('Nickname or preferred name') }}">
                            @error('alias_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="official_email" class="form-label">{{ __('Official Email') }}</label>
                            <input type="email" 
                                   class="form-control @error('official_email') is-invalid @enderror" 
                                   id="official_email" 
                                   name="official_email" 
                                   value="{{ old('official_email') }}" 
                                   placeholder="{{ __('Company email address') }}">
                            @error('official_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="official_contact_no" class="form-label">{{ __('Official Contact') }}</label>
                            <input type="tel" 
                                   class="form-control @error('official_contact_no') is-invalid @enderror" 
                                   id="official_contact_no" 
                                   name="official_contact_no" 
                                   value="{{ old('official_contact_no', $hiring_candidate->phone) }}" 
                                   placeholder="{{ __('Official phone number') }}">
                            @error('official_contact_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="role_id" class="form-label">{{ __('Role') }} <span class="text-danger">*</span></label>
                            <select class="form-select select2 @error('role_id') is-invalid @enderror" 
                                    id="role_id" 
                                    name="role_id" 
                                    required>
                                <option value="">{{ __('Select Role') }}</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="col-12">
                            <hr class="my-4">
                            <div class="d-flex justify-content-end gap-3">
                                <a href="{{ route('administration.hiring.show', $hiring_candidate) }}" class="btn btn-outline-secondary">
                                    {{ __('Cancel') }}
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="ti ti-user-plus"></i> {{ __('Complete Hiring & Create Account') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script_links')
    {{--  External Javascript Links --}}
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2();

            // Auto-generate userid from first and last name
            $('#first_name, #last_name').on('input', function() {
                const firstName = $('#first_name').val().toLowerCase().replace(/[^a-z]/g, '');
                const lastName = $('#last_name').val().toLowerCase().replace(/[^a-z]/g, '');
                
                if (firstName && lastName) {
                    const userid = firstName + '.' + lastName;
                    $('#userid').val(userid);
                }
            });

            // Password strength indicator (optional)
            $('#password').on('input', function() {
                const password = $(this).val();
                const strength = getPasswordStrength(password);
                // You can add visual feedback here
            });

            function getPasswordStrength(password) {
                let strength = 0;
                if (password.length >= 8) strength++;
                if (/[a-z]/.test(password)) strength++;
                if (/[A-Z]/.test(password)) strength++;
                if (/[0-9]/.test(password)) strength++;
                if (/[^A-Za-z0-9]/.test(password)) strength++;
                return strength;
            }
        });
    </script>
@endsection
