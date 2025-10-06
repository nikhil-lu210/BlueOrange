<!-- System Settings -->
@canany (['App Setting Read', 'Weekend Read', 'Holiday Read'])
    <li class="menu-item {{ request()->is('settings/system*') ? 'active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-settings"></i>
            <div data-i18n="System Settings">{{ ___('System Settings') }}</div>
        </a>
        <ul class="menu-sub">
            @canany (['App Setting Update', 'App Setting Read'])
                <li class="menu-item {{ request()->is('settings/system/app_setting*') ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <div data-i18n="App Setting">{{ ___('App Settings') }}</div>
                    </a>
                    <ul class="menu-sub">
                        @can ('App Setting Update')
                            <li class="menu-item {{ request()->is('settings/system/app_setting/restrictions*') ? 'active' : '' }}">
                                <a href="{{ route('administration.settings.system.app_setting.restriction.index') }}" class="menu-link">
                                    <div data-i18n="Restrictions">{{ ___('Restrictions') }}</div>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            @can ('Weekend Read')
                <li class="menu-item {{ request()->is('settings/system/weekend*') ? 'active' : '' }}">
                    <a href="{{ route('administration.settings.system.weekend.index') }}" class="menu-link">{{ ___('Weekends') }}</a>
                </li>
            @endcan
            @can ('Holiday Read')
                <li class="menu-item {{ request()->is('settings/system/holiday*') ? 'active' : '' }}">
                    <a href="{{ route('administration.settings.system.holiday.index') }}" class="menu-link">{{ ___('Holidays') }}</a>
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
            <div data-i18n="User Management">{{ ___('User Management') }}</div>
        </a>
        <ul class="menu-sub">
            @can ('User Read')
                <li class="menu-item {{ request()->is('settings/user/all*') ? 'active' : '' }}">
                    <a href="{{ route('administration.settings.user.index') }}" class="menu-link">{{ ___('All Users') }}</a>
                </li>
            @endcan
            @can ('User Create')
                <li class="menu-item {{ request()->is('settings/user/barcode*') ? 'active' : '' }}">
                    <a href="{{ route('administration.settings.user.barcode.all') }}" class="menu-link">{{ ___('All Barcodes') }}</a>
                </li>
            @endcan
            @can ('User Create')
                <li class="menu-item {{ request()->is('settings/user/create*') ? 'active' : '' }}">
                    <a href="{{ route('administration.settings.user.create') }}" class="menu-link">{{ ___('Create New User') }}</a>
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
            <div data-i18n="Role & Permission">{{ ___('Role & Permission') }}</div>
        </a>
        <ul class="menu-sub">
            @canany (['Role Create', 'Role Read'])
                <li class="menu-item {{ request()->is('settings/rolepermission/role*') ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <div data-i18n="Role">{{ ___('Roles') }}</div>
                    </a>
                    <ul class="menu-sub">
                        @can ('Role Read')
                            <li class="menu-item {{ request()->is('settings/rolepermission/role/all*') ? 'active' : '' }}">
                                <a href="{{ route('administration.settings.rolepermission.role.index') }}" class="menu-link">
                                    <div data-i18n="All Roles">{{ ___('All Roles') }}</div>
                                </a>
                            </li>
                        @endcan
                        @can ('Role Create')
                            <li class="menu-item {{ request()->is('settings/rolepermission/role/create') ? 'active' : '' }}">
                                <a href="{{ route('administration.settings.rolepermission.role.create') }}" class="menu-link">
                                    <div data-i18n="Create Role">{{ ___('Create Role') }}</div>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            @canany (['Permission Create', 'Permission Read'])
                <li class="menu-item {{ request()->is('settings/rolepermission/permission*') ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <div data-i18n="Permissions">{{ ___('Permissions') }}</div>
                    </a>
                    <ul class="menu-sub">
                        @can ('Permission Read')
                            <li class="menu-item {{ request()->is('settings/rolepermission/permission/all*') ? 'active' : '' }}">
                                <a href="{{ route('administration.settings.rolepermission.permission.index') }}" class="menu-link">
                                    <div data-i18n="All Permission">{{ ___('All Permissions') }}</div>
                                </a>
                            </li>
                        @endcan
                        @can ('Permission Create')
                            <li class="menu-item {{ request()->is('settings/rolepermission/permission/create*') ? 'active' : '' }}">
                                <a href="{{ route('administration.settings.rolepermission.permission.create') }}" class="menu-link">
                                    <div data-i18n="Create Permission">{{ ___('Create Permission') }}</div>
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
        <div data-i18n="Shortcuts">{{ ___('Shortcuts') }}</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->is('shortcut/all*') ? 'active' : '' }}">
            <a href="{{ route('administration.shortcut.index') }}" class="menu-link">{{ ___('My Shortcuts') }}</a>
        </li>
        <li class="menu-item {{ request()->is('shortcut/create*') ? 'active' : '' }}">
            <a href="{{ route('administration.shortcut.create') }}" class="menu-link">{{ ___('Add Shortcut') }}</a>
        </li>
    </ul>
</li>
