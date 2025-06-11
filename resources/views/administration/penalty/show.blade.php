@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Penalty Details'))

@section('css_links')
    {{--  External CSS  --}}

    {{-- Lightbox CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" integrity="sha512-ZKX+BvQihRJPA8CROKBhDNvoc2aDMOdAlcm7TUQY+35XYtrd3yh95QOOhsPDQY9QnKE0Wqag9y38OIgEvb88cA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
    <b class="text-uppercase">{{ __('Penalty Details') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Penalty') }}</li>
    <li class="breadcrumb-item">
        @canany(['Penalty Everything', 'Penalty Update', 'Penalty Delete'])
            <a href="{{ route('administration.penalty.index') }}">{{ __('All Penalties') }}</a>
        @elsecanany(['Penalty Read'])
            <a href="{{ route('administration.penalty.my') }}">{{ __('My Penalties') }}</a>
        @endcan
    </li>
    <li class="breadcrumb-item active">{{ __('Penalty Details') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">
                    <span class="text-bold">{{ $penalty->user->alias_name }}'s</span> Penalty Details
                </h5>
            </div>
            <div class="card-body">
                <div class="row justify-content-left">
                    <div class="col-md-6">
                        @include('administration.penalty.includes.penalty_details')
                    </div>

                    <div class="col-md-6">
                        <div class="card card-action mb-4">
                            <div class="card-header align-items-center pb-3 pt-3">
                                <h5 class="card-action-title mb-0">Penalty Reason</h5>
                            </div>
                            <div class="card-body">
                                <div class="penalty-reason">
                                    {!! $penalty->reason !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($penalty->files->count() > 0)
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                                @include('administration.penalty.includes.penalty_proof_files')
                            </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- End row -->

@endsection


@section('script_links')
    {{--  External Javascript Links --}}

    {{-- Lightbox JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js" integrity="sha512-Ixzuzfxv1EqafeQlTCufWfaC6ful6WFqIz4G+dWvK0beHw0NVJwvCKSgafpy5gwNqKmgUfIBraVwkKI+Cz0SEQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
