@extends('administration.profile.index')

@section('profile_breadcrumb')
    <li class="breadcrumb-item active">{{ __('Profile') }}</li>
@endsection

@section('profile_content')

<!-- User Profile Content -->
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-body">
                <small class="card-text text-uppercase">Official Information</small>
                <dl class="row mt-3 mb-1">
                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                        <i class="ti ti-hash text-heading"></i>
                        <span class="fw-medium mx-2 text-heading">User ID:</span>
                    </dt>
                    <dd class="col-sm-8">
                        <span>{{ $user->userid }}</span>
                    </dd>
                </dl>
                <dl class="row mb-1">
                    <dt class="col-sm-4 fw-medium text-nowrap">
                        <i class="ti ti-user-bolt text-heading"></i>
                        <span class="fw-medium mx-2 text-heading">Alias Name:</span>
                    </dt>
                    <dd class="col-sm-8">
                        <span>{{ optional($user->employee)->alias_name }}</span>
                    </dd>
                </dl>
                <dl class="row mb-1">
                    <dt class="col-sm-4 fw-medium text-nowrap">
                        <i class="ti ti-mail-cog text-heading"></i>
                        <span class="fw-medium mx-2 text-heading">Login Email:</span>
                    </dt>
                    <dd class="col-sm-8">
                        <a href="mailto:{{ $user->email }}" class="text-primary">{{ $user->email }}</a>
                    </dd>
                </dl>
                @if ($user->employee->official_email)
                    <dl class="row mb-1">
                        <dt class="col-sm-4 fw-medium text-nowrap">
                            <i class="ti ti-mail-star text-heading"></i>
                            <span class="fw-medium mx-2 text-heading">Official Email:</span>
                        </dt>
                        <dd class="col-sm-8">
                            <a href="mailto:{{ optional($user->employee)->official_email }}" class="text-primary">{{ optional($user->employee)->official_email }}</a>
                        </dd>
                    </dl>
                @endif
                @if ($user->employee->official_contact_no)
                    <dl class="row mb-1">
                        <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                            <i class="ti ti-phone-call text-heading"></i>
                            <span class="fw-medium mx-2 text-heading">Official Contact:</span>
                        </dt>
                        <dd class="col-sm-8">
                            <a href="tel:{{ optional($user->employee)->official_contact_no }}" class="text-primary">{{ optional($user->employee)->official_contact_no }}</a>
                        </dd>
                    </dl>
                @endif
                <dl class="row mb-1">
                    <dt class="col-sm-4 fw-medium text-nowrap">
                        <i class="ti ti-calendar text-heading"></i>
                        <span class="fw-medium mx-2 text-heading">Joining Date:</span>
                    </dt>
                    <dd class="col-sm-8">
                        <span>{{ show_date(optional($user->employee)->joining_date) }}</span>
                        <br>
                        <small class="badge bg-success text-capitalize mt-1">{{ total_day_difference(optional($user->employee)->joining_date) }}</small>
                    </dd>
                </dl>
                @hasanyrole(['Developer'])
                    <hr>
                    <dl class="row mb-1">
                        <dt class="col-sm-4 fw-medium text-nowrap">
                            <i class="ti ti-qrcode text-heading"></i>
                            <span class="fw-medium mx-2 text-heading">QR Code:</span>
                        </dt>
                        <dd class="col-sm-8">
                            @if ($user->hasMedia('qrcode'))
                                <img src="{{ $user->getFirstMediaUrl('qrcode') }}" alt="{{ $user->name }} QRCODE" class="d-block h-auto ms-0 ms-sm-4" width="150px">
                            @else
                                <a href="{{ route('administration.settings.user.generate.qr.code', ['user' => $user]) }}" class="btn btn-outline-primary btn-sm confirm-success">Generate QR Code</a>
                            @endif
                        </dd>
                    </dl>
                @endhasanyrole
                <hr>
                <dl class="row mb-1">
                    <dt class="col-sm-4 fw-medium text-nowrap">
                        <i class="ti ti-barcode text-heading"></i>
                        <span class="fw-medium mx-2 text-heading">Bar Code:</span>
                    </dt>
                    <dd class="col-sm-8">
                        @if ($user->hasMedia('barcode'))
                            <img src="{{ $user->getFirstMediaUrl('barcode') }}" alt="{{ $user->name }} BAR-CODE" class="d-block h-auto" width="300px">

                            <a href="{{ spatie_media_download($user->getFirstMedia('barcode')) }}" target="_blank" class="text-bold text-muted" title="Download Barcode">
                                {{ $user->userid }}
                            </a>
                        @else
                            @canany (['User Everything', 'User Create'])
                                <a href="{{ route('administration.settings.user.generate.bar.code', ['user' => $user]) }}" class="btn btn-outline-primary btn-sm confirm-success">Generate Barcode</a>
                            @endcanany
                        @endif
                    </dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-body">
                <small class="card-text text-uppercase">Personal Information</small>
                <dl class="row mt-3 mb-1">
                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                        <i class="ti ti-user text-heading"></i>
                        <span class="fw-medium mx-2 text-heading">Full Name:</span>
                    </dt>
                    <dd class="col-sm-8">
                        <span>{{ $user->name }}</span>
                    </dd>
                </dl>
                @isset ($user->employee->blood_group)
                    <dl class="row mb-1">
                        <dt class="col-sm-4 fw-medium text-nowrap">
                            <i class="ti ti-drop-circle text-heading"></i>
                            <span class="fw-medium mx-2 text-heading">Blood Group:</span>
                        </dt>
                        <dd class="col-sm-8">
                            <span>{{ optional($user->employee)->blood_group }}</span>
                        </dd>
                    </dl>
                @endisset
                @isset ($user->employee->gender)
                    <dl class="row mb-1">
                        <dt class="col-sm-4 fw-medium text-nowrap">
                            <i class="ti ti-{{ $user->employee->gender === 'Male' ? 'man' : 'woman' }} text-heading"></i>
                            <span class="fw-medium mx-2 text-heading">Gender:</span>
                        </dt>
                        <dd class="col-sm-8">
                            <span>{{ optional($user->employee)->gender }}</span>
                        </dd>
                    </dl>
                @endisset
                @isset ($user->employee->religion)
                    <dl class="row mb-1">
                        <dt class="col-sm-4 fw-medium text-nowrap">
                            <i class="ti ti-pray text-heading"></i>
                            <span class="fw-medium mx-2 text-heading">Religion:</span>
                        </dt>
                        <dd class="col-sm-8">
                            <span>{{ optional($user->employee)->religion->name }}</span>
                        </dd>
                    </dl>
                @endisset
                <dl class="row mb-1">
                    <dt class="col-sm-4 fw-medium text-nowrap">
                        <i class="ti ti-mail text-heading"></i>
                        <span class="fw-medium mx-2 text-heading">Personal Email:</span>
                    </dt>
                    <dd class="col-sm-8">
                        <a href="mailto:{{ optional($user->employee)->personal_email }}" class="text-primary">{{ optional($user->employee)->personal_email }}</a>
                    </dd>
                </dl>
                <dl class="row mb-1">
                    <dt class="col-sm-4 fw-medium text-nowrap">
                        <i class="ti ti-device-mobile-vibration text-heading"></i>
                        <span class="fw-medium mx-2 text-heading">Personal Contact:</span>
                    </dt>
                    <dd class="col-sm-8">
                        <a href="tel:{{ optional($user->employee)->personal_contact_no }}" class="text-primary">{{ optional($user->employee)->personal_contact_no }}</a>
                    </dd>
                </dl>
                <dl class="row mb-1">
                    <dt class="col-sm-4 fw-medium text-nowrap">
                        <i class="ti ti-man text-heading"></i>
                        <span class="fw-medium mx-2 text-heading">Father Name:</span>
                    </dt>
                    <dd class="col-sm-8">
                        <span>{{ optional($user->employee)->father_name }}</span>
                    </dd>
                </dl>
                <dl class="row mb-1">
                    <dt class="col-sm-4 fw-medium text-nowrap">
                        <i class="ti ti-woman text-heading"></i>
                        <span class="fw-medium mx-2 text-heading">Mother Name:</span>
                    </dt>
                    <dd class="col-sm-8">
                        <span>{{ optional($user->employee)->mother_name }}</span>
                    </dd>
                </dl>
                <dl class="row mb-1">
                    <dt class="col-sm-4 fw-medium text-nowrap">
                        <i class="ti ti-calendar text-heading"></i>
                        <span class="fw-medium mx-2 text-heading">Date Of Birth:</span>
                    </dt>
                    <dd class="col-sm-8">
                        <span>{{ show_date(optional($user->employee)->birth_date) }}</span>
                        <br>
                        @if (is_today_birthday(optional($user->employee)->birth_date))
                            <small class="mt-1 badge bg-success">
                                <span class="text-bold text-capitalize">Happy Birthday {{ get_employee_name($user) }}</span>
                            </small>
                        @else
                            <small class="mt-1" title="Upcoming Birthday">
                                <span class="text-bold text-success">{{ upcoming_birthday(optional($user->employee)->birth_date) }}</span>
                            </small>
                        @endif
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>
<!--/ User Profile Content -->
@endsection
