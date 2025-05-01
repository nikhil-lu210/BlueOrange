@extends('layouts.email.app')


@section('email_title')
    <span style="text-align: center;">{{ show_date($data->date) }} Work Update By {{ $data->user->name }}</span>
@endsection


@section('content')
<!-- Start Content -->
<div>
    Hello {{ $user->alias_name }},
    <br>
    The <b>Work Update of {{ show_date($data->date) }}</b> has been submitted by <b>{{ $data->user->alias_name }}</b>.
    <br>
    Work Update Link:
    <a href="{{ route('administration.daily_work_update.show', ['daily_work_update' => $data]) }}">
        <strong>Work Update of {{ show_date($data->date) }}</strong>
    </a>.
</div>
<!-- End Content -->
@endsection


