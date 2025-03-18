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
        @can ('Attendance Everything')
            <li class="menu-item {{ request()->is('attendance/create*') ? 'active' : '' }}">
                <a href="{{ route('administration.attendance.create') }}" class="menu-link">{{ __('Assign Attendance') }}</a>
            </li>
        @endcan
        @can('Attendance Create')
            @hasanyrole(['Developer'])
                <li class="menu-item {{ request()->is('attendance/qrcode*') ? 'active' : '' }}">
                    <a href="{{ route('administration.attendance.qrcode.scanner') }}" class="menu-link">{{ __('QR Code Attendance') }}</a>
                </li>
            @endhasanyrole

            <li class="menu-item {{ request()->is('attendance/barcode*') ? 'active' : '' }}">
                <a href="{{ route('administration.attendance.barcode.scanner') }}" class="menu-link">{{ __('Bar Code Attendance') }}</a>
            </li>
        @endcan
        @canany (['Attendance Everything', 'Attendance Read', 'Attendance Update'])
            <li class="menu-item {{ request()->is('attendance/issue*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <div data-i18n="Attendance Issue">{{ __('Attendance Issue') }}</div>
                </a>
                <ul class="menu-sub">
                    @can ('Attendance Update')
                        <li class="menu-item {{ request()->is('attendance/issue/all*') ? 'active' : '' }}">
                            <a href="{{ route('administration.attendance.issue.index') }}" class="menu-link">
                                <div data-i18n="All Issues">{{ __('All Issues') }}</div>
                            </a>
                        </li>
                    @endcan
                    @can ('Attendance Read')
                        <li class="menu-item {{ request()->is('attendance/issue/my*') ? 'active' : '' }}">
                            <a href="{{ route('administration.attendance.issue.my') }}" class="menu-link">
                                <div data-i18n="My Issues">{{ __('My Issues') }}</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->is('attendance/issue/create*') ? 'active' : '' }}">
                            <a href="{{ route('administration.attendance.issue.create') }}" class="menu-link">
                                <div data-i18n="Create Issue">{{ __('Create Issue') }}</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany
    </ul>
</li>
@endcanany
