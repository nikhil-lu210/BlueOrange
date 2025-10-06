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
        // Set up a polling mechanism for chat updates with error handling
        let refreshInterval;
        let consecutiveErrors = 0;
        let currentInterval = 30000; // Start with 30 seconds to reduce server load
        let maxConsecutiveErrors = 3;
        let maxInterval = 120000; // Max 2 minutes

        const startPolling = () => {
            refreshInterval = setInterval(() => {
                try {
                    Livewire.dispatch('refresh');
                    consecutiveErrors = 0; // Reset on success
                    currentInterval = 30000; // Reset to normal interval
                } catch (error) {
                    consecutiveErrors++;
                    console.error('Livewire refresh error:', error);
                    
                    // Implement exponential backoff
                    if (consecutiveErrors >= maxConsecutiveErrors) {
                        currentInterval = Math.min(currentInterval * 2, maxInterval);
                        console.warn(`Implementing exponential backoff. Next refresh in ${currentInterval/1000} seconds`);
                        
                        // Clear current interval and restart with backoff
                        clearInterval(refreshInterval);
                        setTimeout(startPolling, currentInterval);
                        return;
                    }
                }
            }, currentInterval);
        };

        // Start polling
        startPolling();

        // Function to refresh CSRF token
        const refreshCsrfToken = function() {
            return fetch('{{ route("csrf.refresh") }}', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.token) {
                    // Update all CSRF tokens on the page
                    document.querySelectorAll('input[name="_token"]').forEach(input => {
                        input.value = data.token;
                    });

                    // Also update the meta tag if it exists
                    const metaToken = document.querySelector('meta[name="csrf-token"]');
                    if (metaToken) {
                        metaToken.setAttribute('content', data.token);
                    }

                    // Update Livewire's CSRF token
                    if (window.Livewire) {
                        window.Livewire.csrfToken = data.token;
                    }

                    console.log('CSRF token refreshed successfully at', data.timestamp);
                    return true;
                } else {
                    throw new Error(data.message || 'Failed to refresh CSRF token');
                }
            })
            .catch(error => {
                console.error('Error refreshing CSRF token:', error);
                
                // If it's a 419 error, try to reload the page
                if (error.message.includes('419') || error.message.includes('Page Expired')) {
                    console.warn('CSRF token expired, reloading page...');
                    if (confirm('Your session has expired. Click OK to refresh the page.')) {
                        window.location.reload();
                    }
                }
                return false;
            });
        };

        // Refresh CSRF token less frequently (every 15 minutes) to reduce server load
        setInterval(refreshCsrfToken, 15 * 60 * 1000);

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
            if (message && (message.includes('419') || message.includes('CSRF') || message.includes('token') || message.includes('Page Expired'))) {
                console.error('CSRF token error detected in parent window, refreshing token...');
                
                // Try to refresh the token first
                refreshCsrfToken().then(success => {
                    if (success) {
                        // Token refreshed successfully, notify user
                        console.log('CSRF token refreshed, retrying operation...');
                        // The user can try their action again
                    } else {
                        // Token refresh failed, reload page
                        console.warn('CSRF token refresh failed, reloading page...');
                        if (confirm('Your session has expired. Click OK to refresh the page.')) {
                            window.location.reload();
                        }
                    }
                });

                // Prevent the default error behavior
                event.preventDefault();
            }
        });
    });
</script>
@endsection
