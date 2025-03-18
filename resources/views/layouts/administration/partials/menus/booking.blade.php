<!-- Booking Management -->
@canany(['Dining Room Booking Everything', 'Dining Room Booking Create', 'Dining Room Booking Read'])
<li class="menu-item {{ request()->is('booking*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-hand-click"></i>
        <div data-i18n="Booking">{{ __('Booking') }}</div>
    </a>
    <ul class="menu-sub">
        @canany(['Dining Room Booking Create', 'Dining Room Booking Read'])
            <li class="menu-item {{ request()->is('booking/dining_room*') ? 'active' : '' }}">
                <a href="{{ route('administration.booking.dining_room.index') }}" class="menu-link">{{ __('Dining Room Booking') }}</a>
            </li>
        @endcanany
    </ul>
</li>
@endcanany
