@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Quiz Test Details'))

@section('css_links')
    {{--  External CSS  --}}

    {{-- Lightbox CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
        /* Custom CSS Here */
        .img-thumbnail {
            padding: 3px;
            border: 3px solid var(--bs-border-color);
            border-radius: 5px;
        }
        .file-thumbnail-container {
            width: 150px;
            height: 100px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
        }
        .file-thumbnail-container .file-name {
            max-width: 140px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Quiz Test Details') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Quiz') }}</li>
    <li class="breadcrumb-item">{{ __('Quiz Tests') }}</li>
    <li class="breadcrumb-item">
        @canany(['Quiz Everything', 'Quiz Update', 'Quiz Delete'])
            <a href="{{ route('administration.quiz.test.index') }}">{{ __('All Tests') }}</a>
        @endcan
    </li>
    <li class="breadcrumb-item active">{{ __('Quiz Test Details') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">
                    <span class="text-bold">{{ $test->candidate_name }}'s</span> Quiz Test Details
                </h5>
            </div>
            <div class="card-body">
                <div class="row justify-content-left">
                    <div class="col-md-6">
                        @include('administration.quiz.test.includes.test_info')
                    </div>

                    <div class="col-md-6">
                        <div class="card card-action mb-4">
                            <div class="card-header align-items-center pb-3 pt-3">
                                <h5 class="card-action-title mb-0">Question And Answers</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>{{ __('SL') }}</th>
                                            <th>{{ __('Question') }}</th>
                                            <th>{{ __('Answer') }}</th>
                                            <th>{{ __('Is Correct') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @forelse ($test->answers as $answer)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $answer->question->question }}</td>
                                                <td>{{ $answer->selected_option }}</td>
                                                <td>
                                                    @if ($answer->is_correct)
                                                        <span class="badge bg-success">{{ __('Yes') }}</span>
                                                    @else
                                                        <span class="badge bg-danger">{{ __('No') }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">
                                                    <span class="text-muted">{{ __('No Answers Found') }}</span>
                                                </td>
                                            </tr>
                                        @endforelse --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End row -->

@endsection


@section('script_links')
    {{--  External Javascript Links --}}

    {{-- Lightbox JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function () {
            // Lightbox configuration
            lightbox.option({
                'resizeDuration': 200,
                'wrapAround': true,
                'albumLabel': "Image %1 of %2"
            });
        });
    </script>
@endsection
