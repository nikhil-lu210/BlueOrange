@extends('layouts.email.app')

@section('email_title')
    <span style="text-align: center;">Happy Birthday <b>{{ $data->user->name }}</b></span>
@endsection

@section('content')
<!-- Start Content -->
<div>
    Dear <b>{{ $data->user->name }}</b> ({{ $data->alias_name }}),  
    <br><br>
    Wish You A Many Many Happy Returns Of The Day.  
    <br><br>
    Hope you will have a successful life ahead and shine you future. 
    <br><br>

    Best Regards,  
    <br>
    {{ config('app.name') }}
</div>
<!-- End Content -->
@endsection
