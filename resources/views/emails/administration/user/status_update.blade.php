@extends('layouts.email.app')

@section('email_title')
    <span style="text-align: center;">Employee Status Update</span>
@endsection

@section('content')
<!-- Start Content -->
<div>
    Dear {{ $user->employee->alias_name }},  
    <br><br>
    With regret, we inform you that <strong>{{ $data->employee->alias_name }} ({{ $data->name }})</strong> is no longer with us.  
    <br><br>
    His/Her employment has been terminated, and his/her access to the office has been revoked. He/She should not be seen within the office premises.  
    <br><br>

    <div style="text-align: center;">
        @if ($data->hasMedia('avatar'))
            <img src="{{ $data->getFirstMediaUrl('avatar', 'profile_view') }}" alt="{{ $data->name }} Avatar" style="max-width: 150px; border-radius: 5%;">
        @else
            <img src="{{ asset('assets/img/avatars/no_image.png') }}" alt="No Avatar Available" style="max-width: 150px; border-radius: 50%;">
        @endif
    </div>

    Best Regards,  
    <br>
    Management  
</div>
<!-- End Content -->
@endsection
