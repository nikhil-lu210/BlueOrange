@extends('administration.profile.index')

@section('profile_breadcrumb')
    <li class="breadcrumb-item active">{{ __('Monthly Salary History') }}</li>
@endsection

@section('profile_content')

<!-- User Profile Content -->
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">My Monthly Salaries</h5>
            </div>
            <div class="card-body">
                <table class="table data-table table-bordered table-responsive" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>Base Salary</th>
                            <th>Total Earning</th>
                            <th>Salary Proccessed</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($monthly_salaries as $key => $monthlySalary) 
                            <tr>
                                <th>#{{ serial($monthly_salaries, $key) }}</th>
                                <td>
                                    <a href="{{ route('administration.settings.user.salary.show', ['user' => $user, 'salary' => $monthlySalary->salary]) }}" target="_blank" class="text-bold" data-bs-toggle="tooltip" title="{{ spell_number($monthlySalary->salary->total) }}">
                                        <i class="ti ti-currency-taka" style="margin-top: -4px; margin-right: -5px;"></i>
                                        {{ format_number($monthlySalary->salary->total) }}
                                    </a>
                                </td>
                                <td>{{ 'total_earning' }}</td>
                                <td>{{ show_date_time($monthlySalary->created_at) }}</td>
                                <td class="text-center">
                                    <span class="badge bg-label-{{ $monthlySalary->status == 'Paid' ? 'success' : 'primary' }}">{{ $monthlySalary->status }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-sm btn-icon confirm-success" data-bs-toggle="tooltip" title="Download Invoice">
                                        <i class="text-dark ti ti-download"></i>
                                    </a>
                                    <a href="{{ route('administration.my.salary.monthly.history.show', ['monthly_salary' => $monthlySalary]) }}" class="btn btn-sm btn-icon" data-bs-toggle="tooltip" title="Show Details">
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