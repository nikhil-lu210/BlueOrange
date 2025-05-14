@extends('layouts.email.app')


@section('email_title')
    <span style="text-align: center;">Task Has Been Stopped by <b>{{ $stopper->alias_name }}</b></span>
@endsection


@section('content')
<!-- Start Content -->
<div>
    Hello {{ $user->alias_name }},
    <br>
    The Task <b>({{ $data->title }})</b> has been stopped by <b>{{ $stopper->alias_name }}</b> at <b>{{ show_date_time($history->ends_at) }}</b>. Please check the task details or history and feel free to contact with {{ $stopper->alias_name }} if any query arrives.
    <br>
    <br>
    The Task: <a href="{{ route('administration.task.show', ['task' => $data, 'taskid' => $data->taskid]) }}"><strong>{{ $data->title }}</strong></a>.
    <br>
    The Task History: <a href="{{ route('administration.task.history.show', ['task' => $data]) }}"><strong>{{ route('administration.task.history.show', ['task' => $data]) }}</strong></a>.
</div>
<!-- End Content -->
@endsection


