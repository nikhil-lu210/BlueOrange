@extends('layouts.email.app')


@section('email_title')
    <span style="text-align: center;">New {{ $data->type }} Attendance Issue By {{ $data->user->alias_name }}</span>
@endsection


@section('content')
<!-- Start Content -->
<div>
    Hello {{ $data->user->active_team_leader->alias_name }},
    <br>
    A New <b>{{ $data->type }} Attendance Issue</b> has been Created by <b>{{ $data->user->alias_name }}</b>.
    <br>
    The Attendance Issue:
    <a href="{{ route('administration.attendance.issue.show', ['issue' => $data]) }}">
        <strong>{{ $data->title }}</strong>
    </a>.
</div>
<!-- End Content -->
@endsection


