@extends('administration.chatting.index')

@section('chat_body')


@livewire('administration.chatting.chat-body', ['user' => $user])


<!-- Sidebar Right -->
@include('administration.chatting.layouts.chat_contact_details')
<!-- /Sidebar Right -->
@endsection