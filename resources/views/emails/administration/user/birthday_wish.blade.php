@extends('layouts.email.app')

@section('email_title')
    <span style="text-align: center;">Happy Birthday <b>{{ $data->user->name }}</b></span>
@endsection

@section('content')
<!-- Start Content -->
<div>
    Dear <b>{{ $data->user->name }}</b> ({{ $data->alias_name }}),  
    <br><br>
    {{ $wish }}
    <br><br>
    Once Again, Happy Birthday To You. 
    <br><br>

    Best Regards,  
    <br>
    {{ config('app.name') }}
</div>
<!-- End Content -->
@endsection
