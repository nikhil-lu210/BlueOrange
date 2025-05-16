@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Chattings'))

@section('css_links')
    {{--  External CSS  --}}
    {{-- <!-- Page CSS --> --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-chat.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/chat-custom.css') }}" />

    {{-- Lightbox CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" integrity="sha512-ZKX+BvQihRJPA8CROKBhDNvoc2aDMOdAlcm7TUQY+35XYtrd3yh95QOOhsPDQY9QnKE0Wqag9y38OIgEvb88cA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

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
    .img-thumbnail {
        padding: 3px;
        border: 3px solid var(--bs-border-color);
        border-radius: 5px;
    }
    .file-thumbnail-container {
        width: 150px;
        height: 100px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
    }
    .file-thumbnail-container .file-name {
        max-width: 140px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .chat-history-footer {
        position: relative;
    }
    .selected-file.file-thumbnail-container {
        position: absolute;
        top: -105px;
        left: 0;
    }
    </style>
@endsection



@section('content')
<!-- Start row -->
<div class="app-chat card overflow-hidden">
    <div class="row g-0">
        <!-- Chat Settings Left -->
        @include('administration.chatting.layouts.chat_settings')
        <!-- /Chat Settings Left-->

        <!-- Chat & Contacts -->
        @include('administration.chatting.layouts.chat_contacts')
        <!-- /Chat contacts -->

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

    {{-- Lightbox JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js" integrity="sha512-Ixzuzfxv1EqafeQlTCufWfaC6ful6WFqIz4G+dWvK0beHw0NVJwvCKSgafpy5gwNqKmgUfIBraVwkKI+Cz0SEQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    @livewireScripts
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    {{-- <script>
        $(document).ready(function () {
            function fetchNewMessages() {
                $.get("{{ route('administration.chatting.browser.fetch_unread') }}", function (data) {
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
                                        let chatUrl = "{{ route('administration.chatting.show', ['user' => '__USER__', 'userid' => '__USERID__']) }}";
                                        chatUrl = chatUrl.replace("__USER__", message.sender.id).replace("__USERID__", message.sender.userid);


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
        });
    </script> --}}
@endsection
