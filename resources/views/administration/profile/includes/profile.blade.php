@extends('administration.profile.index')

@section('profile_content')

<!-- User Profile Content -->
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-body">
                <small class="card-text text-uppercase">Basic Information</small>
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
                        <i class="ti ti-user-bolt text-heading"></i>
                        <span class="fw-medium mx-2 text-heading">Alias Name:</span>
                    </dt>
                    <dd class="col-sm-8">
                        <span>{{ $user->name }}</span>  
                    </dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-body">
                <small class="card-text text-uppercase">Contact Information</small>
                <dl class="row mt-3 mb-1">
                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                        <i class="ti ti-phone-call text-heading"></i>
                        <span class="fw-medium mx-2 text-heading">Contact No:</span>
                    </dt>
                    <dd class="col-sm-8">
                        <a href="tel:+" class="text-primary">+880 1234 567890</a>
                    </dd>
                </dl>
                <dl class="row mb-1">
                    <dt class="col-sm-4 fw-medium text-nowrap">
                        <i class="ti ti-mail text-heading"></i>
                        <span class="fw-medium mx-2 text-heading">Email:</span>
                    </dt>
                    <dd class="col-sm-8">
                        <a href="mailto:{{ $user->email }}" class="text-primary">{{ $user->email }}</a>
                    </dd>
                </dl>
                <dl class="row mb-1">
                    <dt class="col-sm-4 fw-medium text-nowrap">
                        <i class="ti ti-brand-skype text-heading"></i>
                        <span class="fw-medium mx-2 text-heading">Skype:</span>
                    </dt>
                    <dd class="col-sm-8">
                        <span>{{ $user->email }}</span>  
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>
<!--/ User Profile Content -->
@endsection