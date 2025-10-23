@extends('layouts.email.app')


@section('email_title')
    <span style="text-align: center;">Suggestion Created By {{ $suggestion->user->alias_name }}</span>
@endsection


@section('content')
<!-- Start Content -->
<div>
    Hello {{ $user->alias_name }},
    <br>
    A Suggestion has been created by <b>{{ $suggestion->user->alias_name }}</b>. Please check the suggetion details and feel free to contact with {{ $suggestion->user->alias_name }} if any query arrives.
    <br>
    <br>
    The Suggestion: <a href="{{ route('administration.suggestion.show', ['suggestion' => $suggestion]) }}"><strong>{{ $suggestion->title }}</strong></a>.
</div>
<!-- End Content -->
@endsection
