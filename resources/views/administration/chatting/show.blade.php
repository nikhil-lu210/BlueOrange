@extends('administration.chatting.index')

@section('chat_body')

@livewire('administration.chatting.chat-body', ['user' => $user])

<!-- Sidebar Right -->
@include('administration.chatting.layouts.chat_contact_details')
<!-- /Sidebar Right -->
@endsection



@section('custom_script')
<!-- Ensure jQuery is loaded first -->
<script>
    // Check if jQuery is already loaded, if not, load it
    if (typeof jQuery === 'undefined') {
        document.write('<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"><\/script>');
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set up a polling mechanism for chat updates
        const refreshInterval = setInterval(() => {
            Livewire.dispatch('refresh');
        }, 5000);

        // Function to refresh CSRF token
        const refreshCsrfToken = function() {
            fetch('{{ route("csrf.refresh") }}', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.token) {
                    // Update all CSRF tokens on the page
                    document.querySelectorAll('input[name="_token"]').forEach(input => {
                        input.value = data.token;
                    });

                    // Also update the meta tag if it exists
                    const metaToken = document.querySelector('meta[name="csrf-token"]');
                    if (metaToken) {
                        metaToken.setAttribute('content', data.token);
                    }

                    console.log('CSRF token refreshed in parent window');
                }
            })
            .catch(error => {
                console.error('Error refreshing CSRF token:', error);
            });
        };

        // Refresh CSRF token periodically (every 10 minutes)
        setInterval(refreshCsrfToken, 10 * 60 * 1000);

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

        // Handle Livewire errors
        document.addEventListener('livewire:error', function(event) {
            const message = event.detail.message;
            if (message && (message.includes('419') || message.includes('CSRF') || message.includes('token'))) {
                console.error('CSRF token error detected in parent window, refreshing token...');
                refreshCsrfToken();

                // Notify the user
                alert('Your session expired. We\'ve refreshed it for you. Please try again.');

                // Prevent the default error behavior
                event.preventDefault();
            }
        });
    });
</script>
@endsection
