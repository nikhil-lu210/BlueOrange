/**
 * One-to-One Chat Browser Notifications
 * This file handles browser notifications specifically for one-to-one chat messages
 */

$(document).ready(function () {
    console.log("One-to-one chat notification script loaded");

    // Initialize notification audio
    const notificationSound = new Audio('/assets/audio/message_notification.mp3');
    notificationSound.volume = 1; // Set volume to 100%

    // Test audio on page load (uncomment for testing)
    // setTimeout(() => {
    //     notificationSound.play().catch(error => {
    //         console.error("Error playing test notification sound:", error);
    //     });
    // }, 3000);

    // Check if we're on a specific chat page
    function isOnChatPage() {
        const isOnChat = window.location.pathname.includes('/chatting/one-to-one/') &&
                        window.location.pathname.split('/').length > 3;
        console.log("Is on specific chat page:", isOnChat, window.location.pathname);
        return isOnChat;
    }

    // Check if we're on the chat list page
    function isOnChatListPage() {
        const isOnChatList = window.location.pathname === '/chatting/one-to-one/';
        console.log("Is on chat list page:", isOnChatList);
        return isOnChatList;
    }

    // Request notification permission
    function requestNotificationPermission() {
        console.log("Current notification permission:", Notification.permission);

        if (Notification.permission !== "granted") {
            Notification.requestPermission().then(permission => {
                console.log("Notification permission response:", permission);

                // Show a test notification if permission was just granted
                if (permission === "granted") {
                    const testNotif = new Notification("Notifications Enabled", {
                        body: "You will now receive notifications for new chat messages",
                        icon: "https://cdn-icons-png.flaticon.com/512/1827/1827301.png"
                    });

                    // Play test sound
                    notificationSound.play().catch(error => {
                        console.error("Error playing test notification sound:", error);
                    });
                }
            });
        }
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
        console.log("Checking for unread chat messages...");

        // Get current chat user ID if we're on a chat page
        let currentChatUserId = null;
        if (isOnChatPage()) {
            // Extract user ID from URL path
            const pathParts = window.location.pathname.split('/');
            if (pathParts.length >= 4) {
                currentChatUserId = pathParts[pathParts.length - 2]; // User ID is the second-to-last part
                console.log("Current chat user ID:", currentChatUserId);
            }
        }

        // Prepare URL with query parameter if needed
        let fetchUrl = unreadOneToOneMessagesNotificationUrl;
        let params = [];

        if (currentChatUserId) {
            params.push('current_chat_user_id=' + currentChatUserId);
        }

        // Add bypass_cache parameter to force fresh data
        params.push('bypass_cache=true');

        if (params.length > 0) {
            fetchUrl += '?' + params.join('&');
        }

        console.log("Fetching unread messages from:", fetchUrl);

        $.get(fetchUrl, function (response) {
            console.log("Full API response:", response);

            // Extract messages from the new response format
            const data = response.messages || [];
            const debug = response.debug || {};

            console.log("Unread messages:", data);
            console.log("Debug info:", debug);

            if (data && data.length > 0) {
                console.log(`Found ${data.length} unread messages out of ${debug.total_unread_count} total unread`);

                let newMessageNotification = JSON.parse(localStorage.getItem("newMessageNotification")) || [];
                console.log("Previously notified messages:", newMessageNotification);

                let hasNewMessages = false;

                data.forEach(message => {
                    console.log("Processing message:", message.id, "from", message.sender.name);

                    if (!newMessageNotification.includes(message.id)) {
                        console.log("New message detected, showing notification");
                        hasNewMessages = true;

                        // Check if browser notifications are allowed
                        if (Notification.permission === "granted") {
                            console.log("Creating notification for message:", message.id);

                            try {
                                let notif = new Notification("New Message from " + message.sender.name, {
                                    body: message.message,
                                    icon: "https://cdn-icons-png.flaticon.com/512/1827/1827301.png"
                                });

                                notif.onclick = function () {
                                    // Get the base URL for the chat page (without using the notification redirect)
                                    // This ensures we go directly to the chat page
                                    const baseUrl = window.location.origin;
                                    const chatUrl = baseUrl + '/chatting/one-to-one/' + message.sender.id + '/' + message.sender.userid;

                                    console.log("Notification clicked, opening direct chat URL:", chatUrl);

                                    // Log the sender details for debugging
                                    console.log("Sender details:", {
                                        id: message.sender.id,
                                        userid: message.sender.userid,
                                        name: message.sender.name
                                    });

                                    // Open in a new tab
                                    window.open(chatUrl, "_blank");

                                    // Also mark the message as read via AJAX (without redirecting)
                                    const readUrl = markOneToOneMessageReadUrl + '/' + message.sender.id + '/' + message.sender.userid;
                                    $.get(readUrl).done(function(response) {
                                        console.log("Message marked as read via AJAX");
                                    }).fail(function(error) {
                                        console.error("Error marking message as read:", error);
                                    });
                                };

                                // Mark this message as notified
                                newMessageNotification.push(message.id);
                                localStorage.setItem("newMessageNotification", JSON.stringify(newMessageNotification));
                                console.log("Updated notified messages list:", newMessageNotification);
                            } catch (error) {
                                console.error("Error creating notification:", error);
                            }
                        } else {
                            console.warn("Notification permission not granted:", Notification.permission);
                            if (Notification.permission !== "denied") {
                                requestNotificationPermission();
                            }
                        }
                    } else {
                        console.log("Message already notified, skipping");
                    }
                });

                // Play sound if there are new messages
                if (hasNewMessages) {
                    console.log("Playing notification sound for new messages");
                    notificationSound.play().catch(error => {
                        console.error("Error playing notification sound:", error);
                    });
                }
            } else {
                console.log("No unread messages found");
            }
        }).fail(function (err) {
            console.error("Error fetching new messages:", err);
        });
    }

    // Add test buttons (for debugging)
    if (!document.getElementById('test-chat-notification-btn')) {
        const navbarNav = document.querySelector('.navbar-nav');
        if (navbarNav) {
            const testBtnContainer = document.createElement('li');
            testBtnContainer.className = 'nav-item d-none d-lg-block';
            testBtnContainer.innerHTML = `
                <div class="d-flex">
                    <button id="test-chat-notification-btn" class="btn btn-sm btn-info mx-1">
                        <i class="ti ti-bell-ringing me-1"></i> Test Notification
                    </button>
                    <button id="send-test-message-btn" class="btn btn-sm btn-warning mx-1">
                        <i class="ti ti-message me-1"></i> Send Test Message
                    </button>
                    <button id="clear-notification-cache-btn" class="btn btn-sm btn-danger mx-1">
                        <i class="ti ti-trash me-1"></i> Clear Cache
                    </button>
                </div>
            `;
            navbarNav.appendChild(testBtnContainer);

            // Add click event for test notification
            document.getElementById('test-chat-notification-btn').addEventListener('click', function() {
                console.log("Testing chat notification...");

                if (Notification.permission === "granted") {
                    // Send a test message first to ensure we have a sender
                    $.ajax({
                        url: '/chatting/one-to-one/send-test-message',
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            console.log("Test message sent for notification:", response);

                            if (response.success && response.data && response.data.sender_id) {
                                // Get sender info
                                const senderId = response.data.sender_id;
                                const senderName = response.data.sender ? response.data.sender.name : "Test User";
                                const senderUserid = response.data.sender ? response.data.sender.userid : "TESTUSER";

                                // Create a test notification with the actual sender
                                const notification = new Notification("Test Message from " + senderName, {
                                    body: response.data.message || "This is a test chat notification",
                                    icon: "https://cdn-icons-png.flaticon.com/512/1827/1827301.png"
                                });

                                // Set up click handler
                                notification.onclick = function() {
                                    const baseUrl = window.location.origin;
                                    const chatUrl = baseUrl + '/chatting/one-to-one/' + senderId + '/' + senderUserid;
                                    console.log("Test notification clicked, opening URL:", chatUrl);
                                    window.open(chatUrl, "_blank");
                                };

                                // Play test sound
                                notificationSound.play().catch(error => {
                                    console.error("Error playing test notification sound:", error);
                                });
                            } else {
                                // Fallback to generic notification
                                const notification = new Notification("Test Notification", {
                                    body: "This is a test chat notification",
                                    icon: "https://cdn-icons-png.flaticon.com/512/1827/1827301.png"
                                });

                                // Play test sound
                                notificationSound.play().catch(error => {
                                    console.error("Error playing test notification sound:", error);
                                });
                            }
                        },
                        error: function(error) {
                            console.error("Error sending test message for notification:", error);

                            // Fallback to generic notification
                            const notification = new Notification("Test Notification", {
                                body: "This is a test chat notification",
                                icon: "https://cdn-icons-png.flaticon.com/512/1827/1827301.png"
                            });

                            // Play test sound
                            notificationSound.play().catch(error => {
                                console.error("Error playing test notification sound:", error);
                            });
                        }
                    });
                } else {
                    alert("Notification permission not granted. Current status: " + Notification.permission);
                    requestNotificationPermission();
                }
            });

            // Add click event for sending test message
            document.getElementById('send-test-message-btn').addEventListener('click', function() {
                console.log("Sending test message...");

                // Send a test message via AJAX
                $.ajax({
                    url: '/chatting/one-to-one/send-test-message',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log("Test message sent:", response);
                        alert("Test message sent successfully! Check for notifications.");

                        // Force check for new messages
                        setTimeout(fetchUnreadChatMessages, 2000);
                    },
                    error: function(error) {
                        console.error("Error sending test message:", error);
                        alert("Error sending test message. Check console for details.");
                    }
                });
            });

            // Add click event for clearing notification cache
            document.getElementById('clear-notification-cache-btn').addEventListener('click', function() {
                console.log("Clearing notification cache...");

                // Clear localStorage
                localStorage.removeItem("newMessageNotification");

                // Clear server cache
                $.get(unreadOneToOneMessagesNotificationUrl + '?bypass_cache=true', function(response) {
                    console.log("Cache cleared:", response);
                    alert("Notification cache cleared!");

                    // Force check for new messages
                    setTimeout(fetchUnreadChatMessages, 1000);
                });
            });
        }
    }

    // Check for new messages every 15 seconds
    const intervalId = setInterval(fetchUnreadChatMessages, 15000);
    console.log("Set up message check interval:", intervalId);

    // Also check when the tab becomes visible
    document.addEventListener("visibilitychange", function () {
        if (document.visibilityState === "visible") {
            console.log("Tab became visible, checking for messages");
            fetchUnreadChatMessages();
        }
    });

    // Run an initial check for messages
    console.log("Running initial check for unread messages");
    setTimeout(fetchUnreadChatMessages, 2000);
});
