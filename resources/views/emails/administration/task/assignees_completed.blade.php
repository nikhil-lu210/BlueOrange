@extends('layouts.email.app')

@section('email_title')
    <span style="text-align: center;">Task Ready for Review</span>
@endsection

@section('content')
<!-- Start Content -->
<div>
    Hello {{ $user->alias_name }},
    <br><br>
    All assignees have reported <strong>100% progress</strong> on the task below.  
    Please review their work and, if confirmed, update the task status to <strong>Completed</strong>.
    <br><br>

    <strong>Task:</strong> 
    <a href="{{ route('administration.task.show', ['task' => $data, 'taskid' => $data->taskid]) }}">
        {{ $data->title }}
    </a>
    <br>
    <strong>Deadline:</strong> {{ optional($data->deadline)->format('jS F Y') }}
    <br><br>

    Thank you for your prompt attention to this task.
</div>
<!-- End Content -->
@endsection
