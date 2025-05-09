// Initialize Pusher
const pusher = new Pusher(PUSHER_APP_KEY, {
    wsHost: PUSHER_HOST,
    wsPort: PUSHER_PORT,
    wssPort: PUSHER_PORT,
    forceTLS: PUSHER_SCHEME === 'https',
    enabledTransports: ['ws', 'wss'],
    disableStats: true
});

// Initialize Echo
window.Echo = new Echo({
    broadcaster: 'pusher',
    client: pusher
});

// Listen for private channel events
function initializeEchoListeners(userId, receiverId) {
    // Listen for private channel events
    Echo.private(`chat.${userId}.${receiverId}`)
        .listen('.message.sent', (e) => {
            // Livewire will handle this through the echo:chat listener
            console.log('New message received:', e);
        });
}

// Function to scroll to bottom of chat
function scrollToBottom() {
    const chatHistory = document.querySelector('.chat-history-body');
    if (chatHistory) {
        chatHistory.scrollTop = chatHistory.scrollHeight;
    }
}

// Scroll to bottom on page load
document.addEventListener('DOMContentLoaded', function() {
    scrollToBottom();
    
    // Scroll to bottom after Livewire updates
    document.addEventListener('livewire:load', function() {
        Livewire.hook('message.processed', (message, component) => {
            scrollToBottom();
        });
    });
});
