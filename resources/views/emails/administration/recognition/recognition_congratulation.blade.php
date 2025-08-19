@extends('layouts.email.app')


@section('email_title')
    <span style="text-align: center;">Congratulations For Getting Recognition</span>
@endsection


@section('content')
<!-- Start Content -->
<div>
    Hello {{ $recognition->user->alias_name }},
    <br>
    You have been recognized by your Team Leader ({{ $recognition->recognizer->alias_name }}) for <b>{{ $recognition->category }}</b>. You got total marks <b>{{ $recognition->total_mark }}</b>.
    <br>
    <b>Comment:</b> <q>{{ $recognition->comment }}</q>
    <br>
    Keep up the great work!
    <br>
    Thanks
</div>
<!-- End Content -->
@endsection
