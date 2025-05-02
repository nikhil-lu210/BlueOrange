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

    {{-- Select 2 --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />

    {{-- Bootstrap Datepicker --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('My IT Tickets') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('IT Ticket') }}</li>
    <li class="breadcrumb-item active">{{ __('My IT Tickets') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <form action="{{ route('administration.ticket.it_ticket.my') }}" method="get" autocomplete="off">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-5">
                            <label for="solved_by" class="form-label">{{ __('Select Solver') }}</label>
                            <select name="solved_by" id="solved_by" class="select2 form-select @error('solved_by') is-invalid @enderror" data-allow-clear="true">
                                <option value="" {{ is_null(request()->solved_by) ? 'selected' : '' }}>{{ __('Select Solver') }}</option>
                                @foreach ($ticketSolvers as $solver)
                                    <option value="{{ $solver->id }}" {{ $solver->id == request()->solved_by ? 'selected' : '' }}>
                                        {{ get_employee_name($solver) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('solved_by')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label">{{ __('IT-Tickets Of') }}</label>
                            <input type="text" name="ticket_month_year" value="{{ request()->ticket_month_year ?? old('ticket_month_year') }}" class="form-control month-year-picker" placeholder="MM yyyy" tabindex="-1"/>
                            @error('ticket_month_year')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-3">
                            <label for="status" class="form-label">{{ __('Select Status') }}</label>
                            <select name="status" id="status" class="form-select bootstrap-select w-100 @error('status') is-invalid @enderror"  data-style="btn-default">
                                {{-- 'Pending','Running','Solved','Canceled' --}}
                                <option value="" {{ is_null(request()->status) ? 'selected' : '' }}>{{ __('Select status') }}</option>
                                <option value="Pending" {{ request()->status == 'Pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                <option value="Running" {{ request()->status == 'Running' ? 'selected' : '' }}>{{ __('Running') }}</option>
                                <option value="Solved" {{ request()->status == 'Solved' ? 'selected' : '' }}>{{ __('Solved') }}</option>
                                <option value="Canceled" {{ request()->status == 'Canceled' ? 'selected' : '' }}>{{ __('Canceled') }}</option>
                            </select>
                            @error('status')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12 text-end">
                        @if (request()->solved_by || request()->ticket_month_year || request()->status)
                            <a href="{{ route('administration.ticket.it_ticket.my') }}" class="btn btn-danger confirm-warning">
                                <span class="tf-icon ti ti-refresh ti-xs me-1"></span>
                                {{ __('Reset Filters') }}
                            </a>
                        @endif
                        <button type="submit" name="filter_tickets" value="true" class="btn btn-primary">
                            <span class="tf-icon ti ti-filter ti-xs me-1"></span>
                            {{ __('Filter Tickets') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">
                    @if(request()->has('filter_tickets'))
                        My
                        @if(request()->filled('status'))
                            <b class="text-dark">{{ __(request()->status) }}</b>
                        @endif
                        IT Tickets
                        @if(request()->filled('ticket_month_year'))
                            Of <b class="text-dark">{{ request()->ticket_month_year }}</b>
                        @endif
                        @if(request()->filled('solved_by'))
                            Solved By
                            <b class="text-dark">{{ show_employee_data(request()->solved_by, 'alias_name') }}</b>
                        @endif
                    @else
                        My IT Tickets Of {{ date('F Y') }}
                    @endif
                </h5>

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
                                        <b title="{{ $ticket->title }}">{{ show_content($ticket->title, 30) }}</b>
                                        <br>
                                        <small class="text-muted">{!! show_content($ticket->description, 40) !!}</small>
                                    </td>
                                    <td>
                                        <b>{{ show_date($ticket->created_at) }}</b>
                                        <br>
                                        <span>at <b>{{ show_time($ticket->created_at) }}</b></span>
                                    </td>
                                    <td>
                                        @if ($ticket->status === 'Pending')
                                            <span class="badge bg-dark">{{ __('Pending') }}</span>
                                        @elseif ($ticket->status === 'Running')
                                            <span class="badge bg-primary">{{ __('Running') }}</span>
                                        @elseif ($ticket->status === 'Solved')
                                            <span class="badge bg-success">{{ __('Solved') }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ __('Canceled') }}</span>
                                        @endif
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
                                        @if ($ticket->status === 'Pending')
                                            @can ('IT Ticket Update')
                                                <a href="{{ route('administration.ticket.it_ticket.edit', ['it_ticket' => $ticket]) }}" class="btn btn-sm btn-icon btn-info" data-bs-toggle="tooltip" title="Edit IT Ticket?">
                                                    <i class="text-white ti ti-pencil"></i>
                                                </a>
                                            @endcan
                                        @endif
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

    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Custom Script Here
        $(document).ready(function () {
            $('.bootstrap-select').each(function() {
                if (!$(this).data('bs.select')) { // Check if it's already initialized
                    $(this).selectpicker();
                }
            });

            $('.month-year-picker').datepicker({
                format: 'MM yyyy',         // Display format to show full month name and year
                minViewMode: 'months',     // Only allow month selection
                todayHighlight: true,
                autoclose: true,
                orientation: 'auto right'
            });
        });
    </script>
@endsection

