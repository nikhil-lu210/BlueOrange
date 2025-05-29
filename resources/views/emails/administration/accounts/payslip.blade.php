@extends('layouts.email.app')


@section('email_title')
    <span style="text-align: center;">Payslip of {{ show_month($data->for_month) }}</span>
@endsection


@section('content')
<!-- Start Content -->
<div>
    Hello {{ $user->employee->alias_name }},
    <br>
    The payslip of <b>{{ show_month($data->for_month) }}</b> has been attatched below.
    <br>
    Or you can view your payslip: <a href="{{ route('application.accounts.salary.monthly.show', ['payslip_id' => $data->payslip_id, 'userid' => $data->user->userid, 'id' => encrypt($data->id)]) }}"><strong>Here</strong></a>.
</div>
<!-- End Content -->
@endsection


