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
    .btn-block {
        width: 100%;
    }
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
                <h5 class="mb-0">
                    <b class="text-primary">{{ $itTicket->creator->employee->alias_name. '\'s ' }}</b>
                    {{ __('IT Ticket Details') }}
                </h5>

                @canany(['IT Ticket Update', 'IT Ticket Delete'])
                    @if ($itTicket->status === 'Pending')
                        <div class="card-header-elements ms-auto">
                            @if ($itTicket->status === 'Pending' && $itTicket->creator_id == auth()->user()->id)
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
                        @include('administration.ticket.it_ticket.partials._information')
                    </div>

                    <div class="col-md-6">
                        @include('administration.ticket.it_ticket.partials._description')

                        @isset ($itTicket->solver_note)
                            @include('administration.ticket.it_ticket.partials._note')
                        @endisset

                        @include('administration.ticket.it_ticket.partials._comment')
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
