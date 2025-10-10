@extends('layouts.email.app')


@section('email_title')
    <span style="text-align: center;">New Functionality Walkthrough: {{ $walkthrough->title }}</span>
@endsection


@section('content')
<!-- Start Content -->
<div>
    Hello {{ $user->alias_name }},
    <br>
    A new functionality walkthrough has been created by <b>{{ $walkthrough->creator->employee->alias_name ?? $walkthrough->creator->name }}</b>.
    <br>
    <br>
    <b>Walkthrough:</b> {{ $walkthrough->title }}
    <br>
    <br>
    This walkthrough contains step-by-step instructions to help you understand and use specific functionality in our system.
    <br>
    <br>
    Walkthrough Link:
    <a href="{{ route('administration.functionality_walkthrough.show', ['functionalityWalkthrough' => $walkthrough]) }}">
        <strong>View Functionality Walkthrough</strong>
    </a>.
</div>
<!-- End Content -->
@endsection
