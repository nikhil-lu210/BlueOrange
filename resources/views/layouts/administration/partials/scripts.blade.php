<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->

<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>

<script src="{{ asset('assets/js/custom_js/jquery-confirm/jquery-confirm.min.js') }}"></script>
<script src="{{ asset('assets/js/custom_js/jquery-confirm/confirm.js') }}"></script>

<script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

<!-- endbuild -->

<!-- Vendors JS -->

{{-- Desktop Browser Notification --}}
<script>
    var unreadNotificationsUrl = "{{ url('/notification/get-unread-notifications-for-browser') }}";
    var markNotificationReadUrl = "{{ url('/notification/mark-as-read-notifications-for-browser/') }}";

    var unreadOneToOneMessagesNotificationUrl = "{{ url('/chatting/one-to-one/browser-unread-messages') }}";
    var markOneToOneMessageReadUrl = "{{ url('/chatting/one-to-one/read-browser-notification-message') }}";

    var unreadGroupMessagesNotificationUrl = "{{ url('/chatting/group/browser-unread-messages') }}";
    var markGroupMessageReadUrl = "{{ url('/chatting/group/read-browser-notification-message/show') }}";
</script>
<script src="{{ asset('assets/js/custom_js/notification/browser_notification.js') }}"></script>
<script src="{{ asset('assets/js/custom_js/notification/one_to_one_chat_notification.js') }}"></script>

<!-- Main JS -->
<script src="{{ asset('assets/js/main.js') }}"></script>

{{-- Custom Js by NIKHIL --}}
<script src="{{ asset('assets/js/custom.js') }}"></script>

<!-- Page JS -->
@yield('script_links')

@yield('custom_script')
