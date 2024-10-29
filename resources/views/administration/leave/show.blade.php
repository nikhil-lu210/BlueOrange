@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Leave History Details'))

@section('css_links')
    {{--  External CSS  --}}
    {{-- <!-- Vendors CSS --> --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Leave History Details') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Leave History') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.leave.history.my') }}">{{ __('My Leave Histories') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Leave History Details') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0"><b>{{ $leaveHistory->user->name }}</b> Leave History's Details</h5>
        
                @canany(['Leave History Update', 'Leave History Delete'])
                    <div class="card-header-elements ms-auto">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#editDailyBreak" class="btn btn-sm btn-primary">
                            <span class="tf-icon ti ti-edit ti-xs me-1"></span>
                            Edit Break
                        </button>
                    </div>
                @endcanany
            </div>
            <div class="card-body">
                <div class="row justify-content-left">
                    <div class="col-md-6">
                        @include('administration.leave.includes.leave_history_details')
                    </div>

                    <div class="col-md-6">
                        @include('administration.leave.includes.leave_reason')

                        @if ($leaveHistory->files->count() > 0) 
                            @include('administration.leave.includes.leave_proof_files')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End row -->

{{-- Modal for Leave History Edit --}}
{{-- @canany(['Leave History Update', 'Leave History Delete'])
    <div class="modal fade" id="editDailyBreak" tabindex="-1" aria-hidden="true"  data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('administration.daily_break.update', ['break' => $break]) }}" method="POST" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editDailyBreakTitle">
                            <span class="ti ti-edit ti-sm me-1"></span>
                            Update Leave History
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3 col-md-4">
                                <label for="break_in_at" class="form-label">{{ __('Break Start') }} <b class="text-danger">*</b></label>
                                <input type="text" id="break_in_at" name="break_in_at" value="{{ $break->break_in_at ? get_time_only($break->break_in_at) : old('break_in_at') }}" placeholder="HH:MM:SS" class="form-control time-picker @error('break_in_at') is-invalid @enderror" required/>
                                @error('break_in_at')
                                    <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="break_out_at" class="form-label">{{ __('Break Stop') }} <b class="text-danger">*</b></label>
                                <input type="text" id="break_out_at" name="break_out_at" value="{{ $break->break_out_at ? get_time_only($break->break_out_at) : old('break_out_at') }}" placeholder="HH:MM:SS" class="form-control time-picker @error('break_out_at') is-invalid @enderror" required/>
                                @error('break_out_at')
                                    <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="type" class="form-label">{{ __('Break Type') }} <b class="text-danger">*</b></label>
                                <select name="type" id="type" class="form-select bootstrap-select w-100 @error('type') is-invalid @enderror"  data-style="btn-default" required>
                                    <option value="Short" {{ $break->type == 'Short' ? 'selected' : '' }}>{{ __('Short Break') }}</option>
                                    <option value="Long" {{ $break->type == 'Long' ? 'selected' : '' }}>{{ __('Long Break') }}</option>
                                </select>
                                @error('announcer_id')
                                    <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                            <i class="ti ti-x"></i>
                            Close
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="ti ti-check"></i>
                            Update Break
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endcanany --}}

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
    {{-- <!-- Vendors JS --> --}}
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js')}}"></script>
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
