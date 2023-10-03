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

        <!-- Settings -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Settings</span>
        </li>

        <!-- User Management -->
        <li class="menu-item {{ request()->is('users*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-user-shield"></i>
                <div data-i18n="User Management">User Management</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('users/all*') ? 'active' : '' }}">
                    <a href="#" class="menu-link">All Users</a>
                </li>
                <li class="menu-item {{ request()->is('users/create*') ? 'active' : '' }}">
                    <a href="#" class="menu-link">Add New User</a>
                </li>
            </ul>
        </li>
        
        <!-- Role & Permission -->
        <li class="menu-item {{ request()->is('settings/rolepermission*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-lock"></i>
                <div data-i18n="Role & Permission">Role & Permission</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('settings/rolepermission/role*') ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <div data-i18n="Role">Role</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item {{ request()->is('settings/rolepermission/role/all*') ? 'active' : '' }}">
                            <a href="{{ route('administration.settings.rolepermission.role.index') }}" class="menu-link">
                                <div data-i18n="All Roles">All Roles</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->is('settings/rolepermission/role/create') ? 'active' : '' }}">
                            <a href="{{ route('administration.settings.rolepermission.role.create') }}" class="menu-link">
                                <div data-i18n="Create Role">Create Role</div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-item {{ request()->is('settings/rolepermission/permission*') ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <div data-i18n="Permissions">Permissions</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item {{ request()->is('settings/rolepermission/permission/all*') ? 'active' : '' }}">
                            <a href="{{ route('administration.settings.rolepermission.permission.index') }}" class="menu-link">
                                <div data-i18n="All Permission">All Permission</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->is('settings/rolepermission/permission/create*') ? 'active' : '' }}">
                            <a href="{{ route('administration.settings.rolepermission.permission.create') }}" class="menu-link">
                                <div data-i18n="Create Permission">Create Permission</div>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
        
        <!-- Shortcuts -->
        <li class="menu-item {{ request()->is('shortcuts*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-layout-grid-add"></i>
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
