@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Daily Work Update Details'))

@section('css_links')
    {{--  External CSS  --}}
    {{-- Select 2 --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
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
    <b class="text-uppercase">{{ __('Daily Work Update Details') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Daily Work Update') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.daily_work_update.my') }}">{{ __('My Daily Work Updates') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Daily Work Update Details') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                <div class="flex-grow-1 mt-4">
                    <div class="d-flex align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                        <div class="user-profile-info">
                            <h4 class="mb-0">
                                Work Update of
                                <span class="text-bold text-primary">{{ $dailyWorkUpdate->user->name }}</span>
                            </h4>
                            <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                <li class="list-inline-item d-flex gap-1 text-bold text-primary" data-bs-toggle="tooltip" title="Team Leader" data-bs-placement="bottom">
                                    <i class="ti ti-crown"></i>
                                    {{ $dailyWorkUpdate->team_leader->name }}
                                </li>
                                <li class="list-inline-item d-flex gap-1 text-bold" data-bs-toggle="tooltip" title="Work Update Date" data-bs-placement="bottom">
                                    <i class="ti ti-calendar"></i>
                                    {{ show_date($dailyWorkUpdate->date) }}
                                </li>
                                <li class="list-inline-item d-flex gap-1" data-bs-toggle="tooltip" title="Work Update Submitted At">
                                    <i class="ti ti-clock"></i>
                                    {{ show_date_time($dailyWorkUpdate->created_at) }}
                                </li>
                            </ul>
                        </div>
                        @can ('Daily Work Update Update')
                            @if (!$dailyWorkUpdate->rating && $dailyWorkUpdate->team_leader_id == auth()->user()->id)
                                <button type="button" class="btn btn-success btn-icon" title="Rate This Work Update?" data-bs-toggle="modal" data-bs-target="#markAsReadModal">
                                    <span class="ti ti-check"></span>
                                </button>
                            @endif
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Daily Work Update Details --}}
    <div class="col-md-7">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Daily Work Update Details</h5>

                @if ($dailyWorkUpdate->rating)
                    @php
                        switch ($dailyWorkUpdate->rating) {
                            case '1':
                                $color = 'danger';
                                break;
                            case '2':
                                $color = 'warning';
                                break;
                            case '3':
                                $color = 'dark';
                                break;
                            case '4':
                                $color = 'primary';
                                break;
                            default:
                                $color = 'success';
                                break;
                        }
                    @endphp
                    <div class="card-header-elements ms-auto">
                        <div class="btn btn-{{ $color }} btn-icon p-3" title="Rating {{ $dailyWorkUpdate->rating }} out of 5">
                            <sup class="text-bold">{{ $dailyWorkUpdate->rating }}</sup>
                            <span>/</span>
                            <sub class="text-bold">5</sub>
                        </div>
                    </div>
                @endif
            </div>

            <div class="card-body">
                <div class="work-update-description mb-3">
                    {!! $dailyWorkUpdate->work_update !!}
                </div>

                <div class="work-update-files">
                    <div class="row">
                        @foreach ($dailyWorkUpdate->files as $key => $file)
                            <div class="col-md-6 mb-4">
                                <div class="card bg-label-primary card-border-shadow-primary h-100">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div class="card-title mb-0">
                                            <h6 class="mb-0 me-2">{{ show_content($file->original_name, 20) }}</h6>
                                            <small>{{ get_file_media_size($file) }}</small>
                                        </div>
                                        <a href="{{ file_media_download($file) }}" target="_blank" class="card-icon" title="Download {{ $file->original_name }}">
                                            <span class="badge bg-primary rounded-pill p-2">
                                                <i class="ti ti-download ti-sm"></i>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Team Leader Comment --}}
    <div class="col-md-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header header-elements">
                        <h5 class="mb-0">Note / Issues</h5>
                    </div>
                    <div class="card-body">
                        {!! $dailyWorkUpdate->note !!}
                    </div>
                </div>
            </div>

            @if ($dailyWorkUpdate->comment)
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header header-elements">
                            <h5 class="mb-0">Team Leader Comment</h5>
                        </div>
                        <div class="card-body">
                            {!! $dailyWorkUpdate->comment !!}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
<!-- End row -->


{{-- Mark As Read Modal --}}
@can ('Daily Work Update Update')
    @if (!$dailyWorkUpdate->rating && $dailyWorkUpdate->team_leader_id == auth()->user()->id)
        <div class="modal fade" data-bs-backdrop="static" id="markAsReadModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content p-3">
                    <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <h3 class="role-title mb-2">Mark As Read</h3>
                            <p class="text-muted">Update the work update's status and rating</p>
                        </div>

                        <form id="workUpdateStatusForm" action="{{ route('administration.daily_work_update.update', ['daily_work_update' => $dailyWorkUpdate]) }}" method="post" enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="rating" class="form-label">Select Rating <strong class="text-danger">*</strong></label>
                                    <select name="rating" id="rating" class="form-select bootstrap-select w-100 @error('rating') is-invalid @enderror"  data-style="btn-default" required>
                                        <option value="" {{ is_null(request()->rating) ? 'selected' : '' }}>Select rating</option>
                                        <option value="1" {{ request()->rating == 1 ? 'selected' : '' }}>1</option>
                                        <option value="2" {{ request()->rating == 2 ? 'selected' : '' }}>2</option>
                                        <option value="3" {{ request()->rating == 3 ? 'selected' : '' }}>3</option>
                                        <option value="4" {{ request()->rating == 4 ? 'selected' : '' }}>4</option>
                                        <option value="5" {{ request()->rating == 5 ? 'selected' : '' }}>5</option>
                                    </select>
                                    @error('rating')
                                        <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Comment</label>
                                    <div name="comment" id="commentEditor">{!! old('comment') !!}</div>
                                    <textarea class="d-none" name="comment" id="commentEditorInput">{{ old('comment') }}</textarea>
                                    @error('comment')
                                        <b class="text-danger">{{ $message }}</b>
                                    @enderror
                                </div>

                                <div class="col-12 text-center mt-4">
                                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                    <button type="submit" class="btn btn-primary me-sm-3 me-1">
                                        <i class="ti ti-check"></i>
                                        Update Rating & Comment
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endcan
{{-- Mark As Read Modal --}}

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Custom Script Here
        $(document).ready(function() {
            $('.bootstrap-select').each(function() {
                if (!$(this).data('bs.select')) { // Check if it's already initialized
                    $(this).selectpicker();
                }
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

            var commentEditor = new Quill("#commentEditor", {
                bounds: "#commentEditor",
                placeholder: "Any Comment Regarding This Work Update...",
                modules: {
                    formula: true,
                    toolbar: fullToolbar,
                },
                theme: "snow",
            });

            // Set the editor content to the old comment if validation fails
            @if(old('comment'))
                commentEditor.root.innerHTML = {!! json_encode(old('comment')) !!};
            @endif

            $('#workUpdateStatusForm').on('submit', function() {
                $('#commentEditorInput').val(commentEditor.root.innerHTML);
            });
        });
    </script>
@endsection
