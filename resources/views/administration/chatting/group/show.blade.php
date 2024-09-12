@extends('administration.chatting.group.index')

@section('chat_body')


@livewire('administration.chatting.group-chat-body', ['group' => $group])


<!-- Sidebar Right -->
@include('administration.chatting.group.layouts.chat_group_details')
<!-- /Sidebar Right -->
@endsection