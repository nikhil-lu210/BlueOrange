<!-- Settings -->
<li class="menu-header small text-uppercase">
    <span class="menu-header-text">{{ __('Settings') }}</span>
</li>

<!-- System Settings -->
@canany (['App Setting Read', 'Weekend Read', 'Holiday Read'])
    <li class="menu-item {{ request()->is('settings/system*') ? 'active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-settings"></i>
            <div data-i18n="System Settings">{{ __('System Settings') }}</div>
        </a>
        <ul class="menu-sub">
            @canany (['App Setting Update', 'App Setting Read'])
                <li class="menu-item {{ request()->is('settings/system/app_setting*') ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <div data-i18n="App Setting">{{ __('App Settings') }}</div>
                    </a>
                    <ul class="menu-sub">
                        @can ('App Setting Update')
                            <li class="menu-item {{ request()->is('settings/system/app_setting/restrictions*') ? 'active' : '' }}">
                                <a href="{{ route('administration.settings.system.app_setting.restriction.index') }}" class="menu-link">
                                    <div data-i18n="Restrictions">{{ __('Restrictions') }}</div>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            @can ('Weekend Read')
                <li class="menu-item {{ request()->is('settings/system/weekend*') ? 'active' : '' }}">
                    <a href="{{ route('administration.settings.system.weekend.index') }}" class="menu-link">{{ __('Weekends') }}</a>
                </li>
            @endcan
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
                <li class="menu-item {{ request()->is('settings/user/barcode*') ? 'active' : '' }}">
                    <a href="{{ route('administration.settings.user.barcode.all') }}" class="menu-link">{{ __('All Barcodes') }}</a>
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
