@extends('layouts.email.app')


@section('email_title')
    <span style="text-align: center;">New {{ $data->type }} Leave Request By {{ $data->user->name }}</span>
@endsection


@section('content')
<!-- Start Content -->
<div>
    Hello {{ $data->name }},
    <br>
    A New <b>{{ $data->type }} Leave Request</b> has been Created by <b>{{ $data->user->name }}</b>. 
    <br>
    The Leave Request: 
    <a href="{{ route('administration.leave.history.show', ['leaveHistory' => $data]) }}">
        <strong>{{ __('Details.') }}</strong>
    </a>.
</div>
<!-- End Content -->
@endsection


