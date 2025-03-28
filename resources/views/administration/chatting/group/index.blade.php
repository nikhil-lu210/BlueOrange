@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Chatting Groups'))

@section('css_links')
    {{--  External CSS  --}}
    {{-- <!-- Page CSS --> --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-chat.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />

    @livewireStyles
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    nav.breadcrumb {
        display: none !important;
    }
    .app-chat .app-chat-contacts {
        height: calc(100vh - 8rem);
    }
    .app-chat .app-chat-contacts .sidebar-body {
        height: calc(calc(100vh - 8rem) - 5rem);
    }
    .app-chat .app-chat-sidebar-left {
        height: calc(100vh - 8rem);
    }
    .app-chat .app-chat-sidebar-right {
        height: calc(100vh - 8rem);
    }
    .app-chat .app-chat-sidebar-right .sidebar-body {
        height: calc(calc(100vh - 11.5rem) - 8.75rem);
    }
    .app-chat .app-chat-history {
        height: calc(100vh - 8rem);
    }
    .app-chat .app-chat-history .chat-history-body {
        height: calc(100vh - 16rem);
        /* overflow-y: scroll; */
        overflow: auto;
        display: flex;
        flex-direction: column-reverse;
    }
    </style>
@endsection



@section('content')
<!-- Start row -->
<div class="app-chat card overflow-hidden">
    <div class="row g-0">
        <!-- Chat Settings Left -->
        @include('administration.chatting.group.layouts.create_chat_group')
        <!-- /Chat Settings Left-->

        <!-- Chat Groups -->
        @include('administration.chatting.group.layouts.chat_groups')
        <!-- Chat Groups -->

        <!-- Chat Body (History) -->
        @if ($hasChat == false)
            <div class="col app-chat-history bg-body">
                <div class="chat-history-wrapper">
                    <div class="chat-history-header border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex overflow-hidden align-items-center">
                                <i class="ti ti-menu-2 ti-sm cursor-pointer d-lg-none d-block me-2" data-bs-toggle="sidebar" data-overlay data-target="#app-chat-contacts"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            @yield('chat_body')
        @endif
        <!-- /Chat Body (History) -->

        <div class="app-overlay"></div>
    </div>
</div>
<!-- End row -->
@endsection



@section('script_links')
    {{--  External Javascript Links --}}
    {{-- Vendors JS --}}
    <script src="{{ asset('assets/vendor/libs/bootstrap-maxlength/bootstrap-maxlength.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/app-chat.js') }}"></script> --}}
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>

    @livewireScripts
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    {{-- <script>
        $(document).ready(function () {
            function fetchNewGroupMessages() {
                $.get("{{ route('administration.chatting.group.browser.fetch_unread') }}", function (data) {
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
                                        let groupChatUrl = "{{ route('administration.chatting.group.show', ['group' => '__GROUP__', 'groupid' => '__GROUPID__']) }}";
                                        groupChatUrl = groupChatUrl.replace("__GROUP__", message.group.id).replace("__GROUPID__", message.group.groupid);

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
    </script> --}}
@endsection
