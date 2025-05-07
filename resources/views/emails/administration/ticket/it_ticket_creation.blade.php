@extends('layouts.email.app')


@section('email_title')
    <span style="text-align: center;">IT Ticket Created By {{ $itTicket->creator->alias_name }}</span>
@endsection


@section('content')
<!-- Start Content -->
<div>
    Hello {{ $user->alias_name }},
    <br>
    An IT Ticket has been created by <b>{{ $itTicket->creator->alias_name }}</b>. Please check the ticket details and feel free to contact with {{ $itTicket->creator->alias_name }} if any query arrives.
    <br>
    <br>
    The IT Ticket: <a href="{{ route('administration.ticket.it_ticket.show', ['it_ticket' => $itTicket]) }}"><strong>{{ $itTicket->title }}</strong></a>.
</div>
<!-- End Content -->
@endsection
