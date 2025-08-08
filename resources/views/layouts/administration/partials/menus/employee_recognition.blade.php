@canany(['Recognition Everything', 'Recognition Create', 'Recognition Read'])
    <li class="menu-item {{ request()->is('recognition*') ? 'active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-award"></i>
            <div data-i18n="Recognition">{{ __('Recognition') }}</div>
        </a>
        <ul class="menu-sub">
            @can('Recognition Everything')
                <li class="menu-item {{ request()->is('recognition/reports*') ? 'active' : '' }}">
                    <a href="{{ route('administration.employee_recognition.reports') }}" class="menu-link">{{ __('Reports') }}</a>
                </li>
            @endcan
            @can('Recognition Create')
                <li class="menu-item {{ request()->is('recognition') ? 'active' : '' }}">
                    <a href="{{ route('administration.employee_recognition.index') }}" class="menu-link">{{ __('Provide Recognition') }}</a>
                </li>
            @endcan
            @can('Recognition Read')
                <li class="menu-item {{ request()->is('recognition/my*') ? 'active' : '' }}">
                    <a href="{{ route('administration.employee_recognition.my') }}" class="menu-link">{{ __('My Recognition') }}</a>
                </li>
            @endcan
        </ul>
    </li>
@endcanany
