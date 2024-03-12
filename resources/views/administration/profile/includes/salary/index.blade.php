@extends('administration.profile.index')

@section('profile_breadcrumb')
    <li class="breadcrumb-item active">{{ __('Base Salary History') }}</li>
@endsection

@section('profile_content')

<!-- User Profile Content -->
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">My Base Salary History</h5>
            </div>
            <div class="card-body">
                <table class="table data-table table-bordered table-responsive" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>Total Salary</th>
                            <th>Implemented From</th>
                            <th>Implemented To</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($salaries as $key => $salary) 
                            <tr>
                                <th>#{{ serial($salaries, $key) }}</th>
                                <td>
                                    <span class="text-bold" data-bs-toggle="tooltip" title="{{ spell_number($salary->total) }}">
                                        <i class="ti ti-currency-taka" style="margin-top: -4px; margin-right: -5px;"></i>
                                        {{ format_number($salary->total) }}
                                    </span>
                                </td>
                                <td>{{ show_date($salary->implemented_from) }}</td>
                                <td>
                                    @if ($salary->implemented_to) 
                                        {{ show_date($salary->implemented_to) }}
                                    @else
                                        <span class="badge bg-label-success">Running Salary</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-label-{{ $salary->status == 'Active' ? 'success' : 'danger' }}">{{ $salary->status }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('administration.my.salary.show', ['salary' => $salary]) }}" class="btn btn-sm btn-icon" data-bs-toggle="tooltip" title="Show Details">
                                        <i class="text-primary ti ti-info-hexagon"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>        
    </div>
</div>
<!--/ User Profile Content -->
@endsection