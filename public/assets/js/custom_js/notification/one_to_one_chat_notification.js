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
    // Global notification manager to prevent conflicts
    if (!window.notificationManager) {
        window.notificationManager = {
            activePolling: null,
            startPolling: function(type, callback, interval) {
                // Stop any existing polling
                if (this.activePolling) {
                    clearInterval(this.activePolling);
                }
                
                // Start new polling
                this.activePolling = setInterval(callback, interval);
                // console.log(`Started ${type} notification polling`);
            },
            stopPolling: function() {
                if (this.activePolling) {
                    clearInterval(this.activePolling);
                    this.activePolling = null;
                    // console.log('Stopped notification polling');
                }
            }
        };
    }

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

    // Track consecutive errors to implement exponential backoff
    let consecutiveErrors = 0;
    let maxConsecutiveErrors = 3;
    let currentInterval = 30000; // Start with 30 seconds (reduced frequency)
    let maxInterval = 600000; // Max 10 minutes
    let isPollingActive = true;

    /**
     * Fetch unread one-to-one chat messages and show notifications
     */
    function fetchUnreadChatMessages() {
        // Stop polling if we've reached max errors or if polling is disabled
        if (!isPollingActive || consecutiveErrors >= maxConsecutiveErrors * 2) {
            console.warn('One-to-one chat notifications stopped due to too many errors');
            clearInterval(window.oneToOneChatInterval);
            return;
        }

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
            timeout: 10000, // Increased timeout to 10 seconds
            success: function(response) {
                // Reset error counter on successful request
                consecutiveErrors = 0;
                currentInterval = 30000; // Reset to normal interval

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
                }
            },
            error: function(xhr, status, error) {
                consecutiveErrors++;

                // Log error details for debugging
                console.error("Error fetching new messages:", {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    error: error,
                    consecutiveErrors: consecutiveErrors
                });

                // If we get server errors (500, 502, 503, 504) or too many consecutive errors, implement exponential backoff
                if ([500, 502, 503, 504, 508].includes(xhr.status) || consecutiveErrors >= maxConsecutiveErrors) {
                    currentInterval = Math.min(currentInterval * 2, maxInterval);
                    console.warn(`Implementing exponential backoff. Next check in ${currentInterval/1000} seconds`);

                    // Clear the current interval and set a new one with backoff
                    clearInterval(window.oneToOneChatInterval);
                    window.oneToOneChatInterval = setInterval(fetchUnreadChatMessages, currentInterval);
                }

                // If we get authentication errors (401, 403), stop polling
                if (xhr.status === 401 || xhr.status === 403) {
                    console.error("Authentication error. Stopping chat notifications.");
                    isPollingActive = false;
                    clearInterval(window.oneToOneChatInterval);
                }

                // If we get too many consecutive errors, stop polling completely
                if (consecutiveErrors >= maxConsecutiveErrors * 2) {
                    console.error("Too many consecutive errors. Stopping chat notifications permanently.");
                    isPollingActive = false;
                    clearInterval(window.oneToOneChatInterval);
                }
            }
        });
    }

    // Smart polling system - alternate between one-to-one and group chat notifications
    // This prevents both systems from hitting the server simultaneously
    let isOneToOneActive = true;
    
    function smartPolling() {
        if (isOneToOneActive) {
            fetchUnreadChatMessages();
        }
        // Toggle for next poll
        isOneToOneActive = !isOneToOneActive;
    }
    
    // Check for new messages every 30 seconds (reduced frequency to prevent resource exhaustion)
    // Use the global notification manager to prevent conflicts
    window.notificationManager.startPolling('smart chat', smartPolling, 30000);

    // Also check when the tab becomes visible
    document.addEventListener("visibilitychange", function () {
        if (document.visibilityState === "visible") {
            fetchUnreadChatMessages();
        }
    });

    // Run an initial check for messages
    setTimeout(fetchUnreadChatMessages, 2000);
});
