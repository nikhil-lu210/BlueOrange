@extends('layouts.email.app')


@section('email_title')
    <span style="text-align: center;">New Task by <b>{{ $data->creator->alias_name }}</b></span>
@endsection


@section('content')
<!-- Start Content -->
<div>
    Hello {{ $user->alias_name }},
    <br>
    A new {{ $data->priority }} priority task has been assigned to you by {{ $data->creator->alias_name }}.
    <br>
    The Task: <a href="{{ route('administration.task.show', ['task' => $data, 'taskid' => $data->taskid]) }}"><strong>{{ $data->title }}</strong></a>.
</div>
<!-- End Content -->
@endsection


