<!-- Penalty Management -->
@canany(['Penalty Everything', 'Penalty Create', 'Penalty Update', 'Penalty Delete'])
    <li class="menu-item {{ request()->is('penalty*') ? 'active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-gavel"></i>
            <div data-i18n="Penalty">{{ __('Penalty') }}</div>
        </a>
        <ul class="menu-sub">
            @canany(['Penalty Everything', 'Penalty Update', 'Penalty Delete'])
                <li class="menu-item {{ request()->is('penalty/all*') ? 'active' : '' }}">
                    <a href="{{ route('administration.penalty.index') }}" class="menu-link">{{ __('All Penalties') }}</a>
                </li>
            @endcanany
            @canany(['Penalty Everything', 'Penalty Read'])
                <li class="menu-item {{ request()->is('penalty/my*') ? 'active' : '' }}">
                    <a href="{{ route('administration.penalty.my') }}" class="menu-link">{{ __('My Penalties') }}</a>
                </li>
            @endcanany
            @canany(['Penalty Everything', 'Penalty Create'])
                <li class="menu-item {{ request()->is('penalty/create*') ? 'active' : '' }}">
                    <a href="{{ route('administration.penalty.create') }}" class="menu-link">{{ __('Create Penalty') }}</a>
                </li>
            @endcanany
        </ul>
    </li>
@elsecan(['Penalty Read'])
    <li class="menu-item {{ request()->is('penalty/my*') ? 'active' : '' }}">
        <a href="{{ route('administration.penalty.my') }}" class="menu-link">
            <i class="menu-icon tf-icons ti ti-gavel"></i>
            <div data-i18n="My Penalties">{{ __('My Penalties') }}</div>
        </a>
    </li>
@endcanany
