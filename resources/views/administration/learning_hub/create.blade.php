@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Create New Topic'))

@section('css_links')
    {{--  External CSS  --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
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
    <b class="text-uppercase">{{ __('Create New Topic') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Learning Hub') }}</li>
    <li class="breadcrumb-item active">{{ __('Create New Topic') }}</li>
@endsection

@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Create New Topic</h5>

                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.learning_hub.index') }}" class="btn btn-sm btn-primary">
                        <span class="tf-icon ti ti-circle ti-xs me-1"></span>
                        All Topics
                    </a>
                </div>
            </div>
            <!-- Account -->
            <div class="card-body">
                <form id="learningHubForm" class="learning-hub-form" action="{{ route('administration.learning_hub.store') }}" method="post" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label for="title" class="form-label">{{ __('Title') }} <strong class="text-danger">*</strong></label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="{{ __('Title') }}" class="form-control @error('title') is-invalid @enderror" required/>
                            @error('title')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label class="form-label">Description <strong class="text-danger">*</strong></label>
                            <div name="description" id="full-editor">{!! old('description') !!}</div>
                            <textarea class="d-none" name="description" id="description-input">{{ old('description') }}</textarea>
                            @error('description')
                                <b class="text-danger">{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="files[]" class="form-label">{{ __('File(s)') }}</label>
                            <input type="file" id="files[]" name="files[]" value="{{ old('files[]') }}" placeholder="{{ __('File(s)') }}" class="form-control @error('files[]') is-invalid @enderror" multiple/>
                            @error('files[]')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="recipients" class="form-label">Select Recipients</label>
                            <select name="recipients[]" id="recipients" class="select2 form-select @error('recipients') is-invalid @enderror" data-allow-clear="true" multiple autofocus>
                                <option value="selectAllValues">Select All</option>
                                @foreach ($roles as $role)
                                    <optgroup label="{{ $role->name }}">
                                        @foreach ($role->users as $user)
                                            <option value="{{ $user->id }}" {{ in_array($user->id, old('recipients', [])) ? 'selected' : '' }}>
                                                {{ get_employee_name($user) }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <small><b class="text-primary">Note:</b> If the topic is for all users, then don't select any Recipient.</small>
                            <br>
                            @error('recipients')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-2 float-end">
                        <a href="{{ route('administration.learning_hub.create') }}" class="btn btn-outline-danger me-2 confirm-danger">Reset Form</a>
                        <button type="submit" class="btn btn-primary">Create Topic</button>
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
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
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

            $('#learningHubForm').on('submit', function() {
                $('#description-input').val(fullEditor.root.innerHTML);

                // Ensure selectAllValues is removed from recipients before submission
                var recipientsSelect = $('#recipients');
                var selectedValues = recipientsSelect.val() || [];
                if (selectedValues.includes('selectAllValues')) {
                    recipientsSelect.val(selectedValues.filter(val => val !== 'selectAllValues'));
                }
            });
        });
    </script>
@endsection
