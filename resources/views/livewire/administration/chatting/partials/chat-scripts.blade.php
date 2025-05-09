<style>
    .message-input {
        min-height: 38px;
        max-height: 150px;
        overflow-y: auto;
        resize: none;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-resize textarea
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

            // Resize when Shift+Enter is pressed
            textarea.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && e.shiftKey) {
                    // Wait for the new line to be added
                    setTimeout(() => {
                        this.style.height = 'auto';
                        this.style.height = (this.scrollHeight) + 'px';
                    }, 0);
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
        });
    });
</style>
