@extends('layouts.email.app')

@section('email_title')
    <span style="text-align: center;">
        🎉 Birthday of <b>{{ $data->user->alias_name }}</b> ({{ $data->user->name }})
    </span>
@endsection

@section('content')
<!-- Start Content -->
<div>
    Hello {{ $user->alias_name }},
    <br><br>
    Did you know? Today is a special day!
    <br><br>
    It’s the birthday of <b>{{ $data->user->alias_name }}</b> ({{ $data->user->name }}).
    Take a moment to send your best wishes and make the day even more memorable. 🎂✨
    <br><br>

    <div style="text-align: center; margin: 20px 0;">
        @if ($data->user->hasMedia('avatar'))
            <img src="{{ $data->user->getFirstMediaUrl('avatar', 'profile_view_color') }}"
                 alt="{{ $data->user->alias_name }} Avatar"
                 style="max-width: 150px; border-radius: 8px;">
        @else
            <img src="{{ asset('assets/img/avatars/no_image.png') }}"
                 alt="No Avatar Available"
                 style="max-width: 150px; border-radius: 50%;">
        @endif
    </div>

    <div style="text-align: center; margin: 25px 0;">
        <a href="{{ route('administration.chatting.show', ['user' => $data->user, 'userid' => $data->user->userid]) }}"
           style="display: inline-block; padding: 12px 24px; background-color: #7367f0; color: #ffffff; text-decoration: none; font-size: 16px; border-radius: 6px; font-weight: bold;">
            Wish {{ $data->user->employee->gender === 'Male' ? 'Him' : 'Her' }} Happy Birthday
        </a>
    </div>

    Warm regards,
    <br>
    {{ config('app.name') }}
</div>
<!-- End Content -->
@endsection
