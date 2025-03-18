@canany (['Income Create', 'Income Read', 'Expense Create', 'Expense Read'])
    <li class="menu-item {{ request()->is('accounts/income_expense*') ? 'active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-calculator"></i>
            <div data-i18n="Income & Expense">{{ __('Income & Expense') }}</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item {{ request()->is('accounts/income_expense/statistics*') ? 'active' : '' }}">
                <a href="{{ route('administration.accounts.income_expense.statistics.index') }}" class="menu-link">{{ __('Statistics') }}</a>
            </li>

            <li class="menu-item {{ request()->is('accounts/income_expense/category/all*') ? 'active' : '' }}">
                <a href="{{ route('administration.accounts.income_expense.category.index') }}" class="menu-link">{{ __('Categories') }}</a>
            </li>

            @canany (['Income Create', 'Income Read'])
                <li class="menu-item {{ request()->is('accounts/income_expense/income*') ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <div data-i18n="Income">{{ __('Income') }}</div>
                    </a>
                    <ul class="menu-sub">
                        @can ('Income Read')
                            <li class="menu-item {{ request()->is('accounts/income_expense/income/all*') ? 'active' : '' }}">
                                <a href="{{ route('administration.accounts.income_expense.income.index') }}" class="menu-link">
                                    <div data-i18n="All Incomes">{{ __('All Incomes') }}</div>
                                </a>
                            </li>
                        @endcan
                        @can ('Income Create')
                            <li class="menu-item {{ request()->is('accounts/income_expense/income/create') ? 'active' : '' }}">
                                <a href="{{ route('administration.accounts.income_expense.income.create') }}" class="menu-link">
                                    <div data-i18n="Create Income">{{ __('Create Income') }}</div>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            @canany (['Expense Create', 'Expense Read'])
                <li class="menu-item {{ request()->is('accounts/income_expense/expense*') ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <div data-i18n="Expense">{{ __('Expense') }}</div>
                    </a>
                    <ul class="menu-sub">
                        @can ('Expense Read')
                            <li class="menu-item {{ request()->is('accounts/income_expense/expense/all*') ? 'active' : '' }}">
                                <a href="{{ route('administration.accounts.income_expense.expense.index') }}" class="menu-link">
                                    <div data-i18n="All Expenses">{{ __('All Expenses') }}</div>
                                </a>
                            </li>
                        @endcan
                        @can ('Expense Create')
                            <li class="menu-item {{ request()->is('accounts/income_expense/expense/create') ? 'active' : '' }}">
                                <a href="{{ route('administration.accounts.income_expense.expense.create') }}" class="menu-link">
                                    <div data-i18n="Create Expense">{{ __('Create Expense') }}</div>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany
        </ul>
    </li>
@endcanany
