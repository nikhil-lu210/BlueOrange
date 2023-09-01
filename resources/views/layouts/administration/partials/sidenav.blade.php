<!-- Start Leftbar -->
<div class="leftbar">
    <!-- Start Sidebar -->
    <div class="sidebar">
        <!-- Start Logobar -->
        <div class="logobar">
            <a href="#" class="logo logo-large"><img src="{{ asset('assets/images/logo.svg') }}" class="img-fluid" alt="logo" /></a>
            <a href="#" class="logo logo-small"><img src="{{ asset('assets/images/small_logo.svg') }}" class="img-fluid" alt="logo" /></a>
        </div>
        <!-- End Logobar -->
        <!-- Start Navigationbar -->
        <div class="navigationbar">
            <ul class="vertical-menu">
                <li>
                    <a href="{{ route('administration.dashboard.index') }}">
                        <i class="sl-icon-layers"></i>
                        <span>{{ __('Dashboard') }}</span>
                        <span class="badge badge-success pull-right">{{ __('New') }}</span>
                    </a>
                </li>

                <li>
                    <a href="javaScript:void(0);">
                        <i class="sl-icon-settings"></i>
                        <span>{{ __('Settings') }}</span>
                        <i class="feather icon-chevron-right pull-right"></i>
                    </a>
                    <ul class="vertical-submenu">
                        <li>
                            <a href="javaScript:void(0);">{{ __('Permission') }}
                                <i class="feather icon-chevron-right pull-right"></i>
                            </a>
                            <ul class="vertical-submenu">
                                <li>
                                    <a href="{{ route('administration.settings.permission.index') }}">
                                        {{ __('All Permissions') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('administration.settings.permission.group.index') }}">
                                        {{ __('Permission Groups') }}
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javaScript:void(0);">{{ __('Role') }}
                                <i class="feather icon-chevron-right pull-right"></i>
                            </a>
                            <ul class="vertical-submenu">
                                <li>
                                    <a href="{{ route('administration.settings.role.index') }}">
                                        {{ __('All Roles') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('administration.settings.role.create') }}">
                                        {{ __('Create Role') }}
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- End Navigationbar -->
    </div>
    <!-- End Sidebar -->
</div>
<!-- End Leftbar -->