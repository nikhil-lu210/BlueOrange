<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('administration.dashboard.index') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('Logo/logo_black_01.png') }}" width="90%">
            </span>
            <span class="app-brand-text demo menu-text fw-bold">{{ config('app.name') }}</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-item {{ request()->is('dashboard*') ? 'active' : '' }}">
            <a href="{{ route('administration.dashboard.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div data-i18n="Dashboard">{{ __('Dashboard') }}</div>
            </a>
        </li>

        <li class="menu-item {{ request()->is('chatting/private*') ? 'active' : '' }}">
            <a href="{{ route('administration.chatting.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-message"></i>
                <div data-i18n="Chattings">{{ __('Chattings') }}</div>
                @if (get_total_unread_private_messages_count() > 0) 
                    <div class="badge bg-danger rounded-pill ms-auto">{{ get_total_unread_private_messages_count() }}</div>
                @endif
            </a>
        </li>

        <li class="menu-item {{ request()->is('chatting/group*') ? 'active' : '' }}">
            <a href="{{ route('administration.chatting.group.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-messages"></i>
                <div data-i18n="Group Chattings">{{ __('Group Chattings') }}</div>
            </a>
        </li>

        <!-- Attendance Management -->
        @canany(['Attendance Create', 'Attendance Read']) 
            <li class="menu-item {{ request()->is('attendance*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-clock-2"></i>
                    <div data-i18n="Attendance">{{ __('Attendance') }}</div>
                </a>
                <ul class="menu-sub">
                    @canany(['Attendance Update', 'Attendance Delete'])
                        <li class="menu-item {{ request()->is('attendance/all*') ? 'active' : '' }}">
                            <a href="{{ route('administration.attendance.index') }}" class="menu-link">{{ __('All Attendances') }}</a>
                        </li>
                    @endcanany
                    @can('Attendance Read') 
                        <li class="menu-item {{ request()->is('attendance/my*') ? 'active' : '' }}">
                            <a href="{{ route('administration.attendance.my') }}" class="menu-link">{{ __('My Attendances') }}</a>
                        </li>
                    @endcan
                    @can('Attendance Create')
                        <li class="menu-item {{ request()->is('attendance/create*') ? 'active' : '' }}">
                            <a href="{{ route('administration.attendance.create') }}" class="menu-link">{{ __('Assign Attendance') }}</a>
                        </li>
                    @endcan
                    @can('Attendance Create')
                        <li class="menu-item {{ request()->is('attendance/qrcode*') ? 'active' : '' }}">
                            <a href="{{ route('administration.attendance.qrcode.scanner') }}" class="menu-link">{{ __('QR Code Attendance') }}</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        <!-- Announcement Management -->
        @canany(['Announcement Create', 'Announcement Read']) 
            <li class="menu-item {{ request()->is('announcement*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-speakerphone"></i>
                    <div data-i18n="Announcement">{{ __('Announcement') }}</div>
                </a>
                <ul class="menu-sub">
                    @canany(['Announcement Create', 'Announcement Update', 'Announcement Delete'])
                        <li class="menu-item {{ request()->is('announcement/all*') ? 'active' : '' }}">
                            <a href="{{ route('administration.announcement.index') }}" class="menu-link">{{ __('All Announcements') }}</a>
                        </li>
                    @endcanany
                    @can('Announcement Read') 
                        <li class="menu-item {{ request()->is('announcement/my*') ? 'active' : '' }}">
                            <a href="{{ route('administration.announcement.my') }}" class="menu-link">{{ __('My Announcements') }}</a>
                        </li>
                    @endcan
                    @can('Announcement Create')
                        <li class="menu-item {{ request()->is('announcement/create*') ? 'active' : '' }}">
                            <a href="{{ route('administration.announcement.create') }}" class="menu-link">{{ __('New Announcement') }}</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        <!-- Task Management -->
        @canany(['Task Create', 'Task Read']) 
            <li class="menu-item {{ request()->is('task*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-brand-stackshare"></i>
                    <div data-i18n="Task">{{ __('Task') }}</div>
                </a>
                <ul class="menu-sub">
                    @canany(['Task Create', 'Task Update', 'Task Delete'])
                        <li class="menu-item {{ request()->is('task/all*') ? 'active' : '' }}">
                            <a href="{{ route('administration.task.index') }}" class="menu-link">{{ __('All Tasks') }}</a>
                        </li>
                    @endcanany
                    @can('Task Read') 
                        <li class="menu-item {{ request()->is('task/my*') ? 'active' : '' }}">
                            <a href="{{ route('administration.task.my') }}" class="menu-link">{{ __('My Tasks') }}</a>
                        </li>
                    @endcan
                    @can('Task Create')
                        <li class="menu-item {{ request()->is('task/create*') ? 'active' : '' }}">
                            <a href="{{ route('administration.task.create') }}" class="menu-link">{{ __('New Task') }}</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        <!-- Daily Work Update -->
        @canany(['Daily Work Update Create', 'Daily Work Update Read']) 
            <li class="menu-item {{ request()->is('daily_work_update*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-device-imac-check"></i>
                    <div data-i18n="Daily Work Update">{{ __('Daily Work Update') }}</div>
                </a>
                <ul class="menu-sub">
                    @if(auth()->user()->hasAllPermissions(['Daily Work Update Create', 'Daily Work Update Update', 'Daily Work Update Delete']))
                        <li class="menu-item {{ request()->is('daily_work_update/all*') ? 'active' : '' }}">
                            <a href="{{ route('administration.daily_work_update.index') }}" class="menu-link">{{ __('All Work Updates') }}</a>
                        </li>
                    @endif
                    @can('Daily Work Update Read') 
                        <li class="menu-item {{ request()->is('daily_work_update/my*') ? 'active' : '' }}">
                            <a href="{{ route('administration.daily_work_update.my') }}" class="menu-link">{{ __('My Work Updates') }}</a>
                        </li>
                    @endcan
                    @can('Daily Work Update Create')
                        <li class="menu-item {{ request()->is('daily_work_update/create*') ? 'active' : '' }}">
                            <a href="{{ route('administration.daily_work_update.create') }}" class="menu-link">{{ __('New Work Update') }}</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        <!-- Settings -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{ __('Settings') }}</span>
        </li>

        <!-- System Settings -->
        @canany (['Holiday Read'])
            <li class="menu-item {{ request()->is('settings/system*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-settings"></i>
                    <div data-i18n="System Settings">{{ __('System Settings') }}</div>
                </a>
                <ul class="menu-sub">
                    @can ('Holiday Read') 
                        <li class="menu-item {{ request()->is('settings/system/holiday*') ? 'active' : '' }}">
                            <a href="{{ route('administration.settings.system.holiday.index') }}" class="menu-link">{{ __('Holidays') }}</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        <!-- User Management -->
        @canany (['User Create', 'User Read']) 
            <li class="menu-item {{ request()->is('settings/user*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-user-shield"></i>
                    <div data-i18n="User Management">{{ __('User Management') }}</div>
                </a>
                <ul class="menu-sub">
                    @can ('User Read') 
                        <li class="menu-item {{ request()->is('settings/user/all*') ? 'active' : '' }}">
                            <a href="{{ route('administration.settings.user.index') }}" class="menu-link">{{ __('All Users') }}</a>
                        </li>
                    @endcan
                    @can ('User Create')
                        <li class="menu-item {{ request()->is('settings/user/create*') ? 'active' : '' }}">
                            <a href="{{ route('administration.settings.user.create') }}" class="menu-link">{{ __('Create New User') }}</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany
        
        <!-- Role & Permission -->
        @canany (['Permission Create', 'Permission Read', 'Role Create', 'Role Read'])
            <li class="menu-item {{ request()->is('settings/rolepermission*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-lock"></i>
                    <div data-i18n="Role & Permission">{{ __('Role & Permission') }}</div>
                </a>
                <ul class="menu-sub">
                    @canany (['Role Create', 'Role Read'])
                        <li class="menu-item {{ request()->is('settings/rolepermission/role*') ? 'active open' : '' }}">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <div data-i18n="Role">{{ __('Roles') }}</div>
                            </a>
                            <ul class="menu-sub">
                                @can ('Role Read') 
                                    <li class="menu-item {{ request()->is('settings/rolepermission/role/all*') ? 'active' : '' }}">
                                        <a href="{{ route('administration.settings.rolepermission.role.index') }}" class="menu-link">
                                            <div data-i18n="All Roles">{{ __('All Roles') }}</div>
                                        </a>
                                    </li>
                                @endcan
                                @can ('Role Create') 
                                    <li class="menu-item {{ request()->is('settings/rolepermission/role/create') ? 'active' : '' }}">
                                        <a href="{{ route('administration.settings.rolepermission.role.create') }}" class="menu-link">
                                            <div data-i18n="Create Role">{{ __('Create Role') }}</div>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                    
                    @canany (['Permission Create', 'Permission Read'])
                        <li class="menu-item {{ request()->is('settings/rolepermission/permission*') ? 'active open' : '' }}">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <div data-i18n="Permissions">{{ __('Permissions') }}</div>
                            </a>
                            <ul class="menu-sub">
                                @can ('Permission Read') 
                                    <li class="menu-item {{ request()->is('settings/rolepermission/permission/all*') ? 'active' : '' }}">
                                        <a href="{{ route('administration.settings.rolepermission.permission.index') }}" class="menu-link">
                                            <div data-i18n="All Permission">{{ __('All Permissions') }}</div>
                                        </a>
                                    </li>
                                @endcan
                                @can ('Permission Create') 
                                    <li class="menu-item {{ request()->is('settings/rolepermission/permission/create*') ? 'active' : '' }}">
                                        <a href="{{ route('administration.settings.rolepermission.permission.create') }}" class="menu-link">
                                            <div data-i18n="Create Permission">{{ __('Create Permission') }}</div>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                </ul>
            </li>
        @endcanany
        
        <!-- Shortcuts -->
        <li class="menu-item {{ request()->is('shortcut*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-share-3"></i>
                <div data-i18n="Shortcuts">{{ __('Shortcuts') }}</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('shortcut/all*') ? 'active' : '' }}">
                    <a href="{{ route('administration.shortcut.index') }}" class="menu-link">{{ __('My Shortcuts') }}</a>
                </li>
                <li class="menu-item {{ request()->is('shortcut/create*') ? 'active' : '' }}">
                    <a href="{{ route('administration.shortcut.create') }}" class="menu-link">{{ __('Add Shortcut') }}</a>
                </li>
            </ul>
        </li>
        

        <!-- Logs -->
        @canany (['Logs Read'])
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">{{ __('Logs') }}</span>
            </li>
        
            <li class="menu-item {{ request()->is('logs*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-history"></i>
                    <div data-i18n="Logs">{{ __('Logs') }}</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->is('logs/login_logout_history*') ? 'active' : '' }}">
                        <a href="{{ route('administration.logs.login_logout_history.index') }}" class="menu-link">{{ __('Login Histories') }}</a>
                    </li>
                </ul>
            </li>
        @endcanany
    </ul>
</aside>
