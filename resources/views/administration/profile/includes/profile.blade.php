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
                    </dd>
                </dl>
                <dl class="row mb-1">
                    <dt class="col-sm-4 fw-medium text-nowrap">
                        <i class="ti ti-qrcode text-heading"></i>
                        <span class="fw-medium mx-2 text-heading">QR Code:</span>
                    </dt>
                    <dd class="col-sm-8">
                        @if ($user->hasMedia('qrcode'))
                            <img src="{{ $user->getFirstMediaUrl('qrcode') }}" alt="{{ $user->name }} QRCODE" class="d-block h-auto ms-0 ms-sm-4" width="150px">
                        @else
                            <a href="{{ route('administration.settings.user.generate.qr.Code', ['user' => $user]) }}" class="btn btn-outline-primary btn-sm confirm-success">Generate QR Code</a>
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
            </div>
        </div>
    </div>
</div>
<!--/ User Profile Content -->
@endsection