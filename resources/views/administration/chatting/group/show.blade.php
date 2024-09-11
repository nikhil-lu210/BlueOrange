@extends('administration.chatting.group.index')

@section('chat_body')


@livewire('administration.chatting.group.chat-body', ['user' => $user])


<!-- Sidebar Right -->
@include('administration.chatting.group.layouts.chat_contact_details')
<!-- /Sidebar Right -->
@endsection