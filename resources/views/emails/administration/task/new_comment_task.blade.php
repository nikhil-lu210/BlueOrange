@extends('layouts.email.app')


@section('email_title')
    <span style="text-align: center;">New Comment by <b>{{ $commenter->name }}</b></span>
@endsection


@section('content')
<!-- Start Content -->
<div>
    Hello {{ $user->name }},
    <br>
    There is a new comment by <b>{{ $commenter->name }}</b> for the task <b>({{ $data->title }})</b>. Please check the comment from task details.
    <br>
    <br>
    The Task: <a href="{{ route('administration.task.show', ['task' => $data, 'taskid' => $data->taskid]) }}"><strong>{{ $data->title }}</strong></a>.
</div>
<!-- End Content -->
@endsection


