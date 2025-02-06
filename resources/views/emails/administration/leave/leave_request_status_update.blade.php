@extends('layouts.email.app')


@section('email_title')
    <span style="text-align: center;">Leave Request Has Been {{ $data->status }}</span>
@endsection


@section('content')
<!-- Start Content -->
<div>
    Hello {{ $data->user->name }},
    <br>
    You Leave Request has been {{ $data->status }} by {{ $user->name }}. 
    <br>
    The Leave Request: 
    <a href="{{ route('administration.leave.history.show', ['leaveHistory' => $data]) }}">
        <strong>{{ __('Details.') }}</strong>
    </a>.
</div>
<!-- End Content -->
@endsection


