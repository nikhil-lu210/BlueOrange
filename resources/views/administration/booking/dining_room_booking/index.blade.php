@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Dining Room Booking'))

@section('css_links')
    {{--  External CSS  --}}
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
        .custom-option {
            border-radius: 0px !important;
        }
        .btn-block {
            width: 100% !important;
        }
        .time-btn {
            padding: 25px 0px;
        }
        .col-disabled {
            cursor: not-allowed !important;
        }
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Book Dining Room') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Booking') }}</li>
    <li class="breadcrumb-item active">{{ __('Dining Room Booking') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center mt-5">
    <div class="col-md-7">
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <h5 class="card-header">
                        Book Dining Room For Your Shift
                        (<b class="text-primary">{{ show_time(auth()->user()->current_shift->start_time, 'h:i A'). ' to ' . show_time(auth()->user()->current_shift->end_time, 'h:i A') }}</b>)
                    </h5>
                    <div class="card-body">
                        <form action="{{ route('administration.booking.dining_room.book') }}" method="post">
                            @csrf
                            <div class="row">
                                @foreach ($availableTimeSlots as $index => $slot)
                                    @php
                                        $time = $slot['time'] ?? $slot;
                                        $disabled = $slot['disabled'] ?? false;
                                        $userHasBooking = $slot['user_has_booking'] ?? false;
                                        $slotDateTime = \Carbon\Carbon::parse($time);  // Convert slot time to Carbon instance
                                    @endphp

                                    @if ($slotDateTime->gt(now()))  <!-- Only display button if the time is in the future -->
                                        <div class="col-md-3 mb-2 {{ $disabled ? 'col-disabled' : '' }}">
                                            <button
                                                class="btn {{ $userHasBooking ? 'btn-success' : ($disabled ? 'btn-outline-dark' : 'btn-outline-success') }} btn-block btn-lg time-btn mb-3"
                                                name="booking_time"
                                                value="{{ $time }}"
                                                @disabled($disabled)
                                                title="{{ $userHasBooking ? 'Cancel Your Booking?' : ($disabled ? 'You Cannot Book' : 'Book For '.show_time($time, 'h:i A')) }}"
                                                type="{{ $userHasBooking ? 'submit' : ($disabled ? 'button' : 'submit') }}"
                                            >
                                                {{ show_time($time, 'h:i A') }}
                                            </button>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card">
            <div class="card-header header-elements">
                <h5 class="mb-0">Bookings Of <b class="text-bold text-primary">{{ date('F Y') }}</b></h5>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-label-primary">
                            <tr>
                                <th class="text-left">Employee</th>
                                <th class="text-center">Booking Time</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bookings as $key => $booking)
                                <tr>
                                    <td class="text-left">
                                        @if ($booking->user)
                                            {!! show_user_name_and_avatar($booking->user, name: null, role: null) !!}
                                        @endif
                                    </td>
                                    <td class="text-center">{{ show_time($booking->booking_time, 'h:i A') }}</td>
                                    <td class="text-center">{!! show_status($booking->status) !!}</td>
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


{{-- Page Modal --}}
@endsection


@section('script_links')
    {{--  External Javascript Links --}}
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        //
    </script>
@endsection
