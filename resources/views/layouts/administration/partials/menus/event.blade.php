<!-- Task Management -->
@canany(['Event Everything', 'Event Create', 'Event Read'])
<li class="menu-item {{ request()->is('event*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-calendar-event"></i>
        <div data-i18n="Event">{{ __('Event') }}</div>
    </a>
    <ul class="menu-sub">
        @canany(['Event Everything'])
            <li class="menu-item {{ request()->is('event/all*') ? 'active' : '' }}">
                <a href="{{ route('administration.event.index') }}" class="menu-link">{{ __('All Event') }}</a>
            </li>
        @endcanany

        @can('Event Read')
            <li class="menu-item {{ request()->is('event/my*') ? 'active' : '' }}">
                <a href="{{ route('administration.event.my') }}" class="menu-link">{{ __('My Event') }}</a>
            </li>
        @endcan
        @can('Event Create')
            <li class="menu-item {{ request()->is('event/create*') ? 'active' : '' }}">
                <a href="{{ route('administration.event.create') }}" class="menu-link">{{ __('New event') }}</a>
            </li>
        @endcan
    </ul>
</li>
@endcanany
