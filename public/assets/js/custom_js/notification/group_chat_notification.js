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

    // Track consecutive errors to implement exponential backoff
    let consecutiveErrors = 0;
    let maxConsecutiveErrors = 3;
    let currentInterval = 15000; // Start with 15 seconds
    let maxInterval = 300000; // Max 5 minutes

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
            timeout: 10000, // Increased timeout to 10 seconds
            success: function(response) {
                // Reset error counter on successful request
                consecutiveErrors = 0;
                currentInterval = 15000; // Reset to normal interval

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
                }
            },
            error: function(xhr, status, error) {
                consecutiveErrors++;

                // Log error details for debugging
                console.error("Error fetching new group messages:", {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    error: error,
                    consecutiveErrors: consecutiveErrors
                });

                // If we get a 508 Loop Detected or too many consecutive errors, implement exponential backoff
                if (xhr.status === 508 || consecutiveErrors >= maxConsecutiveErrors) {
                    currentInterval = Math.min(currentInterval * 2, maxInterval);
                    console.warn(`Implementing exponential backoff. Next check in ${currentInterval/1000} seconds`);

                    // Clear the current interval and set a new one with backoff
                    clearInterval(window.groupChatInterval);
                    window.groupChatInterval = setInterval(fetchUnreadGroupMessages, currentInterval);
                }

                // If we get authentication errors (401, 403), stop polling
                if (xhr.status === 401 || xhr.status === 403) {
                    console.error("Authentication error. Stopping group chat notifications.");
                    clearInterval(window.groupChatInterval);
                }
            }
        });
    }

    // Check for new messages every 15 seconds
    window.groupChatInterval = setInterval(fetchUnreadGroupMessages, 15000);

    // Also check when the tab becomes visible
    document.addEventListener("visibilitychange", function () {
        if (document.visibilityState === "visible") {
            fetchUnreadGroupMessages();
        }
    });

    // Run an initial check for messages
    setTimeout(fetchUnreadGroupMessages, 2000);
});
