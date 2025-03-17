<!-- Vault Management -->
@canany(['Vault Create', 'Vault Read'])
<li class="menu-item {{ request()->is('vault*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-lock-square"></i>
        <div data-i18n="Vault">{{ __('Vault') }}</div>
    </a>
    <ul class="menu-sub">
        @canany(['Vault Read'])
            <li class="menu-item {{ request()->is('vault/all*') ? 'active' : '' }}">
                <a href="{{ route('administration.vault.index') }}" class="menu-link">{{ __('All Credentials') }}</a>
            </li>
        @endcanany
        @can('Vault Create')
            <li class="menu-item {{ request()->is('vault/create*') ? 'active' : '' }}">
                <a href="{{ route('administration.vault.create') }}" class="menu-link">{{ __('Store Credential') }}</a>
            </li>
        @endcan
    </ul>
</li>
@endcanany
