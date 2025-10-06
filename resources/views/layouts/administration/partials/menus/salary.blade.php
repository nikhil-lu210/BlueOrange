@canany (['Salary Everything'])
    <li class="menu-item {{ request()->is('accounts/salary*') ? 'active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-businessplan"></i>
            <div data-i18n="Salary">{{ ___('Salary') }}</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item {{ request()->is('accounts/salary/monthly/all*') ? 'active' : '' }}">
                <a href="{{ route('administration.accounts.salary.monthly.index') }}" class="menu-link">{{ ___('Monthly Salaries') }}</a>
            </li>
        </ul>
    </li>
@endcanany
