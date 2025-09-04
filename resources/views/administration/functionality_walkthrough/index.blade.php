@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('All Functionality Walkthroughs'))

@section('css_links')
    {{--  External CSS  --}}
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('All Functionality Walkthroughs') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Functionality Walkthroughs') }}</li>
    <li class="breadcrumb-item active">{{ __('All Walkthroughs') }}</li>
@endsection

@section('content')

<!-- Start row -->
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">All Functionality Walkthroughs</h5>

                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.functionality_walkthrough.create') }}" class="btn btn-sm btn-primary">
                        <span class="tf-icon ti ti-plus ti-xs me-1"></span>
                        Create New Walkthrough
                    </a>
                    <a href="{{ route('administration.functionality_walkthrough.my') }}" class="btn btn-sm btn-outline-primary">
                        <span class="tf-icon ti ti-eye ti-xs me-1"></span>
                        My Walkthroughs
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter Form -->
                <form method="GET" action="{{ route('administration.functionality_walkthrough.index') }}" class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="creator_id" class="form-label">Filter by Creator</label>
                        <select name="creator_id" id="creator_id" class="form-select">
                            <option value="">All Creators</option>
                            @foreach($walkthroughs->pluck('creator')->unique('id') as $creator)
                                <option value="{{ $creator->id }}" {{ request('creator_id') == $creator->id ? 'selected' : '' }}>
                                    {{ get_employee_name($creator) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="created_month_year" class="form-label">Filter by Month/Year</label>
                        <input type="month" name="created_month_year" id="created_month_year" class="form-control" value="{{ request('created_month_year') }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <a href="{{ route('administration.functionality_walkthrough.index') }}" class="btn btn-outline-secondary">Clear</a>
                    </div>
                </form>

                <!-- Walkthroughs Table -->
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Creator</th>
                                <th>Assigned Roles</th>
                                <th>Steps</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($walkthroughs as $walkthrough)
                                <tr>
                                    <td>
                                        <strong>{{ $walkthrough->title }}</strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($walkthrough->creator->media->isNotEmpty())
                                                <img src="{{ $walkthrough->creator->media->first()->getUrl('thumb') }}" alt="Avatar" class="rounded-circle me-2" width="32" height="32">
                                            @else
                                                <div class="avatar-initial rounded-circle bg-label-primary me-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                                    {{ substr($walkthrough->creator->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ get_employee_name($walkthrough->creator) }}</h6>
                                                <small class="text-muted">{{ $walkthrough->creator->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($walkthrough->assigned_roles)
                                            @php
                                                $roleNames = \Spatie\Permission\Models\Role::whereIn('id', $walkthrough->assigned_roles)->pluck('name')->toArray();
                                            @endphp
                                            @foreach($roleNames as $roleName)
                                                <span class="badge bg-label-primary me-1">{{ $roleName }}</span>
                                            @endforeach
                                        @else
                                            <span class="badge bg-label-success">All Users</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-label-info">{{ $walkthrough->steps->count() }} steps</span>
                                    </td>
                                    <td>
                                        <small>{{ $walkthrough->created_at->format('M j, Y g:i A') }}</small>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('administration.functionality_walkthrough.show', $walkthrough) }}">
                                                    <i class="ti ti-eye me-1"></i> View
                                                </a>
                                                @can('Functionality Walkthrough Update')
                                                    <a class="dropdown-item" href="{{ route('administration.functionality_walkthrough.edit', $walkthrough) }}">
                                                        <i class="ti ti-pencil me-1"></i> Edit
                                                    </a>
                                                @endcan
                                                @can('Functionality Walkthrough Delete')
                                                    <a class="dropdown-item text-danger" href="{{ route('administration.functionality_walkthrough.destroy', $walkthrough) }}" onclick="return confirm('Are you sure you want to delete this walkthrough?')">
                                                        <i class="ti ti-trash me-1"></i> Delete
                                                    </a>
                                                @endcan
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="ti ti-file-text ti-3x mb-3"></i>
                                            <p>No functionality walkthroughs found.</p>
                                            <a href="{{ route('administration.functionality_walkthrough.create') }}" class="btn btn-primary">
                                                Create Your First Walkthrough
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End row -->

@endsection

@section('script_links')
    {{--  External Javascript Links --}}
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Custom Script Here
    </script>
@endsection
