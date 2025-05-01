@extends('layouts.email.app')


@section('email_title')
    <span style="text-align: center;">New File(s) Uploaded by <b>{{ $data->creator->alias_name }}</b></span>
@endsection


@section('content')
<!-- Start Content -->
<div>
    Hello {{ $user->alias_name }},
    <br>
    New files has been uploaded by <b>{{ $data->creator->alias_name }}</b> for the task <b>({{ $data->title }})</b>. Please check the uploaded files from task details.
    <br>
    <br>
    The Task: <a href="{{ route('administration.task.show', ['task' => $data, 'taskid' => $data->taskid]) }}"><strong>{{ $data->title }}</strong></a>.
</div>
<!-- End Content -->
@endsection


