@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('New Daily Work Update'))

@section('css_links')
    {{--  External CSS  --}}
    {{-- Bootstrap Datepicker --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
        /* Custom CSS Here */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            margin: 0;
        }
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('New Daily Work Update') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Daily Work Update') }}</li>
    <li class="breadcrumb-item active">{{ __('Submit New Work Update') }}</li>
@endsection

@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">{{ __('Submit Daily Work Update') }}</h5>

                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.daily_work_update.my') }}" class="btn btn-sm btn-primary">
                        <span class="tf-icon ti ti-circle ti-xs me-1"></span>
                        My Work Updates
                    </a>
                </div>
            </div>
            <!-- Account -->
            <div class="card-body">
                <form id="workUpdateForm" action="{{ route('administration.daily_work_update.store') }}" method="post" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="row justify-content-center">
                        <div class="mb-3 col-md-3">
                            <label class="form-label">Work Update Date <strong class="text-danger">*</strong></label>
                            <input type="text" name="date" value="{{ old('date', date('Y-m-d')) }}" class="form-control date-picker" placeholder="YYYY-MM-DD" required/>
                            @error('date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-2">
                            <label class="form-label">Work Progress <strong class="text-danger">*</strong></label>
                            <div class="input-group input-group-merge">
                                <input type="number" min="0" max="100" name="progress" value="{{ old('progress') }}" placeholder="50" class="form-control" required>
                                <span class="input-group-text"><i class="ti ti-percentage"></i></span>
                            </div>
                            @error('progress')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-7">
                            <label for="files[]" class="form-label">{{ __('Files') }}</label>
                            <input type="file" id="files[]" name="files[]" value="{{ old('files[]') }}" placeholder="{{ __('Files') }}" class="form-control @error('files[]') is-invalid @enderror" multiple/>
                            @error('files[]')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Work Update Description <strong class="text-danger">*</strong></label>
                            <div name="work_update" id="workUpdateEditor">{!! old('work_update') !!}</div>
                            <textarea class="d-none" name="work_update" id="workUpdateInput">{{ old('work_update') }}</textarea>
                            @error('work_update')
                                <b class="text-danger">{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Note / Issue</label>
                            <div name="note_issue" id="noteIssueEditor">{!! old('note_issue') !!}</div>
                            <textarea class="d-none" name="note_issue" id="noteIssueInput">{{ old('note_issue') }}</textarea>
                            @error('note_issue')
                                <b class="text-danger">{{ $message }}</b>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-2 float-end">
                        <a href="{{ route('administration.daily_work_update.create') }}" class="btn btn-outline-danger me-2 confirm-danger">Reset Form</a>
                        <button type="submit" class="btn btn-primary">Submit Work Update</button>
                    </div>
                </form>
            </div>
            <!-- /Account -->
        </div>
    </div>
</div>
<!-- End row -->

@endsection

@section('script_links')
    {{--  External Javascript Links --}}
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}"></script>

    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>
    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $('.date-picker').datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight: true,
            autoclose: true,
            orientation: 'auto right'
        });
    </script>
    <script>
        $(document).ready(function () {
            var fullToolbar = [
                [{ font: [] }, { size: [] }],
                ["bold", "italic", "underline", "strike"],
                [{ color: [] }, { background: [] }],
                ["link"],
                [{ header: "1" }, { header: "2" }, "blockquote", "code-block"],
                [{ list: "ordered" }, { list: "bullet" }, { indent: "-1" }, { indent: "+1" }],
            ];

            var workUpdateEditor = new Quill("#workUpdateEditor", {
                bounds: "#workUpdateEditor",
                placeholder: "Your Work Update Description Here...",
                modules: {
                    formula: true,
                    toolbar: fullToolbar,
                },
                theme: "snow",
            });

            // Set the editor content to the old work_update if validation fails
            @if(old('work_update'))
                workUpdateEditor.root.innerHTML = {!! json_encode(old('work_update')) !!};
            @endif

            var noteIssueEditor = new Quill("#noteIssueEditor", {
                bounds: "#noteIssueEditor",
                placeholder: "Any Note or Issues you have Faced during your shift...",
                modules: {
                    formula: true,
                    toolbar: fullToolbar,
                },
                theme: "snow",
            });

            // Set the editor content to the old note_issue if validation fails
            @if(old('note_issue'))
                noteIssueEditor.root.innerHTML = {!! json_encode(old('note_issue')) !!};
            @endif

            $('#workUpdateForm').on('submit', function() {
                $('#workUpdateInput').val(workUpdateEditor.root.innerHTML);
                $('#noteIssueInput').val(noteIssueEditor.root.innerHTML);
            });
        });
    </script>
@endsection
