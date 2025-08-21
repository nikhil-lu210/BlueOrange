@extends('layouts.email.app')

@section('email_title')
    <span style="text-align: center;">Task Overdue Notification</span>
@endsection

@section('content')
<!-- Start Content -->
<div>
    Dear {{ $user->alias_name }},
    <br><br>
    This is a reminder that the following task has passed its deadline and requires your immediate attention:
    <br><br>

    <strong>Task:</strong> 
    <a href="{{ route('administration.task.show', ['task' => $data, 'taskid' => $data->taskid]) }}">
        {{ $data->title }}
    </a><br>
    <strong>Deadline:</strong> {{ optional($data->deadline)->format('jS F Y') }}<br>
    <strong>Status:</strong> Overdue
    <br><br>

    Please review the task, check progress, and take the necessary action as soon as possible.
    <br><br>

    Thank you for your prompt attention.
    <br><br>

    Regards,<br>
    <strong>{{ config('app.name') }} Task Management System</strong>
</div>
<!-- End Content -->
@endsection
