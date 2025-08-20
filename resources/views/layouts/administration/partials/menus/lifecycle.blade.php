<!-- Task Management -->
@canany(['Event Everything', 'Event Create', 'Event Read'])
<li class="menu-item {{ request()->is('lifecycle*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-calendar-event"></i>
        <div data-i18n="Event">{{ __('Employee Lifecycle Management') }}</div>
    </a>
    <ul class="menu-sub">
        @canany(['Event Everything'])
            <li class="menu-item {{ request()->is('lifecycle/all*') ? 'active' : '' }}">
                <a href="{{ route('administration.lifecycle.index') }}" class="menu-link">{{ __('All LifeCycle') }}</a>
            </li>
        @endcanany

        @can('Event Read')
            <li class="menu-item {{ request()->is('lifecycle/my*') ? 'active' : '' }}">
                <a href="{{ route('administration.lifecycle.my') }}" class="menu-link">{{ __('My Event') }}</a>
            </li>
        @endcan
        @can('Event Create')
            <li class="menu-item {{ request()->is('lifecycle/create*') ? 'active' : '' }}">
                <a href="{{ route('administration.lifecycle.create') }}" class="menu-link">{{ __('New event') }}</a>
            </li>
        @endcan
    </ul>
</li>
@endcanany