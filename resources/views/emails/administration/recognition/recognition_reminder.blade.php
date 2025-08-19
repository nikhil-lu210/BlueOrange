@extends('layouts.email.app')


@section('email_title')
    <span style="text-align: center;">Do you want to submit Recognition?</span>
@endsection


@section('content')
<!-- Start Content -->
<div>
    Hello {{ $teamLeader->alias_name }},
    <br>
    You did not submit any Employee Recognition in last {{ config('recognition.reminder_days') }} days. Do you want to Recognize any Employee? If yes, then just login into {{ config('app.name') }}, then go to <b>Dashboard</b> and click on <b>Submit Recognition</b> button.
    <br>
    <br>
    Thanks
</div>
<!-- End Content -->
@endsection
