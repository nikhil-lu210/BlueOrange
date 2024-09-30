@extends('layouts.email.app')


@section('email_title')
    <span style="text-align: center;">Task Status Updated To <b>{{ $data->status }}</b></span>
@endsection


@section('content')
<!-- Start Content -->
<div>
    Hello {{ $user->name }},
    <br>
    The Task <b>({{ $data->title }})'s</b> status has been updated to <b>{{ $data->status }}</b> by {{ $data->creator->name }}.
    <br>
    <br>
    The Task: <a href="{{ route('administration.task.show', ['task' => $data, 'taskid' => $data->taskid]) }}"><strong>{{ $data->title }}</strong></a>.
</div>
<!-- End Content -->
@endsection


