@extends('layouts.email.app')


@section('email_title')
    <span style="text-align: center;">Attendance Issue Has Been {{ $data->status }}</span>
@endsection


@section('content')
<!-- Start Content -->
<div>
    Hello {{ $data->user->name }},
    <br>
    You Attendance Issue has been {{ $data->status }} by {{ $user->name }}. 
    <br>
    The Attendance Issue: 
    <a href="{{ route('administration.attendance.issue.show', ['issue' => $data]) }}">
        <strong>{{ $data->title }}</strong>
    </a>.
</div>
<!-- End Content -->
@endsection


