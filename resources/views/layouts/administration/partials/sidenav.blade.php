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
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>

        <li class="menu-item {{ request()->is('chatting*') ? 'active' : '' }}">
            <a href="{{ route('administration.chatting.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-message"></i>
                <div data-i18n="Chattings">Chattings</div>
                @if (get_total_unread_messages_count() > 0) 
                    <div class="badge bg-danger rounded-pill ms-auto">{{ get_total_unread_messages_count() }}</div>
                @endif
            </a>
        </li>

        <!-- Attendance Management -->
        @canany(['Attendance Create', 'Attendance Read']) 
            <li class="menu-item {{ request()->is('attendance*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-clock-2"></i>
                    <div data-i18n="Attendance">Attendance</div>
                </a>
                <ul class="menu-sub">
                    @canany(['Attendance Update', 'Attendance Delete'])
                        <li class="menu-item {{ request()->is('attendance/all*') ? 'active' : '' }}">
                            <a href="{{ route('administration.attendance.index') }}" class="menu-link">All Attendances</a>
                        </li>
                    @endcanany
                    @can('Attendance Read') 
                        <li class="menu-item {{ request()->is('attendance/my*') ? 'active' : '' }}">
                            <a href="{{ route('administration.attendance.my') }}" class="menu-link">My Attendances</a>
                        </li>
                    @endcan
                    @can('Attendance Create')
                        <li class="menu-item {{ request()->is('attendance/create*') ? 'active' : '' }}">
                            <a href="{{ route('administration.attendance.create') }}" class="menu-link">Assign Attendance</a>
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
                    <div data-i18n="Announcement">Announcement</div>
                </a>
                <ul class="menu-sub">
                    @canany(['Announcement Create', 'Announcement Update', 'Announcement Delete'])
                        <li class="menu-item {{ request()->is('announcement/all*') ? 'active' : '' }}">
                            <a href="{{ route('administration.announcement.index') }}" class="menu-link">All Announcements</a>
                        </li>
                    @endcanany
                    @can('Announcement Read') 
                        <li class="menu-item {{ request()->is('announcement/my*') ? 'active' : '' }}">
                            <a href="{{ route('administration.announcement.my') }}" class="menu-link">My Announcements</a>
                        </li>
                    @endcan
                    @can('Announcement Create')
                        <li class="menu-item {{ request()->is('announcement/create*') ? 'active' : '' }}">
                            <a href="{{ route('administration.announcement.create') }}" class="menu-link">New Announcement</a>
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
                    <div data-i18n="Task">Task</div>
                </a>
                <ul class="menu-sub">
                    @canany(['Task Create', 'Task Update', 'Task Delete'])
                        <li class="menu-item {{ request()->is('task/all*') ? 'active' : '' }}">
                            <a href="{{ route('administration.task.index') }}" class="menu-link">All Tasks</a>
                        </li>
                    @endcanany
                    @can('Task Read') 
                        <li class="menu-item {{ request()->is('task/my*') ? 'active' : '' }}">
                            <a href="{{ route('administration.task.my') }}" class="menu-link">My Tasks</a>
                        </li>
                    @endcan
                    @can('Task Create')
                        <li class="menu-item {{ request()->is('task/create*') ? 'active' : '' }}">
                            <a href="{{ route('administration.task.create') }}" class="menu-link">New Task</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        <!-- Settings -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Settings</span>
        </li>

        <!-- System Settings -->
        @canany (['Holiday Read'])
            <li class="menu-item {{ request()->is('settings/system*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-settings"></i>
                    <div data-i18n="System Settings">System Settings</div>
                </a>
                <ul class="menu-sub">
                    @can ('Holiday Read') 
                        <li class="menu-item {{ request()->is('settings/system/holiday*') ? 'active' : '' }}">
                            <a href="{{ route('administration.settings.system.holiday.index') }}" class="menu-link">Holidays</a>
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
                    <div data-i18n="User Management">User Management</div>
                </a>
                <ul class="menu-sub">
                    @can ('User Read') 
                        <li class="menu-item {{ request()->is('settings/user/all*') ? 'active' : '' }}">
                            <a href="{{ route('administration.settings.user.index') }}" class="menu-link">All Users</a>
                        </li>
                    @endcan
                    @can ('User Create')
                        <li class="menu-item {{ request()->is('settings/user/create*') ? 'active' : '' }}">
                            <a href="{{ route('administration.settings.user.create') }}" class="menu-link">Create New User</a>
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
                    <div data-i18n="Role & Permission">Role & Permission</div>
                </a>
                <ul class="menu-sub">
                    @canany (['Role Create', 'Role Read'])
                        <li class="menu-item {{ request()->is('settings/rolepermission/role*') ? 'active open' : '' }}">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <div data-i18n="Role">Role</div>
                            </a>
                            <ul class="menu-sub">
                                @can ('Role Read') 
                                    <li class="menu-item {{ request()->is('settings/rolepermission/role/all*') ? 'active' : '' }}">
                                        <a href="{{ route('administration.settings.rolepermission.role.index') }}" class="menu-link">
                                            <div data-i18n="All Roles">All Roles</div>
                                        </a>
                                    </li>
                                @endcan
                                @can ('Role Create') 
                                    <li class="menu-item {{ request()->is('settings/rolepermission/role/create') ? 'active' : '' }}">
                                        <a href="{{ route('administration.settings.rolepermission.role.create') }}" class="menu-link">
                                            <div data-i18n="Create Role">Create Role</div>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                    
                    @canany (['Permission Create', 'Permission Read'])
                        <li class="menu-item {{ request()->is('settings/rolepermission/permission*') ? 'active open' : '' }}">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <div data-i18n="Permissions">Permissions</div>
                            </a>
                            <ul class="menu-sub">
                                @can ('Permission Read') 
                                    <li class="menu-item {{ request()->is('settings/rolepermission/permission/all*') ? 'active' : '' }}">
                                        <a href="{{ route('administration.settings.rolepermission.permission.index') }}" class="menu-link">
                                            <div data-i18n="All Permission">All Permission</div>
                                        </a>
                                    </li>
                                @endcan
                                @can ('Permission Create') 
                                    <li class="menu-item {{ request()->is('settings/rolepermission/permission/create*') ? 'active' : '' }}">
                                        <a href="{{ route('administration.settings.rolepermission.permission.create') }}" class="menu-link">
                                            <div data-i18n="Create Permission">Create Permission</div>
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
        <li class="menu-item {{ request()->is('shortcuts*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-share-3"></i>
                <div data-i18n="Shortcuts">Shortcuts</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('shortcut') ? 'active' : '' }}">
                    <a href="#" class="menu-link">My Shortcuts</a>
                </li>
                <li class="menu-item {{ request()->is('shortcuts/create*') ? 'active' : '' }}">
                    <a href="#" class="menu-link">Add Shortcut</a>
                </li>
            </ul>
        </li>
    </ul>
</aside>
