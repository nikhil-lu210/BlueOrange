<!-- Employee Hiring Management -->
@canany(['Employee Hiring Everything', 'Employee Hiring Create', 'Employee Hiring Update', 'Employee Hiring Delete'])
    <li class="menu-item {{ request()->is('hiring*') ? 'active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-users-plus"></i>
            <div data-i18n="Employee Hiring">{{ __('Employee Hiring') }}</div>
        </a>
        <ul class="menu-sub">
            @canany(['Employee Hiring Everything', 'Employee Hiring Update', 'Employee Hiring Delete'])
                <li class="menu-item {{ request()->is('hiring/all*') ? 'active' : '' }}">
                    <a href="{{ route('administration.hiring.index') }}" class="menu-link">{{ __('All Candidates') }}</a>
                </li>
            @endcanany
            @canany(['Employee Hiring Everything', 'Employee Hiring Create'])
                <li class="menu-item {{ request()->is('hiring/create*') ? 'active' : '' }}">
                    <a href="{{ route('administration.hiring.create') }}" class="menu-link">{{ __('Add Candidate') }}</a>
                </li>
            @endcanany
            @canany(['Employee Hiring Everything', 'Employee Hiring Read'])
                <li class="menu-item {{ request()->is('hiring/my-evaluations*') ? 'active' : '' }}">
                    <a href="{{ route('administration.hiring.my.evaluations') }}" class="menu-link">{{ __('My Evaluations') }}</a>
                </li>
            @endcanany
        </ul>
    </li>
@endcanany
