<!-- Certificate Management -->
@canany(['Certificate Everything', 'Certificate Create', 'Certificate Update', 'Certificate Delete'])
    <li class="menu-item {{ request()->is('certificate*') ? 'active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-certificate"></i>
            <div data-i18n="Certificate">{{ __('Certificate') }}</div>
        </a>
        <ul class="menu-sub">
            @canany(['Certificate Everything', 'Certificate Update', 'Certificate Delete'])
                <li class="menu-item {{ request()->is('certificate/all*') ? 'active' : '' }}">
                    <a href="{{ route('administration.certificate.index') }}" class="menu-link">{{ __('All Certificates') }}</a>
                </li>
            @endcanany
            @canany(['Certificate Everything', 'Certificate Read'])
                <li class="menu-item {{ request()->is('certificate/my*') ? 'active' : '' }}">
                    <a href="{{ route('administration.certificate.my') }}" class="menu-link">{{ __('My Certificates') }}</a>
                </li>
            @endcanany
            @canany(['Certificate Everything', 'Certificate Create'])
                <li class="menu-item {{ request()->is('certificate/create*') ? 'active' : '' }}">
                    <a href="{{ route('administration.certificate.create') }}" class="menu-link">{{ __('Create Certificate') }}</a>
                </li>
            @endcanany
        </ul>
    </li>
@elsecan(['Certificate Read'])
    <li class="menu-item {{ request()->is('certificate/my*') ? 'active' : '' }}">
        <a href="{{ route('administration.penalty.my') }}" class="menu-link">
            <i class="menu-icon tf-icons ti ti-gavel"></i>
            <div data-i18n="My Certificates">{{ __('My Certificates') }}</div>
        </a>
    </li>
@endcanany
