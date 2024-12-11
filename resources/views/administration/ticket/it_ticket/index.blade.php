@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('IT Ticket'))

@section('css_links')
    {{--  External CSS  --}}
    <!-- DataTables css -->
    <link href="{{ asset('assets/css/custom_css/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom_css/datatables/datatable.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('All IT Tickets') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('IT Ticket') }}</li>
    <li class="breadcrumb-item active">{{ __('All IT Tickets') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">All IT Tickets</h5>
        
                <div class="card-header-elements ms-auto">
                    @can ('IT Ticket Create') 
                        <a href="{{ route('administration.ticket.it_ticket.create') }}" class="btn btn-sm btn-primary">
                            <span class="tf-icon ti ti-plus ti-xs me-1"></span>
                            Arise New Ticket
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive-md table-responsive-sm w-100">
                    <table class="table data-table table-bordered">
                        <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>Creator</th>
                                <th>Title</th>
                                <th>Created At</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($itTickets as $key => $ticket) 
                                <tr>
                                    <th>#{{ serial($itTickets, $key) }}</th>
                                    <td>
                                        {!! show_user_name_and_avatar($ticket->creator, name: false) !!}
                                    </td>
                                    <td>
                                        <b title="{{ $ticket->title }}">{{ show_content($ticket->title, 30) }}</b>
                                        <br>
                                        <small class="text-muted">{{ show_content($ticket->description, 30) }}</small>
                                    </td>
                                    <td>
                                        <b>{{ show_date($ticket->created_at) }}</b>
                                        <br>
                                        <span>at <b>{{ show_time($ticket->created_at) }}</b></span>
                                    </td>
                                    <td>
                                        {!! show_status($ticket->status) !!}
                                        @isset ($ticket->solver) 
                                            <br>
                                            <small title="Solved By">
                                                {!! show_user_name_and_avatar($ticket->solver, avatar: false, name: false, role: false) !!}
                                            </small>
                                        @endisset
                                    </td>
                                    <td class="text-center">
                                        @can ('IT Ticket Delete') 
                                            <a href="{{ route('administration.ticket.it_ticket.destroy', ['it_ticket' => $ticket]) }}" class="btn btn-sm btn-icon btn-danger confirm-danger" data-bs-toggle="tooltip" title="Delete IT Ticket?">
                                                <i class="text-white ti ti-trash"></i>
                                            </a>
                                        @endcan
                                        @can ('IT Ticket Update') 
                                            <a href="{{ route('administration.ticket.it_ticket.edit', ['it_ticket' => $ticket]) }}" class="btn btn-sm btn-icon btn-info" data-bs-toggle="tooltip" title="Edit IT Ticket?">
                                                <i class="text-white ti ti-pencil"></i>
                                            </a>
                                        @endcan
                                        @can ('IT Ticket Read') 
                                            <a href="{{ route('administration.ticket.it_ticket.show', ['it_ticket' => $ticket]) }}" class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" title="Show Details">
                                                <i class="text-white ti ti-info-hexagon"></i>
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
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
    <!-- Datatable js -->
    <script src="{{ asset('assets/js/custom_js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/datatable.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Custom Script Here
        $(document).ready(function () {
            // 
        });
    </script>
@endsection
