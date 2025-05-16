/**
 * One-to-One Chat Browser Notifications
 * This file handles browser notifications specifically for one-to-one chat messages
 *
 * Features:
 * - Browser notifications for new chat messages
 * - Sound notifications
 * - Click to open chat with sender
 * - Automatic message status updates
 */

$(document).ready(function () {
    // Initialize notification audio
    // public/assets/audio/cyan_message.mp3
    const notificationSound = new Audio('/assets/audio/cyan_message.mp3');
    notificationSound.volume = 0.7; // Set volume to 70%

    /**
     * Check if user is currently on a specific chat conversation page
     * @returns {boolean} True if on a specific chat page
     */
    function isOnChatPage() {
        return window.location.pathname.includes('/chatting/one-to-one/') &&
               window.location.pathname.split('/').length > 3;
    }

    // Note: isOnChatListPage function removed as it was unused

    /**
     * Request browser notification permission
     * @returns {string} Permission status
     */
    async function requestNotificationPermission() {
        if (Notification.permission !== "granted") {
            const permission = await Notification.requestPermission();

            if (permission === "granted") {
                // Create welcome notification
                new Notification("Notifications Enabled", {
                    body: "You will now receive notifications for new chat messages",
                    icon: "https://cdn-icons-png.flaticon.com/512/1827/1827301.png"
                });

                notificationSound.play().catch(() => {
                    // Silent fail - user may not have interacted with page yet
                });
            }
            return permission;
        }
        return Notification.permission;
    }

    // Add a button to request permission (useful for browsers that block automatic requests)
    if (!document.getElementById('enable-notifications-btn')) {
        const navbarNav = document.querySelector('.navbar-nav');
        if (navbarNav && Notification.permission !== "granted") {
            const permissionBtn = document.createElement('li');
            permissionBtn.className = 'nav-item d-none d-lg-block';
            permissionBtn.innerHTML = `
                <button id="enable-notifications-btn" class="btn btn-sm btn-primary mx-3">
                    <i class="ti ti-bell me-1"></i> Enable Notifications
                </button>
            `;
            navbarNav.appendChild(permissionBtn);

            // Add click event
            document.getElementById('enable-notifications-btn').addEventListener('click', function() {
                requestNotificationPermission();
            });
        }
    }

    // Request notification permission when the page loads (only if not denied)
    if (Notification.permission !== "granted" && Notification.permission !== "denied") {
        requestNotificationPermission();
    }

    /**
     * Fetch unread one-to-one chat messages and show notifications
     */
    function fetchUnreadChatMessages() {
        // Get current chat user ID if we're on a chat page
        let currentChatUserId = null;
        if (isOnChatPage()) {
            // Extract user ID from URL path
            const pathParts = window.location.pathname.split('/');
            if (pathParts.length >= 4) {
                currentChatUserId = pathParts[pathParts.length - 2]; // User ID is the second-to-last part
            }
        }

        // Prepare URL with query parameter if needed
        let fetchUrl = unreadOneToOneMessagesNotificationUrl;
        let params = [];

        if (currentChatUserId) {
            params.push('current_chat_user_id=' + currentChatUserId);
        }

        // Use timestamp instead of bypass_cache to avoid caching issues
        params.push('_t=' + new Date().getTime());

        if (params.length > 0) {
            fetchUrl += '?' + params.join('&');
        }

        $.ajax({
            url: fetchUrl,
            type: 'GET',
            timeout: 5000, // 5 second timeout
            success: function(response) {
                // Extract messages from the response format
                const messages = response.messages || [];

                if (messages && messages.length > 0) {
                    let notifiedMessageIds = JSON.parse(localStorage.getItem("newMessageNotification")) || [];
                    let hasNewMessages = false;

                    messages.forEach(message => {
                        if (!notifiedMessageIds.includes(message.id)) {
                            hasNewMessages = true;

                            // Check if browser notifications are allowed
                            if (Notification.permission === "granted") {
                                try {
                                    let notif = new Notification("New Message from " + message.sender.name, {
                                        body: message.message,
                                        icon: "https://cdn-icons-png.flaticon.com/512/1827/1827301.png"
                                    });

                                    notif.onclick = function () {
                                        // Get the base URL for the chat page
                                        const baseUrl = window.location.origin;
                                        const chatUrl = baseUrl + '/chatting/one-to-one/read-browser-notification-message/' +
                                                      message.sender.id + '/' + message.sender.userid;

                                        // Open in a new tab
                                        window.open(chatUrl, "_blank");

                                        // Also mark the message as read via AJAX (without redirecting)
                                        const readUrl = markOneToOneMessageReadUrl + '/' +
                                                      message.sender.id + '/' + message.sender.userid;

                                        $.get(readUrl).fail(function(error) {
                                            console.error("Error marking message as read:", error);
                                        });
                                    };

                                    // Mark this message as notified
                                    notifiedMessageIds.push(message.id);
                                    localStorage.setItem("newMessageNotification", JSON.stringify(notifiedMessageIds));
                                } catch (error) {
                                    console.error("Error creating notification:", error);
                                }
                            } else if (Notification.permission !== "denied") {
                                requestNotificationPermission();
                            }
                        }
                    });

                    // Play sound if there are new messages
                    if (hasNewMessages) {
                        notificationSound.play().catch(() => {
                            // Silent fail - user may not have interacted with page yet
                        });
                    }
                }
            },
            error: function(_, status, error) {
                // Only log error in console if it's not a timeout
                if (status !== 'timeout') {
                    console.error("Error fetching new messages:", error);
                }

                // Don't retry immediately on error to avoid flooding the server
                // The next check will happen on the regular interval
            }
        });
    }

    // Check for new messages every 15 seconds
    setInterval(fetchUnreadChatMessages, 15000);

    // Also check when the tab becomes visible
    document.addEventListener("visibilitychange", function () {
        if (document.visibilityState === "visible") {
            fetchUnreadChatMessages();
        }
    });

    // Run an initial check for messages
    setTimeout(fetchUnreadChatMessages, 2000);
});
