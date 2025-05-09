@extends('administration.chatting.index')

@section('chat_body')

@livewire('administration.chatting.chat-body', ['user' => $user])

<!-- Sidebar Right -->
@include('administration.chatting.layouts.chat_contact_details')
<!-- /Sidebar Right -->
@endsection

@section('custom_script')
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Pusher
        window.Pusher = Pusher;

        @if(env('PUSHER_HOST'))
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ env('PUSHER_APP_KEY') }}',
            wsHost: '{{ env('PUSHER_HOST') }}',
            wsPort: {{ env('PUSHER_PORT') }},
            wssPort: {{ env('PUSHER_PORT') }},
            forceTLS: '{{ env('PUSHER_SCHEME') }}' === 'https',
            disableStats: true,
            enabledTransports: ['ws', 'wss']
        });
        @else
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ env('PUSHER_APP_KEY') }}',
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            forceTLS: true
        });
        @endif

        // Listen for private channel events
        Echo.private(`chat.{{ auth()->id() }}.{{ $user->id }}`)
            .listen('.message.sent', (e) => {
                // Livewire will handle this through the echo:chat listener
                console.log('New message received:', e);
                Livewire.dispatch('messageSent');
            });

        // Scroll to bottom of chat
        const scrollToBottom = function() {
            const chatHistory = document.querySelector('.chat-history-body');
            if (chatHistory) {
                chatHistory.scrollTop = chatHistory.scrollHeight;
            }
        };

        scrollToBottom();

        // Scroll to bottom after Livewire updates
        Livewire.hook('message.processed', (message, component) => {
            scrollToBottom();
        });
    });
</script>
@endsection
