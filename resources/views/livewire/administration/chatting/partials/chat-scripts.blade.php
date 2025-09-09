<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-resize textarea on page load
        const textarea = document.querySelector('.message-input');
        if (textarea) {
            // Initial resize
            textarea.style.height = 'auto';
            textarea.style.height = (textarea.scrollHeight) + 'px';

            // Resize on input
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });

            // Handle Enter and Shift+Enter
            textarea.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    if (e.shiftKey) {
                        // Wait for the new line to be added
                        setTimeout(() => {
                            this.style.height = 'auto';
                            this.style.height = (this.scrollHeight) + 'px';
                        }, 0);
                    } else {
                        // Prevent default and trigger send button click
                        e.preventDefault();
                        document.querySelector('.send-msg-btn').click();
                        return false;
                    }
                }
            });
        }

        // Scroll to bottom of chat
        const scrollToBottom = function() {
            const chatHistory = document.querySelector('.chat-history-body');
            if (chatHistory) {
                chatHistory.scrollTop = chatHistory.scrollHeight;
            }
        };

        // Scroll to input when replying
        const scrollToInput = function() {
            const chatFooter = document.querySelector('.chat-history-footer');
            if (chatFooter) {
                chatFooter.scrollIntoView({ behavior: 'smooth' });

                // Focus on the input field
                setTimeout(() => {
                    const textarea = document.querySelector('.message-input');
                    if (textarea) {
                        textarea.focus();
                    }
                }, 300);
            }
        };

        // Function to refresh CSRF token
        const refreshCsrfToken = function() {
            // Get the current CSRF token from the meta tag
            const metaToken = document.querySelector('meta[name="csrf-token"]');
            const currentToken = metaToken ? metaToken.getAttribute('content') : null;

            // Make a request to get a fresh token
            return fetch('{{ route("csrf.refresh") }}', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': currentToken || ''
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

        // Also refresh when the user becomes active after being idle
        let idleTime = 0;
        const idleInterval = setInterval(() => {
            idleTime = idleTime + 1;
            if (idleTime > 10) { // 10 minutes of inactivity
                refreshCsrfToken();
                idleTime = 0;
            }
        }, 60 * 1000); // Check every minute

        // Reset idle timer on user activity
        const resetIdleTime = function() {
            idleTime = 0;
        };

        // Monitor user activity
        ['mousemove', 'keypress', 'scroll', 'click', 'touchstart'].forEach(event => {
            document.addEventListener(event, resetIdleTime, true);
        });

        scrollToBottom();

        // Re-initialize event listeners after Livewire updates
        document.addEventListener('livewire:load', function() {
            Livewire.hook('message.processed', (message, component) => {
                // Check if replyToMessageId was updated
                if (message.updateQueue && message.updateQueue.some(update => update.payload.name === 'replyToMessageId')) {
                    if (message.updateQueue.find(update => update.payload.name === 'replyToMessageId').payload.value) {
                        // If replying to a message, scroll to input
                        scrollToInput();
                    } else {
                        // If canceling reply, scroll to bottom
                        scrollToBottom();
                    }
                } else {
                    // For other updates, scroll to bottom
                    scrollToBottom();
                }
            });

            // Listen for Livewire events
            Livewire.on('messageSent', () => {
                scrollToBottom();
            });

            // Listen for session expired event
            Livewire.on('sessionExpired', () => {
                console.error('Session expired, refreshing token...');
                refreshCsrfToken();

                // Notify the user
                alert('Your session expired. We\'ve refreshed it for you. Please try again.');
            });

            // Handle Livewire errors, especially for CSRF token issues
            document.addEventListener('livewire:error', function(event) {
                const message = event.detail.message;
                if (message.includes('419') || message.includes('CSRF') || message.includes('token') || message.includes('Page Expired')) {
                    console.error('CSRF token error detected, refreshing token...');
                    
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

            // Add manual refresh functionality
            const refreshButton = document.querySelector('.chat-history-refresh');
            if (refreshButton) {
                refreshButton.addEventListener('click', function() {
                    refreshCsrfToken(); // Refresh CSRF token when manually refreshing
                    Livewire.dispatch('refresh');
                });
            }
        });
    });
</script>

