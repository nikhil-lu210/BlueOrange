@extends('layouts.email.app')

@section('email_title')
    <span style="text-align: center;">Task Due in <b>{{ $daysLeft }}</b> Day(s)</span>
@endsection

@section('content')
<!-- Start Content -->
<div>
    Hello {{ $user->alias_name }},
    <br><br>
    This is a friendly reminder that the following task is due in <b>{{ $daysLeft }}</b> day(s).  
    Please ensure progress is updated accordingly to avoid delays.
    <br><br>
    Task: <a href="{{ route('administration.task.show', ['task' => $data, 'taskid' => $data->taskid]) }}"><strong>{{ $data->title }}</strong></a>  
    Deadline: <strong>{{ optional($data->deadline)->format('jS F Y') }}</strong>
    <br><br>
    Thank you for staying on top of your tasks.
</div>
<!-- End Content -->
@endsection
