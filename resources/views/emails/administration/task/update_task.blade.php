@extends('layouts.email.app')


@section('email_title')
    <span style="text-align: center;">Task Updated by <b>{{ $data->creator->name }}</b></span>
@endsection


@section('content')
<!-- Start Content -->
<div>
    Hello {{ $user->name }},
    <br>
    The Task <b>({{ $data->title }})</b> has been updated by {{ $data->creator->name }}. Please check the updated task details and feel free to contact with {{ $data->creator->name }} if any query arrives.
    <br>
    <br>
    The Task: <a href="{{ route('administration.task.show', ['task' => $data, 'taskid' => $data->taskid]) }}"><strong>{{ $data->title }}</strong></a>.
</div>
<!-- End Content -->
@endsection


