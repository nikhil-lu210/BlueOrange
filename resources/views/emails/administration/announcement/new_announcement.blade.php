@extends('layouts.email.app')


@section('email_title')
    <span style="text-align: center;">New Announcement</span>
@endsection


@section('content')
<!-- Start Content -->
<div>
    Hello {{ $user->alias_name }},
    <br>
    There is a new Announcement created by {{ $data->announcer->alias_name }}.
    <br>
    The Announcement: <a href="{{ route('administration.announcement.show', ['announcement' => $data]) }}"><strong>{{ $data->title }}</strong></a>.
</div>
<!-- End Content -->
@endsection


