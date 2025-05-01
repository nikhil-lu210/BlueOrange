@extends('layouts.email.app')

@section('email_title')
    <span style="text-align: center;">Birthday Of <b>{{ $data->user->alias_name }}</b> ({{ $data->alias_name }})</span>
@endsection

@section('content')
<!-- Start Content -->
<div>
    Hello {{ $user->alias_name }},
    <br><br>
    Do you know, who born on today's date?
    <br><br>
    It's <b>{{ $data->user->alias_name }}</b> ({{ $data->alias_name }}). Yes, Today is <b>{{ $data->user->alias_name }}</b> ({{ $data->alias_name }}) Happy Birthday. You can wish him/her a warm birthday wish.
    <br><br>

    <div style="text-align: center;">
        @if ($data->user->hasMedia('avatar'))
            <img src="{{ $data->user->getFirstMediaUrl('avatar', 'profile_view') }}" alt="{{ $data->user->alias_name }} Avatar" style="max-width: 150px; border-radius: 5%;">
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
