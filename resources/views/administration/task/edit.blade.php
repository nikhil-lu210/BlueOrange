@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Edit & Update Task'))

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
    <b class="text-uppercase">{{ __('Edit & Update Task') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Task') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.task.index') }}">{{ __('All Tasks') }}</a>
    </li>
    <li class="breadcrumb-item">{{ __('Task Details') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.task.show', ['task' => $task, 'taskid' => $task->taskid]) }}" class="text-bold">{{ $task->taskid }}</a>
    </li>
    <li class="breadcrumb-item">{{ __('Edit & Update Task') }}</li>
@endsection

@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Edit & Update Task</h5>

                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.task.show', ['task' => $task, 'taskid' => $task->taskid]) }}" class="btn btn-sm btn-dark">
                        <span class="tf-icon ti ti-arrow-left ti-xs me-1"></span>
                        Back To Details
                    </a>
                </div>
            </div>
            <!-- Account -->
            <div class="card-body">
                <form id="taskForm" action="{{ route('administration.task.update', ['task' => $task]) }}" method="post" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="mb-3 col-md-8">
                            <label for="title" class="form-label">{{ __('Title') }} <strong class="text-danger">*</strong></label>
                            <input type="text" id="title" name="title" value="{{ old('title', $task->title) }}" placeholder="{{ __('Title') }}" class="form-control" required/>
                            @error('title')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">Deadline</label>
                            <input type="text" name="deadline" value="{{ old('deadline', $task->deadline) }}" class="form-control date-picker" placeholder="YYYY-MM-DD" tabindex="-1"/>
                            @error('deadline')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="priority" class="form-label">Select Priority <strong class="text-danger">*</strong></label>
                            <div class="row">
                                <div class="col-md mb-md-0 mb-2">
                                    <div class="form-check custom-option custom-option-basic">
                                        <label class="form-check-label custom-option-content" for="priorityLow">
                                            <input name="priority" class="form-check-input" type="radio" value="Low" id="priorityLow" {{ old('priority', $task->priority) == 'Low' ? 'checked' : '' }} />
                                            <span class="custom-option-header pb-0">
                                                <span class="h6 mb-0">Low</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-check custom-option custom-option-basic">
                                        <label class="form-check-label custom-option-content" for="priorityAverage">
                                            <input name="priority" class="form-check-input" type="radio" value="Average" id="priorityAverage" {{ old('priority', $task->priority) == 'Average' ? 'checked' : '' }} />
                                            <span class="custom-option-header pb-0">
                                                <span class="h6 mb-0">Average</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-check custom-option custom-option-basic">
                                        <label class="form-check-label custom-option-content" for="priorityMedium">
                                            <input name="priority" class="form-check-input" type="radio" value="Medium" id="priorityMedium" {{ old('priority', $task->priority) == 'Medium' ? 'checked' : '' }} />
                                            <span class="custom-option-header pb-0">
                                                <span class="h6 mb-0">Medium</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-check custom-option custom-option-basic">
                                        <label class="form-check-label custom-option-content" for="priorityHigh">
                                            <input name="priority" class="form-check-input" type="radio" value="High" id="priorityHigh" {{ old('priority', $task->priority) == 'High' ? 'checked' : '' }} />
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
                            <div name="description" id="full-editor">{!! old('description', $task->description) !!}</div>
                            <textarea class="d-none" name="description" id="description-input">{{ old('description', $task->description) }}</textarea>
                            @error('description')
                                <b class="text-danger">{{ $message }}</b>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-2 float-end">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">
                            <i class="ti ti-check"></i>
                            Update Task
                        </button>
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
