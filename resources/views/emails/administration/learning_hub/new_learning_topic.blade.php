@extends('layouts.email.app')


@section('email_title')
    <span style="text-align: center;">New Learning Topic: {{ $data->title }}</span>
@endsection


@section('content')
<!-- Start Content -->
<div>
    Hello {{ $user->alias_name }},
    <br>
    A new learning topic has been created by <b>{{ $data->creator->employee->alias_name ?? $data->creator->name }}</b>.
    <br>
    <br>
    <b>Topic:</b> {{ $data->title }}
    <br>
    <br>
    Learning Topic Link:
    <a href="{{ route('administration.learning_hub.show', ['learning_topic' => $data]) }}">
        <strong>View Learning Topic</strong>
    </a>.
</div>
<!-- End Content -->
@endsection
