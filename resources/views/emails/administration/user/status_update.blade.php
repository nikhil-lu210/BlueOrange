@extends('layouts.email.app')

@section('email_title')
    <span style="text-align: center;">Employee Status Update</span>
@endsection

@section('content')
<!-- Start Content -->
<div>
    Dear {{ $notifiableUser->employee->alias_name }},
    <br>

    We would like to inform you that <strong>{{ $user->employee->alias_name }} ({{ $user->name }})</strong> is now marked as
    <strong>{{ ucfirst(strtolower($user->status)) }}</strong> in our records.
    <br>

    @if ($user->status === 'Fired')
        His/Her employment has been terminated, and all access to company premises and systems has been revoked. Please ensure this is respected and report any unauthorized presence.
    @elseif ($user->status === 'Resigned')
        He/She has been officially resigned from his/her position. Access to company resources has been deactivated.
    @elseif ($user->status === 'Inactive')
        The employee is currently inactive. This may be temporary or pending further administrative action.
    @elseif ($user->status === 'Active')
        He/She remain an active member of our team.
    @endif
    <br><br>

    <div style="text-align: center;">
        @if ($user->hasMedia('avatar'))
            <img src="{{ $user->getFirstMediaUrl('avatar', 'profile_view') }}" alt="{{ $user->name }} Avatar" style="max-width: 150px; border-radius: 5%;">
        @else
            <img src="{{ asset('assets/img/avatars/no_image.png') }}" alt="No Avatar Available" style="max-width: 150px; border-radius: 50%;">
        @endif
    </div>
    <br>

    Best Regards,
    <br>
    Management
</div>
<!-- End Content -->
@endsection
