/**
 * Group Chat Browser Notifications
 * This file handles browser notifications specifically for group chat messages
 *
 * Features:
 * - Browser notifications for new group chat messages
 * - Sound notifications
 * - Click to open chat with the group
 * - Only notifies group members who haven't seen the message
 */

$(document).ready(function () {
    // Initialize notification audio
    // public/assets/audio/message_notification.mp3
    const notificationSound = new Audio('/assets/audio/message_notification.mp3');
    notificationSound.volume = 0.7; // Set volume to 70%

    /**
     * Check if user is currently on a specific group chat page
     * @returns {boolean} True if on a specific group chat page
     */
    function isOnGroupChatPage() {
        return window.location.pathname.includes('/chatting/group/') &&
               window.location.pathname.split('/').length > 3;
    }

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
    // Only add if not already added by one-to-one chat notification
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
     * Fetch unread group chat messages and show notifications
     */
    function fetchUnreadGroupMessages() {
        // Get current group ID if we're on a group chat page
        let currentGroupId = null;
        if (isOnGroupChatPage()) {
            // Extract group ID from URL path
            const pathParts = window.location.pathname.split('/');
            if (pathParts.length >= 4) {
                currentGroupId = pathParts[pathParts.length - 2]; // Group ID is the second-to-last part
            }
        }

        // Prepare URL with query parameter if needed
        let fetchUrl = unreadGroupMessagesNotificationUrl;
        let params = [];

        if (currentGroupId) {
            params.push('current_group_id=' + currentGroupId);
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
                const messages = response || [];

                if (messages && messages.length > 0) {
                    let notifiedGroupMessageIds = JSON.parse(localStorage.getItem("newGroupMessageNotifications")) || [];
                    let hasNewMessages = false;

                    messages.forEach(message => {
                        if (!notifiedGroupMessageIds.includes(message.id)) {
                            hasNewMessages = true;

                            // Check if browser notifications are allowed
                            if (Notification.permission === "granted") {
                                try {
                                    let notif = new Notification("New Message in " + message.group_name, {
                                        body: message.sender_name + ": " + message.message,
                                        icon: "https://cdn-icons-png.flaticon.com/512/1827/1827301.png"
                                    });

                                    notif.onclick = function () {
                                        // The URL already includes "/show" in the markGroupMessageReadUrl variable
                                        // We just need to add the group ID
                                        let chatUrl = markGroupMessageReadUrl;

                                        // Check if the URL already ends with a slash
                                        if (!chatUrl.endsWith('/')) {
                                            chatUrl += '/';
                                        }

                                        chatUrl += message.chatting_group_id;

                                        // Open in a new tab
                                        window.open(chatUrl, "_blank");
                                    };

                                    // Mark this message as notified
                                    notifiedGroupMessageIds.push(message.id);
                                    localStorage.setItem("newGroupMessageNotifications", JSON.stringify(notifiedGroupMessageIds));
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
                    console.error("Error fetching new group messages:", error);
                }

                // Don't retry immediately on error to avoid flooding the server
                // The next check will happen on the regular interval
            }
        });
    }

    // Check for new messages every 15 seconds
    setInterval(fetchUnreadGroupMessages, 15000);

    // Also check when the tab becomes visible
    document.addEventListener("visibilitychange", function () {
        if (document.visibilityState === "visible") {
            fetchUnreadGroupMessages();
        }
    });

    // Run an initial check for messages
    setTimeout(fetchUnreadGroupMessages, 2000);
});
