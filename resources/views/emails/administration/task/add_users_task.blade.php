@extends('layouts.email.app')


@section('email_title')
    <span style="text-align: center;">A Task Assigned by <b>{{ $data->creator->name }}</b></span>
@endsection


@section('content')
<!-- Start Content -->
<div>
    Hello {{ $user->name }},
    <br>
    A task has been assigned to you by <b>{{ $data->creator->name }}</b>. Please check the task details and requirements. 
    <br>
    If you have any questions regarding the task, please feel free to contact with <b>{{ $data->creator->name }}</b>.
    <br>
    <br>
    The Task: <a href="{{ route('administration.task.show', ['task' => $data, 'taskid' => $data->taskid]) }}"><strong>{{ $data->title }}</strong></a>.
</div>
<!-- End Content -->
@endsection


