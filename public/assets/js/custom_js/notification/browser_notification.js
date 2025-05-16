/**
 * Browser Notifications
 * Handles general system notifications
 */

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



    // Chat notifications are now handled in separate files

    // Check for new notifications every 5 minutes
    setInterval(fetchNotifications, 300000);

    // Check when tab becomes visible
    document.addEventListener("visibilitychange", function () {
        if (document.visibilityState === "visible") {
            fetchNotifications();
        }
    });
});
