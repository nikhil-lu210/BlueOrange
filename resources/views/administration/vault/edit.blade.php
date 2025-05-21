@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Edit Credential'))

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
    <b class="text-uppercase">{{ __('Edit Credential') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Vault') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.vault.index') }}">{{ __('All Credentials') }}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.vault.show', ['vault' => $vault]) }}">{{ $vault->name }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Edit Credential') }}</li>
@endsection

@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Edit Credential</h5>

                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.vault.index') }}" class="btn btn-sm btn-primary">
                        <span class="tf-icon ti ti-circle ti-xs me-1"></span>
                        All Credentials
                    </a>
                </div>
            </div>
            <!-- Account -->
            <div class="card-body">
                <form id="vaultForm" action="{{ route('administration.vault.update', ['vault' => $vault]) }}" method="post" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label for="name" class="form-label">{{ __('Name') }} <strong class="text-danger">*</strong></label>
                            <input type="text" id="name" name="name" value="{{ old('name', $vault->name) }}" placeholder="{{ __('Ex: Nigel Web Mail') }}" class="form-control @error('name') is-invalid @enderror" required/>
                            @error('name')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="url" class="form-label">{{ __('Login URL') }}</label>
                            <input type="url" id="url" name="url" value="{{ old('url', $vault->url) }}" placeholder="{{ __('Ex: https://blueorange.test/login') }}" class="form-control @error('url') is-invalid @enderror"/>
                            @error('url')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="username" class="form-label">{{ __('Email / Username') }} <strong class="text-danger">*</strong></label>
                            <input type="text" id="username" name="username" value="{{ old('username', $vault->username) }}" placeholder="{{ __('Ex: https://blueorange.test/login') }}" class="form-control @error('username') is-invalid @enderror" autocomplete="off" required/>
                            @error('username')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6 form-password-toggle">
                            <label class="form-label" for="password">{{ __('Password') }} <strong class="text-danger">*</strong></label>
                            <div class="input-group input-group-merge">
                                <input type="password" minlength="8" id="password" name="password" value="{{ old('password', $vault->password) }}" class="form-control @error('password') is-invalid @enderror" placeholder="**********" autocomplete="off" required />
                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                            @error('password')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label class="form-label">Note <strong class="text-danger">*</strong></label>
                            <div name="note" id="full-editor">{!! old('note', $vault->note) !!}</div>
                            <textarea class="d-none" name="note" id="note-input">{{ old('note', $vault->note) }}</textarea>
                            @error('note')
                                <b class="text-danger">{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="viewers" class="form-label">Select Viewers</label>
                            <select name="viewers[]" id="viewers" class="select2 form-select @error('viewers') is-invalid @enderror" data-allow-clear="true" multiple autofocus>
                                <option value="selectAllValues">Select All</option>
                                @foreach ($roles as $role)
                                    <optgroup label="{{ $role->name }}">
                                        @foreach ($role->users as $user)
                                            <option value="{{ $user->id }}" {{ in_array($user->id, old('viewers', $vault->viewers->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}>
                                                {{ get_employee_name($user) }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <small>
                                <b class="text-primary">Note:</b>
                                If you want to share this credential with any other users, then select them.
                            </small>
                            <br>
                            @error('viewers')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-2 float-end">
                        <a href="{{ route('administration.vault.edit', ['vault' => $vault]) }}" class="btn btn-outline-danger me-2 confirm-danger">Reset Form</a>
                        <button type="submit" class="btn btn-primary">Update Credential</button>
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
                placeholder: "Ex: Credentials is to secret",
                modules: {
                    formula: true,
                    toolbar: fullToolbar,
                },
                theme: "snow",
            });

            // Set the editor content to the old note if validation fails
            @if(old('note'))
                fullEditor.root.innerHTML = {!! json_encode(old('note')) !!};
            @endif

            $('#vaultForm').on('submit', function() {
                $('#note-input').val(fullEditor.root.innerHTML);
            });
        });
    </script>
@endsection
