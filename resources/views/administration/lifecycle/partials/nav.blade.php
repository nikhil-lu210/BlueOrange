<!-- Navigation Tabs (remove badges, keep small left icons) -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body py-2">
                <ul class="nav nav-pills nav-fill gap-2 flex-column flex-sm-row">
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center justify-content-center gap-2 {{ request()->routeIs('administration.lifecycle.index') ? 'active' : '' }}" href="{{ route('administration.lifecycle.index') }}">
                            <i class="ti ti-layout fs-6"></i>
                            <span>Overview</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center justify-content-center gap-2 {{ request()->routeIs('administration.lifecycle.onboarding') ? 'active' : '' }}" href="{{ route('administration.lifecycle.onboarding') }}">
                            <i class="ti ti-user-check fs-6"></i>
                            <span>Onboarding</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center justify-content-center gap-2 {{ request()->routeIs('administration.lifecycle.active') ? 'active' : '' }}" href="{{ route('administration.lifecycle.active') }}">
                            <i class="ti ti-users-plus fs-6"></i>
                            <span>Active</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center justify-content-center gap-2 {{ request()->routeIs('administration.lifecycle.offboarding') ? 'active' : '' }}" href="{{ route('administration.lifecycle.offboarding') }}">
                            <i class="ti ti-user-minus fs-6"></i>
                            <span>Offboarding</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center justify-content-center gap-2 {{ request()->routeIs('administration.lifecycle.transfer') ? 'active' : '' }}" href="{{ route('administration.lifecycle.transfer') }}">
                            <i class="ti ti-arrow-big-right-line"></i>
                            <span>Transfers</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>