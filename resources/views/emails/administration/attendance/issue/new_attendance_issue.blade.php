@extends('layouts.email.app')


@section('email_title')
    <span style="text-align: center;">New {{ $data->type }} Attendance Issue By {{ $data->user->name }}</span>
@endsection


@section('content')
<!-- Start Content -->
<div>
    Hello {{ $data->name }},
    <br>
    A New <b>{{ $data->type }} Attendance Issue</b> has been Created by <b>{{ $data->user->name }}</b>. 
    <br>
    The Attendance Issue: 
    <a href="{{ route('administration.attendance.issue.show', ['issue' => $data]) }}">
        <strong>{{ $data->title }}</strong>
    </a>.
</div>
<!-- End Content -->
@endsection


