@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Employee Hiring'))

@section('css_links')
    {{--  External CSS  --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
        .candidate-card {
            transition: all 0.3s ease;
        }
        .candidate-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 25px 0 rgba(0, 0, 0, 0.1);
        }
        .progress-stage {
            font-size: 0.75rem;
        }
        .stage-indicator {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin: 0 5px;
        }
        .stage-completed {
            background-color: #28a745;
            color: white;
        }
        .stage-current {
            background-color: #ffc107;
            color: #212529;
        }
        .stage-pending {
            background-color: #e9ecef;
            color: #6c757d;
        }
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('Employee Hiring') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('administration.dashboard.index') }}">{{ __('Dashboard') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Employee Hiring') }}</li>
@endsection

@section('content')

<!-- Filters Card -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Filter Candidates') }}</h5>
                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse">
                    <i class="ti ti-filter"></i> {{ __('Filters') }}
                </button>
            </div>
            <div class="collapse {{ request()->hasAny(['search', 'status', 'stage', 'date_from', 'date_to']) ? 'show' : '' }}" id="filtersCollapse">
                <div class="card-body">
                    <form action="{{ route('administration.hiring.index') }}" method="GET">
                        <div class="row g-3">
                            <!-- Search -->
                            <div class="col-md-3">
                                <label for="search" class="form-label">{{ __('Search') }}</label>
                                <input type="text"
                                       class="form-control"
                                       id="search"
                                       name="search"
                                       value="{{ request('search') }}"
                                       placeholder="{{ __('Name, email, or role...') }}">
                            </div>

                            <!-- Status Filter -->
                            <div class="col-md-2">
                                <label for="status" class="form-label">{{ __('Status') }}</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">{{ __('All Statuses') }}</option>
                                    @foreach($statuses as $key => $label)
                                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Stage Filter -->
                            <div class="col-md-2">
                                <label for="stage" class="form-label">{{ __('Current Stage') }}</label>
                                <select class="form-select" id="stage" name="stage">
                                    <option value="">{{ __('All Stages') }}</option>
                                    @foreach($stages as $stage)
                                        <option value="{{ $stage->stage_order }}" {{ request('stage') == $stage->stage_order ? 'selected' : '' }}>{{ $stage->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Date From -->
                            <div class="col-md-2">
                                <label for="date_from" class="form-label">{{ __('From Date') }}</label>
                                <input type="date"
                                       class="form-control"
                                       id="date_from"
                                       name="date_from"
                                       value="{{ request('date_from') }}">
                            </div>

                            <!-- Date To -->
                            <div class="col-md-2">
                                <label for="date_to" class="form-label">{{ __('To Date') }}</label>
                                <input type="date"
                                       class="form-control"
                                       id="date_to"
                                       name="date_to"
                                       value="{{ request('date_to') }}">
                            </div>

                            <!-- Actions -->
                            <div class="col-md-1">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    @if(request()->hasAny(['search', 'status', 'stage', 'date_from', 'date_to']))
                                        <a href="{{ route('administration.hiring.index') }}" class="btn btn-outline-secondary btn-sm" title="{{ __('Clear Filters') }}">
                                            <i class="ti ti-x"></i>
                                        </a>
                                    @endif
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="ti ti-filter"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0">{{ __('Hiring Candidates') }}</h4>
                <p class="text-muted mb-0">{{ __('Manage candidate applications and hiring process') }}</p>
            </div>
            @canany(['Employee Hiring Everything', 'Employee Hiring Create'])
                <div>
                    <a href="{{ route('administration.hiring.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus"></i> {{ __('Add Candidate') }}
                    </a>
                </div>
            @endcanany
        </div>
    </div>
</div>

<!-- Candidates Grid -->
<div class="row">
    @forelse($candidates as $candidate)
        <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
            <div class="card candidate-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="card-title mb-1">{{ $candidate->name }}</h5>
                            <p class="text-muted mb-0">{{ $candidate->expected_role }}</p>
                        </div>
                        <span class="badge {{ $candidate->status_badge_class }}">
                            {{ $candidate->status_formatted }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">
                            <i class="ti ti-mail"></i> {{ $candidate->email }}
                        </small>
                        <small class="text-muted d-block">
                            <i class="ti ti-phone"></i> {{ $candidate->phone }}
                        </small>
                        @if($candidate->expected_salary)
                            <small class="text-muted d-block">
                                <i class="ti ti-currency-rupee"></i> {{ $candidate->expected_salary_formatted }}
                            </small>
                        @endif
                    </div>

                    <!-- Progress Stages -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-center align-items-center">
                            @for($i = 1; $i <= 3; $i++)
                                <div class="stage-indicator
                                    @if($i < $candidate->current_stage) stage-completed
                                    @elseif($i == $candidate->current_stage) stage-current
                                    @else stage-pending
                                    @endif">
                                    {{ $i }}
                                </div>
                                @if($i < 3)
                                    <div class="flex-grow-1 mx-2" style="height: 2px; background-color: #e9ecef;"></div>
                                @endif
                            @endfor
                        </div>
                        <div class="text-center mt-2">
                            <small class="text-muted progress-stage">
                                {{ __('Current Stage') }}: {{ $candidate->current_stage_name }}
                            </small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            {{ __('Added') }}: {{ $candidate->created_at->format('M d, Y') }}
                        </small>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                {{ __('Actions') }}
                            </button>
                            <ul class="dropdown-menu">
                                @canany(['Employee Hiring Everything', 'Employee Hiring Read'])
                                    <li>
                                        <a class="dropdown-item" href="{{ route('administration.hiring.show', $candidate) }}">
                                            <i class="ti ti-eye"></i> {{ __('View Details') }}
                                        </a>
                                    </li>
                                @endcanany
                                @canany(['Employee Hiring Everything', 'Employee Hiring Update'])
                                    <li>
                                        <a class="dropdown-item" href="{{ route('administration.hiring.edit', $candidate) }}">
                                            <i class="ti ti-edit"></i> {{ __('Edit') }}
                                        </a>
                                    </li>
                                @endcanany
                                @if($candidate->status === 'in_progress' && $candidate->current_stage >= 3)
                                    @canany(['Employee Hiring Everything'])
                                        <li>
                                            <a class="dropdown-item" href="{{ route('administration.hiring.complete.form', $candidate) }}">
                                                <i class="ti ti-user-plus"></i> {{ __('Complete Hiring') }}
                                            </a>
                                        </li>
                                    @endcanany
                                @endif
                                @canany(['Employee Hiring Everything', 'Employee Hiring Delete'])
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="{{ route('administration.hiring.destroy', $candidate) }}"
                                           onclick="return confirm('Are you sure you want to delete this candidate?')">
                                            <i class="ti ti-trash"></i> {{ __('Delete') }}
                                        </a>
                                    </li>
                                @endcanany
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="ti ti-users-off display-4 text-muted mb-3"></i>
                    <h5 class="text-muted">{{ __('No candidates found') }}</h5>
                    <p class="text-muted">{{ __('Start by adding your first candidate to the hiring process.') }}</p>
                    @canany(['Employee Hiring Everything', 'Employee Hiring Create'])
                        <a href="{{ route('administration.hiring.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus"></i> {{ __('Add First Candidate') }}
                        </a>
                    @endcanany
                </div>
            </div>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($candidates->hasPages())
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $candidates->links() }}
            </div>
        </div>
    </div>
@endif

@endsection

@section('script_links')
    {{--  External Javascript Links --}}
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2();

            // Auto-collapse filters if no filters are applied
            const hasFilters = '{{ request()->hasAny(["search", "status", "stage", "date_from", "date_to"]) }}';
            if (hasFilters) {
                $('#filtersCollapse').addClass('show');
            }
        });
    </script>
@endsection
