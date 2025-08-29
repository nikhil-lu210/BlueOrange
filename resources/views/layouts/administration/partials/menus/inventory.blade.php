<!-- Inventory Management -->
@canany(['Inventory Everything', 'Inventory Create', 'Inventory Read', 'Inventory Update', 'Inventory Delete'])
    <li class="menu-item {{ request()->is('inventory*') ? 'active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-package"></i>
            <div data-i18n="Inventory">{{ __('Inventory') }}</div>
        </a>
        <ul class="menu-sub">
            @canany(['Inventory Everything', 'Inventory Update', 'Inventory Delete'])
                <li class="menu-item {{ request()->is('inventory/all*') ? 'active' : '' }}">
                    <a href="{{ route('administration.inventory.index') }}" class="menu-link">{{ __('All Inventories') }}</a>
                </li>
            @endcanany
            @canany(['Inventory Everything', 'Inventory Create'])
                <li class="menu-item {{ request()->is('inventory/create*') ? 'active' : '' }}">
                    <a href="{{ route('administration.inventory.create') }}" class="menu-link">{{ __('Store Inventory') }}</a>
                </li>
            @endcanany
        </ul>
    </li>
@endcanany
