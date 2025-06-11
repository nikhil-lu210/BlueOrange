<!-- Penalty Management -->
@canany(['Penalty Everything', 'Penalty Create', 'Penalty Read'])
<li class="menu-item {{ request()->is('penalty*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-gavel"></i>
        <div data-i18n="Penalty">{{ __('Penalty') }}</div>
    </a>
    <ul class="menu-sub">
        @can('Penalty Everything')
            <li class="menu-item {{ request()->is('penalty/all*') ? 'active' : '' }}">
                <a href="{{ route('administration.penalty.index') }}" class="menu-link">{{ __('All Penalties') }}</a>
            </li>
        @endcan
        @canany(['Penalty Everything', 'Penalty Create'])
            <li class="menu-item {{ request()->is('penalty/create*') ? 'active' : '' }}">
                <a href="{{ route('administration.penalty.create') }}" class="menu-link">{{ __('Create Penalty') }}</a>
            </li>
        @endcanany
    </ul>
</li>
@endcanany
