<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="ti ti-menu-2 ti-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Light-Dark Mode -->
        <div class="navbar-nav align-items-center">
            <div class="nav-item dropdown-style-switcher dropdown me-2 me-xl-0">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <i class="ti ti-md"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-start dropdown-styles">
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
                            <span class="align-middle"><i class="ti ti-sun me-2"></i>Light</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
                            <span class="align-middle"><i class="ti ti-moon me-2"></i>Dark</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" data-theme="system">
                            <span class="align-middle"><i class="ti ti-device-desktop me-2"></i>System</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>        
        <!-- /Light-Dark Mode -->

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- Language -->
            <li class="nav-item dropdown-language dropdown me-2 me-xl-0">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <i class="ti ti-language rounded-circle ti-md"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    @foreach (config('localization.languages') as $lang) 
                        <li>
                            <a class="dropdown-item" href="{{ route('administration.localization', ['lang' => $lang['key']]) }}" data-language="{{ $lang['key'] }}">
                                <span class="align-middle">{{ $lang['value'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
            <!--/ Language -->

            <!-- Shortcut links  -->
            <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    <i class="ti ti-share-3 ti-md"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end py-0">
                    <div class="dropdown-menu-header border-bottom">
                        <div class="dropdown-header d-flex align-items-center py-3">
                            <h5 class="text-body mb-0 me-auto">{{ __('topnav.shortcuts') }}</h5>
                            <a href="{{ route('administration.shortcut.create') }}" class="dropdown-shortcuts-add text-body" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('topnav.add_shortcut') }}"><i class="ti ti-sm ti-plus"></i></a>
                        </div>
                    </div>
                    <div class="dropdown-shortcuts-list scrollable-container">
                        @foreach (auth()->user()->shortcuts->chunk(2) as $chunk)
                            <div class="row row-bordered overflow-visible g-0">
                                @foreach ($chunk as $shortcut)
                                    <div class="dropdown-shortcuts-item col">
                                        <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                                            <i class="ti ti-{{ $shortcut->icon }} fs-4"></i>
                                        </span>
                                        <a href="{{ $shortcut->url }}" class="stretched-link">{{ $shortcut->name }}</a>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </li>
            <!-- Shortcut links -->

            <!-- Notification -->
            <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    <i class="ti ti-bell ti-md"></i>
                    @if (auth()->user()->unreadNotifications->count() > 0) 
                        <span class="badge bg-danger rounded-pill badge-notifications">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </a>
                <ul class="dropdown-menu dropdown-menu-end py-0">
                    <li class="dropdown-menu-header border-bottom">
                        <div class="dropdown-header d-flex align-items-center py-3">
                            <h5 class="text-body mb-0 me-auto">{{ __('topnav.notifications') }}</h5>
                            @if (auth()->user()->unreadNotifications->count() > 0) 
                                <a href="{{ route('administration.notification.mark_all_as_read') }}" class="dropdown-notifications-all text-body confirm-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Mark all as read">
                                    <i class="ti ti-mail-opened fs-4"></i>
                                </a>
                            @endif
                        </div>
                    </li>
                    <li class="dropdown-notifications-list scrollable-container">
                        <ul class="list-group list-group-flush">
                            @forelse (auth()->user()->unreadNotifications as $notification)
                                <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar">
                                                <span class="avatar-initial rounded-circle bg-label-primary">
                                                    @if (isset($notification->data['icon'])) 
                                                        <i class="ti ti-{{ $notification->data['icon'] }} ti-md"></i>
                                                    @else 
                                                        <i class="ti ti-bell ti-md"></i>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <a href="{{ route('administration.notification.mark_as_read_and_redirect', ['notification_id' => $notification->id]) }}">
                                                <h6 class="mb-1 text-primary">{{ $notification->data['title'] }}</h6>
                                                <p class="mb-0 text-dark">{{ show_content($notification->data['message'], 60) }}</p>
                                            </a>
                                            <small class="text-muted">{{ date_time_ago($notification->created_at) }}</small>
                                        </div>
                                        <div class="flex-shrink-0 dropdown-notifications-actions">
                                            <a href="{{ route('administration.notification.destroy', ['notification_id' => $notification->id]) }}" class="text-danger confirm-danger" data-bs-toggle="tooltip" title="Delete Notification?">
                                                <span class="ti ti-x"></span>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                    {{ __('topnav.no_unread_notification') }}
                                </li>
                            @endforelse
                        </ul>
                    </li>
                    <li class="dropdown-menu-footer border-top">
                        <a href="{{ route('administration.notification.index') }}" class="dropdown-item d-flex justify-content-center text-primary p-2 h-px-40 mb-1 align-items-center">
                            {{ __('topnav.view_all_notification') }}
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ Notification -->

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        @if (auth()->user()->hasMedia('avatar'))
                            <img src="{{ auth()->user()->getFirstMediaUrl('avatar', 'profile') }}" alt="{{ auth()->user()->name }} Avatar" class="rounded-circle" style="height: 40px; width: 40px;">
                        @else
                            <span class="avatar-initial rounded-circle bg-label-hover-dark text-bold">
                                {{ profile_name_pic(auth()->user()) }}
                            </span>
                        @endif
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('administration.my.profile') }}">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online" title="{{ auth()->user()->name }}">
                                        @if (auth()->user()->hasMedia('avatar'))
                                            <img src="{{ auth()->user()->getFirstMediaUrl('avatar', 'profile') }}" alt="{{ auth()->user()->name }} Avatar" class="h-auto rounded-circle">
                                        @else
                                            <span class="avatar-initial rounded-circle bg-label-hover-dark text-bold">
                                                {{ profile_name_pic(auth()->user()) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    @if (!is_null(auth()->user()->employee->alias_name)) 
                                        <span class="fw-medium d-block">{{ auth()->user()->employee->alias_name }}</span>
                                    @else 
                                        <span class="fw-medium d-block">{{ auth()->user()->name }}</span>
                                    @endif
                                    <small class="text-muted">{{ auth()->user()->roles[0]->name }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('administration.my.profile') }}">
                            <i class="ti ti-user-check me-2 ti-sm"></i>
                            <span class="align-middle">{{ __('topnav.my_profile') }}</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('administration.my.profile.security') }}">
                            <i class="ti ti-lock-cog me-2 ti-sm"></i>
                            <span class="align-middle">{{ __('topnav.security') }}</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="ti ti-logout me-2 ti-sm"></i>
                            <span class="align-middle">{{ __('topnav.logout') }}</span>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>
