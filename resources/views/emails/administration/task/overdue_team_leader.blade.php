@extends('layouts.email.app')

@section('email_title')
    <span style="text-align: center;">Task Due in <b>{{ $daysLeft }}</b> day(s)</span>
@endsection

@section('content')
<div>
    Hello {{ $user->alias_name }},
    <br>
    Task is due in <b>{{ $daysLeft }}</b> day(s). Please update progress.
    <br><br>
    The Task: <a href="{{ route('administration.task.show', ['task' => $data, 'taskid' => $data->taskid]) }}">
        <strong>{{ $data->title }}</strong>
    </a>.
    <br>
    Deadline: <strong>{{ optional($data->deadline)->format('jS F Y') }}</strong>
</div>
@endsection