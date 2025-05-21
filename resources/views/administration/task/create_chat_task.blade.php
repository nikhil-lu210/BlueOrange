@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Create New Task From Chatting'))

@section('css_links')
    {{--  External CSS  --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />

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
    <b class="text-uppercase">{{ __('Create New Task From Chatting') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Tasks') }}</li>
    <li class="breadcrumb-item active">{{ __('Create New Task From Chatting') }}</li>
@endsection

@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Create New Task From Chatting</h5>

                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.task.index') }}" class="btn btn-sm btn-primary">
                        <span class="tf-icon ti ti-circle ti-xs me-1"></span>
                        All Tasks
                    </a>
                </div>
            </div>
            <!-- Account -->
            <div class="card-body">
                <form id="taskForm" action="{{ route('administration.task.store') }}" method="post" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <input type="hidden" name="chatting_id" value="{{ $message->id }}" required>
                    <div class="row">
                        <div class="mb-3 col-md-8">
                            <label for="title" class="form-label">{{ __('Title') }} <strong class="text-danger">*</strong></label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="{{ __('Title') }}" class="form-control @error('title') is-invalid @enderror" required/>
                            @error('title')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">Deadline</label>
                            <input type="text" name="deadline" value="{{ old('deadline') }}" class="form-control  date-picker" placeholder="YYYY-MM-DD" tabindex="-1"/>
                            @error('deadline')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            @php
                                $chatTaskUserId = null;
                                if ($message->sender_id != auth()->user()->id) {
                                    $chatTaskUserId = $message->sender_id;
                                } else {
                                    $chatTaskUserId = $message->receiver_id;
                                }
                            @endphp
                            <label for="users" class="form-label">Select Users <strong class="text-danger">*</strong></label>
                            <select name="users[]" id="users" class="select2 form-select @error('users') is-invalid @enderror" data-allow-clear="true" multiple required>
                                <option value="selectAllValues">Select All</option>
                                @foreach ($roles as $role)
                                    <optgroup label="{{ $role->name }}">
                                        @foreach ($role->users as $user)
                                            <option
                                                value="{{ $user->id }}"
                                                {{ in_array($user->id, old('users', [$chatTaskUserId])) ? 'selected' : '' }}>
                                                {{ get_employee_name($user) }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            @error('users')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="priority" class="form-label">Select Priority <strong class="text-danger">*</strong></label>
                            <div class="row">
                                <div class="col-md mb-md-0 mb-2">
                                    <div class="form-check custom-option custom-option-basic">
                                        <label class="form-check-label custom-option-content" for="priorityLow">
                                            <input name="priority" class="form-check-input" type="radio" value="Low" id="priorityLow" checked />
                                            <span class="custom-option-header pb-0">
                                                <span class="h6 mb-0">Low</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-check custom-option custom-option-basic">
                                        <label class="form-check-label custom-option-content" for="priorityAverage">
                                            <input name="priority" class="form-check-input" type="radio" value="Average" id="priorityAverage" />
                                            <span class="custom-option-header pb-0">
                                                <span class="h6 mb-0">Average</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-check custom-option custom-option-basic">
                                        <label class="form-check-label custom-option-content" for="priorityMedium">
                                            <input name="priority" class="form-check-input" type="radio" value="Medium" id="priorityMedium" />
                                            <span class="custom-option-header pb-0">
                                                <span class="h6 mb-0">Medium</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-check custom-option custom-option-basic">
                                        <label class="form-check-label custom-option-content" for="priorityHigh">
                                            <input name="priority" class="form-check-input" type="radio" value="High" id="priorityHigh" />
                                            <span class="custom-option-header pb-0">
                                                <span class="h6 mb-0">High</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 col-md-12">
                            <label class="form-label">Task Description <strong class="text-danger">*</strong></label>
                            <div name="description" id="full-editor">{!! old('description', $message->message) !!}</div>
                            <textarea class="d-none" name="description" id="description-input">{{ old('description', $message->message) }}</textarea>
                            @error('description')
                                <b class="text-danger">{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="files[]" class="form-label">{{ __('Task Files') }}</label>
                            <input type="file" id="files[]" name="files[]" value="{{ old('files[]') }}" placeholder="{{ __('Task Files') }}" class="form-control @error('files[]') is-invalid @enderror" multiple/>
                            @error('files[]')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-2 float-end">
                        <a href="{{ route('administration.task.create') }}" class="btn btn-outline-danger me-2 confirm-danger">Reset Form</a>
                        <button type="submit" class="btn btn-primary">Create Task</button>
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
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>
    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Custom Script Here
        $(document).ready(function() {
            $('.date-picker').datepicker({
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                autoclose: true,
                orientation: 'auto right'
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
                [{ header: "1" }, { header: "2" }, "blockquote", "code-block"],
                [{ list: "ordered" }, { list: "bullet" }, { indent: "-1" }, { indent: "+1" }],
            ];

            var fullEditor = new Quill("#full-editor", {
                bounds: "#full-editor",
                placeholder: "Ex: Mr. John Doe got promoted as Manager",
                modules: {
                    formula: true,
                    toolbar: fullToolbar,
                },
                theme: "snow",
            });

            // Set the editor content to the old description if validation fails
            @if(old('description'))
                fullEditor.root.innerHTML = {!! json_encode(old('description')) !!};
            @endif

            $('#taskForm').on('submit', function() {
                $('#description-input').val(fullEditor.root.innerHTML);
            });
        });
    </script>
@endsection
