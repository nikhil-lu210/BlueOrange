@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('IT Ticket Details'))

@section('css_links')
    {{--  External CSS  --}}
    {{-- <!-- Vendors CSS --> --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('IT Ticket Details') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('IT Ticket') }}</li>
    <li class="breadcrumb-item">
        @canany (['IT Ticket Update', 'IT Ticket Delete']) 
            <a href="{{ route('administration.ticket.it_ticket.index') }}">{{ __('All Tickets') }}</a>
        @elsecanany (['IT Ticket Read', 'IT Ticket Create']) 
            <a href="{{ route('administration.ticket.it_ticket.my') }}">{{ __('My Tickets') }}</a>
        @endcanany
    </li>
    <li class="breadcrumb-item active">{{ __('IT Ticket Details') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">IT Ticket's Details</h5>
        
                @canany(['IT Ticket Update', 'IT Ticket Delete'])
                    @if ($itTicket->status === 'Pending')
                        <div class="card-header-elements ms-auto">
                            @if ($itTicket->status === 'Pending') 
                                <a href="{{ route('administration.ticket.it_ticket.edit', ['it_ticket' => $itTicket]) }}" class="btn btn-sm btn-info me-2 confirm-info" title="Edit & Update?">
                                    <span class="tf-icon ti ti-edit ti-xs"></span>
                                    <span class="me-1">Edit</span>
                                </a>
                            @endif
                            <a href="{{ route('administration.ticket.it_ticket.mark.running', ['it_ticket' => $itTicket]) }}" class="btn btn-sm btn-primary confirm-primary" title="Start Working On This Ticket?">
                                <span class="me-1">Proceed</span>
                                <span class="tf-icon ti ti-arrow-right ti-xs"></span>
                            </a>
                        </div>
                    @elseif ($itTicket->status === 'Running')
                        <div class="card-header-elements ms-auto">
                            <button type="button" data-bs-toggle="modal" data-bs-target="#statusUpdateModal" class="btn btn-sm btn-primary" title="Mark as Solved / Canceled">
                                <span class="tf-icon ti ti-check ti-xs me-1"></span>
                                Update Ticket Status
                            </button>
                        </div>
                    @endif
                @endcanany
            </div>
            <div class="card-body">
                <div class="row justify-content-left">
                    <div class="col-md-6">
                        <div class="card card-border-shadow-primary mb-4">
                            <div class="card-body">
                                <small class="card-text text-uppercase">Information</small>
                                <dl class="row mt-3 mb-1">
                                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-hash"></i>
                                        <span class="fw-medium mx-2 text-heading">Title:</span>
                                    </dt>
                                    <dd class="col-sm-8">
                                        <span class="text-dark text-bold">{!! $itTicket->title !!}</span>
                                    </dd>
                                </dl>
                                <dl class="row mb-1">
                                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-calendar"></i>
                                        <span class="fw-medium mx-2 text-heading">Creation Date:</span>
                                    </dt>
                                    <dd class="col-sm-8">
                                        <span class="text-dark text-bold">{{ show_date($itTicket->created_at) }}</span>
                                        at
                                        <span class="text-dark text-bold">{{ show_time($itTicket->created_at) }}</span>
                                    </dd>
                                </dl>
                                <dl class="row mb-1">
                                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-chart-candle"></i>
                                        <span class="fw-medium mx-2 text-heading">Status:</span>
                                    </dt>
                                    <dd class="col-sm-8">
                                        @if ($itTicket->status === 'Pending') 
                                            <span class="badge bg-dark">{{ __('Pending') }}</span>
                                        @elseif ($itTicket->status === 'Running') 
                                            <span class="badge bg-primary">{{ __('Running') }}</span>
                                        @elseif ($itTicket->status === 'Solved') 
                                            <span class="badge bg-success">{{ __('Solved') }}</span>
                                        @else 
                                            <span class="badge bg-danger">{{ __('Canceled') }}</span>
                                        @endif
                                    </dd>
                                </dl>
                                @if ($itTicket->status === 'Solved') 
                                    <dl class="row mb-1">
                                        <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                            <i class="ti ti-user-cog"></i>
                                            <span class="fw-medium mx-2 text-heading">Solved By:</span>
                                        </dt>
                                        <dd class="col-sm-8">
                                            {!! show_user_name_and_avatar($itTicket->solver, name: null) !!}
                                        </dd>
                                    </dl>
                                    <dl class="row mb-1">
                                        <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                            <i class="ti ti-clock-check"></i>
                                            <span class="fw-medium mx-2 text-heading">Solved At:</span>
                                        </dt>
                                        <dd class="col-sm-8">
                                            <span class="text-dark">{{ show_date_time($itTicket->solved_at) }}</span>
                                        </dd>
                                    </dl>
                                @endif
                                @isset ($itTicket->solver_note) 
                                    <dl class="row mt-3 mb-1">
                                        <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                            <i class="ti ti-note"></i>
                                            <span class="fw-medium mx-2 text-heading">Note:</span>
                                        </dt>
                                        <dd class="col-sm-8">
                                            <span class="text-dark text-bold">{!! $itTicket->solver_note !!}</span>
                                        </dd>
                                    </dl>
                                @endisset
                                <dl class="row mb-1 mt-3">
                                    <dd class="col-12">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-bold text-center">Seen By</th>
                                                    <th class="text-bold text-center">Seen At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($itTicket->seen_by as $seenByAt)
                                                    <tr>
                                                        <td class="text-center">
                                                            {{ show_user_data($seenByAt['user_id'], 'name') }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ show_date_time($seenByAt['seen_at']) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card card-border-shadow-primary mb-4">
                            <div class="card-header align-items-center pb-3 pt-3">
                                <h5 class="card-action-title mb-0">Description</h5>
                            </div>
                            <div class="card-body">
                                <div class="description">
                                    {!! $itTicket->description !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End row -->

@if ($itTicket->status === 'Running') 
    {{-- Status Update Modal --}}
    @include('administration.ticket.it_ticket.modals.status_update')
@endif

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
    {{-- <!-- Vendors JS --> --}}
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function () {
            $('.bootstrap-select').each(function() {
                if (!$(this).data('bs.select')) { // Check if it's already initialized
                    $(this).selectpicker();
                }
            });

            $('.time-picker').flatpickr({
                enableTime: true,
                noCalendar: true,
            });
        });
    </script>    
@endsection
