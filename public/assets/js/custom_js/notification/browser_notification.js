$(document).ready(function () {
    if (Notification.permission !== "granted") {
        Notification.requestPermission();
    }

    function fetchNotifications() {
        $.get(unreadNotificationsUrl, function (data) {
            if (data.length > 0) {
                $.each(data, function (index, notification) {
                    if (!isNotificationShown(notification.id)) {
                        showBrowserNotification(notification.data.title, notification.data.message, notification.data.url, notification.id);
                        storeNotification(notification.id); // Mark as shown
                    }
                });
            }
        });
    }

    function showBrowserNotification(title, message, url, notificationId) {
        if (Notification.permission === "granted") {
            let notif = new Notification(title, {
                body: message,
                icon: "https://cdn-icons-png.flaticon.com/512/1827/1827301.png"
            });

            notif.onclick = function () {
                window.open(url, '_blank');
                markSingleNotificationAsRead(notificationId);
            };
        }
    }

    function markSingleNotificationAsRead(notificationId) {
        $.get(markNotificationReadUrl + notificationId, function () {
            removeNotificationFromStorage(notificationId); // Remove from localStorage
        });
    }

    function isNotificationShown(notificationId) {
        let shownNotifications = JSON.parse(localStorage.getItem("shownNotifications")) || [];
        return shownNotifications.includes(notificationId);
    }

    function storeNotification(notificationId) {
        let shownNotifications = JSON.parse(localStorage.getItem("shownNotifications")) || [];
        shownNotifications.push(notificationId);
        localStorage.setItem("shownNotifications", JSON.stringify(shownNotifications));
    }

    function removeNotificationFromStorage(notificationId) {
        let shownNotifications = JSON.parse(localStorage.getItem("shownNotifications")) || [];
        let updatedNotifications = shownNotifications.filter(id => id !== notificationId);
        localStorage.setItem("shownNotifications", JSON.stringify(updatedNotifications));
    }

    setInterval(fetchNotifications, 60000);
// });





    /**
     * Chatting Notification for Browser
     */
    function fetchNewMessages() {
        $.get(unreadOneToOneMessagesNotificationUrl, function (data) {
            // console.log(unreadOneToOneMessagesNotificationUrl);

            if (data && data.length > 0) {
                let newMessageNotification = JSON.parse(localStorage.getItem("newMessageNotification")) || [];

                data.forEach(message => {

                    if (!newMessageNotification.includes(message.id)) {
                        // Check if browser notifications are allowed
                        if (Notification.permission === "granted") {
                            let notif = new Notification("New Message from " + message.sender.name, {
                                body: message.message,
                                icon: "https://cdn-icons-png.flaticon.com/512/1827/1827301.png"
                            });

                            notif.onclick = function () {
                                chatUrl = markOneToOneMessageReadUrl +'/'+ message.sender.id +'/'+ message.sender.userid;
                                window.open(chatUrl, "_blank");
                            };

                            // Mark this message as notified
                            newMessageNotification.push(message.id);
                            localStorage.setItem("newMessageNotification", JSON.stringify(newMessageNotification));
                        } else if (Notification.permission !== "denied") {
                            Notification.requestPermission();
                        }
                    }
                });
            }
        }).fail(function (err) {
            console.error("Error fetching new messages:", err);
        });
    }

    // Request notification permission when the page loads (only if not denied)
    if (Notification.permission !== "granted" && Notification.permission !== "denied") {
        Notification.requestPermission();
    }

    // Check for new messages every 30 seconds
    setInterval(fetchNewMessages, 30000);



    /**
     * Group Chatting Notification For Browser
     */
    function fetchNewGroupMessages() {
        $.get(unreadGroupMessagesNotificationUrl, function (data) {
            // console.log(unreadGroupMessagesNotificationUrl);
            if (data && data.length > 0) {
                let newGroupMessageNotifications = JSON.parse(localStorage.getItem("newGroupMessageNotifications")) || [];

                data.forEach(message => {
                    if (!newGroupMessageNotifications.includes(message.id)) {
                        if (Notification.permission === "granted") {
                            let notif = new Notification("New Group Message in " + message.group_name, {
                                body: message.sender_name + ": " + message.message,
                                icon: "https://cdn-icons-png.flaticon.com/512/1827/1827301.png"
                            });

                            notif.onclick = function () {
                                groupChatUrl = markGroupMessageReadUrl +'/'+ message.chatting_group_id;
                                window.open(groupChatUrl, "_blank");
                            };

                            // Mark this message as notified
                            newGroupMessageNotifications.push(message.id);
                            localStorage.setItem("newGroupMessageNotifications", JSON.stringify(newGroupMessageNotifications));
                        } else {
                            Notification.requestPermission();
                        }
                    }
                });
            }
        }).fail(function (err) {
            console.error("Error fetching new group messages:", err);
        });
    }

    // Request notification permission when the page loads
    if (Notification.permission !== "granted") {
        Notification.requestPermission();
    }

    // Check for new messages every 30 seconds
    setInterval(fetchNewGroupMessages, 30000);
});
