<li class="menu-item {{ request()->is('employee-recognition/monthly*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-award"></i>
        <div data-i18n="Employee Recognition">{{ __('Employee Recognition') }}</div>
    </a>
    <ul class="menu-sub">
        @if(auth()->user()->tl_employees()->wherePivot('is_active', true)->exists())
            <li class="menu-item {{ request()->is('employee-recognition/monthly') ? 'active' : '' }}">
                <a href="{{ route('administration.employee_recognition.monthly.index') }}" class="menu-link">{{ __('Monthly Evaluations') }}</a>
            </li>
        @endif
        <li class="menu-item {{ request()->is('employee-recognition/monthly/my*') ? 'active' : '' }}">
            <a href="{{ route('administration.employee_recognition.monthly.my') }}" class="menu-link">{{ __('My Scores') }}</a>
        </li>
        @can('User Read')
            <li class="menu-item {{ request()->is('employee-recognition/monthly/reports*') ? 'active' : '' }}">
                <a href="{{ route('administration.employee_recognition.monthly.reports') }}" class="menu-link">{{ __('Reports') }}</a>
            </li>
        @endcan
    </ul>
</li>
