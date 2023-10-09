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
                            <th>Clocked In At</th>
                            <th>Clocked Out At</th>
                            <th>Total Worked</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>#1</th>
                            <td>12-12-2023</td>
                            <td>02:00 PM</td>
                            <td>10:00 PM</td>
                            <td>08hr 23min 12sec</td>
                            <td>
                                <div class="d-inline-block">
                                    <a href="javascript:void(0);" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="text-primary ti ti-dots-vertical"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end m-0" style="">
                                        <a href="javascript:void(0);" class="dropdown-item">
                                            <i class="text-primary ti ti-pencil"></i> 
                                            Edit
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a href="javascript:void(0);" class="dropdown-item text-danger delete-record">
                                            <i class="ti ti-trash"></i> 
                                            Delete
                                        </a>
                                    </div>
                                </div>
                                <a href="#" class="btn btn-sm btn-icon item-edit" data-bs-toggle="tooltip" title="Show Details">
                                    <i class="text-primary ti ti-info-hexagon"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>        
    </div>
</div>
<!--/ User Profile Content -->
@endsection