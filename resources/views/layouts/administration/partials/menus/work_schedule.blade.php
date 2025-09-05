<!-- Work Schedule Management -->
@canany(['User Everything', 'User Create', 'User Read'])
<li class="menu-item {{ request()->is('work-schedule*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-calendar-time"></i>
        <div data-i18n="Work Schedule">{{ __('Work Schedule') }}</div>
    </a>
    <ul class="menu-sub">
        @canany(['User Everything', 'User Read'])
            <li class="menu-item {{ request()->is('work-schedule') && !request()->is('work-schedule/*') ? 'active' : '' }}">
                <a href="{{ route('administration.work_schedule.index') }}" class="menu-link">{{ __('All Schedules') }}</a>
            </li>
        @endcanany
        @canany(['User Everything', 'User Read'])
            <li class="menu-item {{ request()->is('work-schedule/report*') ? 'active' : '' }}">
                <a href="{{ route('administration.work_schedule.report') }}" class="menu-link">{{ __('Schedule Report') }}</a>
            </li>
        @endcanany
        @can('User Create')
            <li class="menu-item {{ request()->is('work-schedule/create*') ? 'active' : '' }}">
                <a href="{{ route('administration.work_schedule.create') }}" class="menu-link">{{ __('Assign Schedule') }}</a>
            </li>
        @endcan
    </ul>
</li>
@endcanany
