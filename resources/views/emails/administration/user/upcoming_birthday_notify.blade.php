@extends('layouts.email.app')

@section('email_title')
    <span style="text-align: center;">Birthday Of <b>{{ $data->user->name }}</b> ({{ $data->alias_name }}) on <b>{{ \Carbon\Carbon::parse($data->birth_date)->format('jS F') }}</b></span>
@endsection

@section('content')
<!-- Start Content -->
<div>
    Hello {{ $user->name }},  
    <br><br>
    It's <b>{{ $data->user->name }}</b> ({{ $data->alias_name }})'s Birthday is coming on {{ \Carbon\Carbon::parse($data->birth_date)->format('jS F') }}.
    <br><br>

    <div style="text-align: center;">
        @if ($data->user->hasMedia('avatar'))
            <img src="{{ $data->user->getFirstMediaUrl('avatar', 'profile_view') }}" alt="{{ $data->user->name }} Avatar" style="max-width: 150px; border-radius: 5%;">
        @else
            <img src="{{ asset('assets/img/avatars/no_image.png') }}" alt="No Avatar Available" style="max-width: 150px; border-radius: 50%;">
        @endif
    </div>

    Best Regards,  
    <br>
    {{ config('app.name') }}
</div>
<!-- End Content -->
@endsection
