@extends('administration.profile.index')

@section('profile_content')

<!-- User Profile Content -->
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">All Attendances</h5>
            </div>
            <div class="card-body">
                <table class="table data-table table-bordered table-responsive" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>Date</th>
                            <th>Clocked IN</th>
                            <th>Clock Out</th>
                            <th>Total</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attendances as $key => $attendance) 
                            <tr>
                                <th>#{{ serial($attendances, $key) }}</th>
                                <td>{{ show_date($attendance->clock_in_date) }}</td>
                                <td>{{ show_time($attendance->clock_in) }}</td>
                                <td>
                                    @isset($attendance->clock_out)
                                        {{ show_time($attendance->clock_out) }}
                                    @else
                                        <b class="text-success text-uppercase">Running</b>
                                    @endisset
                                </td>
                                <td>
                                    @isset($attendance->total_time)
                                        <b>
                                            {!! total_time($attendance->total_time) !!}
                                        </b>
                                    @else
                                        <b class="text-success text-uppercase">Running</b>
                                    @endisset
                                </td>
                                <td>
                                    @canany(['Attendance Update', 'Attendance Delete'])
                                        <div class="d-inline-block">
                                            <a href="javascript:void(0);" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="text-primary ti ti-dots-vertical"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end m-0" style="">
                                                @can('Attendance Update')
                                                    <a href="javascript:void(0);" class="dropdown-item">
                                                        <i class="text-primary ti ti-pencil"></i> 
                                                        Edit
                                                    </a>
                                                @endcan
                                                @can('Attendance Delete')
                                                    <div class="dropdown-divider"></div>
                                                    <a href="javascript:void(0);" class="dropdown-item text-danger delete-record">
                                                        <i class="ti ti-trash"></i> 
                                                        Delete
                                                    </a>
                                                @endcan
                                            </div>
                                        </div>
                                    @endcanany
                                    <a href="#" class="btn btn-sm btn-icon item-edit" data-bs-toggle="tooltip" title="Show Details">
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