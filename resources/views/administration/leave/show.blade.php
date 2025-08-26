@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Leave History Details'))

@section('css_links')
    {{--  External CSS  --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
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
                <h5 class="mb-0"><a href="{{ route('administration.settings.user.leave_allowed.index', ['user' => $leaveHistory->user]) }}" target="_blank" class="text-bold">{{ $leaveHistory->user->alias_name }}</a> Leave History's Details</h5>

                @canany(['Leave History Update', 'Leave History Delete'])
                    @if ($leaveHistory->status === 'Pending')
                        <div class="card-header-elements ms-auto">
                            <button type="button" data-bs-toggle="modal" data-bs-target="#approveLeaveModal" class="btn btn-sm btn-success">
                                <span class="tf-icon ti ti-check ti-xs me-1"></span>
                                Approve
                            </button>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#rejectLeaveModal" class="btn btn-sm btn-danger">
                                <span class="tf-icon ti ti-x ti-xs me-1"></span>
                                Reject
                            </button>
                        </div>
                    @endif
                    @if ($leaveHistory->status === 'Approved')
                        <div class="card-header-elements ms-auto">
                            <button type="button" data-bs-toggle="modal" data-bs-target="#cancelLeaveModal" class="btn btn-sm btn-danger">
                                <span class="tf-icon ti ti-ban ti-xs me-1"></span>
                                {{ __('Cancel Leave') }}
                            </button>
                        </div>
                    @endif
                @endcanany
            </div>
            <div class="card-body">
                <div class="row justify-content-left">
                    <div class="col-md-6">
                        @include('administration.leave.includes.leave_history_details')

                        @if ($leaveHistory->status === 'Pending')
                            @include('administration.leave.includes.available_leaves')
                        @endif
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

@if ($leaveHistory->status === 'Pending')
    {{-- Approve Modal --}}
    @include('administration.leave.modals.approve')
    {{-- Reject Modal --}}
    @include('administration.leave.modals.reject')
@endif

@if ($leaveHistory->status === 'Approved')
    {{-- Cancel Modal --}}
    @include('administration.leave.modals.cancel')
@endif

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
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

    <script>
        $(document).ready(function () {
            var fullToolbar = [
                [{ font: [] }, { size: [] }],
                ["bold", "italic", "underline", "strike"],
                [{ color: [] }, { background: [] }],
                ["link"],
                [{ header: "1" }, { header: "2" }, "blockquote"],
                [{ list: "ordered" }, { list: "bullet" }],
            ];

            var leaveRejectNoteEditor = new Quill("#leaveRejectNoteEditor", {
                bounds: "#leaveRejectNoteEditor",
                placeholder: "Ex: Completed the task.",
                modules: {
                    formula: true,
                    toolbar: fullToolbar,
                },
                theme: "snow",
            });

            // Set the editor content to the old reviewer_note if validation fails
            @if(old('reviewer_note'))
                leaveRejectNoteEditor.root.innerHTML = {!! json_encode(old('reviewer_note')) !!};
            @endif

            $('#rejectLeaveForm').on('submit', function() {
                $('#leaveRejectNoteInput').val(leaveRejectNoteEditor.root.innerHTML);
            });
        });
    </script>
@endsection
