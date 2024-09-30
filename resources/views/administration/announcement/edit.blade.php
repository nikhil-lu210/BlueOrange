@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Edit & Update Announcement'))

@section('css_links')
    {{--  External CSS  --}}
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/typography.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/katex.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/editor.css')}}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('Edit & Update Announcement') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Announcement') }}</li>
    @canany(['Announcement Create', 'Announcement Update'])
        <li class="breadcrumb-item">
            <a href="{{ route('administration.announcement.index') }}">{{ __('All Announcements') }}</a>
        </li>
    @endcanany
    <li class="breadcrumb-item">
        <a href="{{ route('administration.announcement.show', ['announcement' => $announcement]) }}">{{ __('Announcement Details') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Edit & Update Announcement') }}</li>
@endsection

@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Edit & Update Announcement</h5>
        
                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.announcement.show', ['announcement' => $announcement]) }}" class="btn btn-sm btn-dark">
                        <span class="tf-icon ti ti-arrow-left ti-xs me-1"></span>
                        Back To Details
                    </a>
                </div>
            </div>
            <!-- Account -->
            <div class="card-body">
                <form id="announcementForm" action="{{ route('administration.announcement.update', ['announcement' => $announcement]) }}" method="post" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label for="title" class="form-label">{{ __('Title') }} <strong class="text-danger">*</strong></label>
                            <input type="text" id="title" name="title" value="{{ old('title', $announcement->title) }}" placeholder="{{ __('Title') }}" class="form-control @error('title') is-invalid @enderror" required/>
                            @error('title')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label class="form-label">Description <strong class="text-danger">*</strong></label>
                            <div name="description" id="full-editor">{!! old('description', $announcement->description) !!}</div>
                            <textarea class="d-none" name="description" id="description-input">{{ old('description', $announcement->description) }}</textarea>
                            @error('description')
                                <b class="text-danger">{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="recipients" class="form-label">Select Recipients</label>
                            <select name="recipients[]" id="recipients" class="select2 form-select @error('recipients') is-invalid @enderror" data-allow-clear="true" multiple autofocus>
                                @foreach ($roles as $role)
                                    <optgroup label="{{ $role->name }}">
                                        @foreach ($role->users as $user)
                                            <option value="{{ $user->id }}" {{ in_array($user->id, old('recipients', $announcement->recipients ?? [])) ? 'selected' : '' }}>
                                                {{ get_employee_name($user) }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <small><b class="text-primary">Note:</b> If the announcement is for all users, then don't select any Recipient.</small>
                            <br>
                            @error('recipients')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-2 float-end">
                        <button type="submit" class="btn btn-primary confirm-form-success">
                            <i class="tf-icon ti ti-check ti-xs me-1"></i>
                            Update Announcement
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
    <script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
    <script src="{{asset('assets/js/form-layouts.js')}}"></script>
    <!-- Vendors JS -->
    <script src="{{asset('assets/vendor/libs/quill/katex.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/quill/quill.js')}}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function () {
            var fullToolbar = [
                [{ font: [] }, { size: [] }],
                ["bold", "italic", "underline", "strike"],
                [{ color: [] }, { background: [] }],
                [{ script: "super" }, { script: "sub" }],
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

            $('#announcementForm').on('submit', function() {
                $('#description-input').val(fullEditor.root.innerHTML);
            });
        });
    </script>
@endsection
