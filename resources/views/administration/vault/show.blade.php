@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Vault Details'))

@section('css_links')
    {{--  External CSS  --}}
    {{-- <!-- Vendors CSS --> --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Credential Details') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Vault') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.vault.index') }}">{{ __('All Credentials') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Credential Details') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Credential's Details</h5>
        
                @canany(['Vault Update', 'Vault Delete'])
                    <div class="card-header-elements ms-auto">
                        <a href="{{ route('administration.vault.edit', ['vault' => $vault]) }}" class="btn btn-sm btn-primary">
                            <span class="tf-icon ti ti-edit ti-xs me-1"></span>
                            Edit Credential
                        </a>
                    </div>
                @endcanany
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <dl class="row mt-3 mb-1">
                            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                <i class="ti ti-hash text-heading"></i>
                                <span class="fw-medium mx-2 text-heading">Name:</span>
                            </dt>
                            <dd class="col-sm-8">
                                <span>{{ $vault->name }}</span>
                            </dd>
                        </dl>
                        <dl class="row mt-3 mb-1">
                            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                <i class="ti ti-world-www text-heading"></i>
                                <span class="fw-medium mx-2 text-heading">URL:</span>
                            </dt>
                            <dd class="col-sm-8">
                                <a href="{{ $vault->url }}" class="text-bold text-primary">{{ $vault->url }}</a>
                            </dd>
                        </dl>
                        <dl class="row mt-3 mb-1">
                            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                <i class="ti ti-user text-heading"></i>
                                <span class="fw-medium mx-2 text-heading">Creator:</span>
                            </dt>
                            <dd class="col-sm-8">
                                {!! show_user_name_and_avatar($vault->creator) !!}
                            </dd>
                        </dl>
                        <dl class="row mt-3 mb-1">
                            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                <i class="ti ti-calendar text-heading"></i>
                                <span class="fw-medium mx-2 text-heading">Created At:</span>
                            </dt>
                            <dd class="col-sm-8">
                                <span>{{ show_date_time($vault->created_at) }}</span>
                            </dd>
                        </dl>
                        <dl class="row mt-3 mb-1">
                            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                <i class="ti ti-note text-heading"></i>
                                <span class="fw-medium mx-2 text-heading">Note:</span>
                            </dt>
                            <dd class="col-sm-8">
                                <span>{!! $vault->note !!}</span>
                            </dd>
                        </dl>
                    </div>

                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th class="text-center" style="width: 15%;">Username</th>
                                    <td class="text-center" style="width: 50%;"><code>{{ $vault->username }}</code></td>
                                    <td class="text-center" style="width: 30%;">
                                        <button type="button" class="btn btn-outline-dark btn-xs copy-btn" title="Click to Copy" data-copy="{{ $vault->username }}">
                                            <i class="ti ti-copy"></i> Copy Username
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-center" style="width: 15%;">Password</th>
                                    <td class="text-center" style="width: 50%;"><code>{{ $vault->password }}</code></td>
                                    <td class="text-center" style="width: 30%;">
                                        <button type="button" class="btn btn-outline-dark btn-xs copy-btn" title="Click to Copy" data-copy="{{ $vault->password }}">
                                            <i class="ti ti-copy"></i> Copy Password
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Custom Script Here
        $(document).ready(function () {
            $('.table').on('click', '.copy-btn', function () {
                const button = $(this);
                const textToCopy = button.data('copy'); // Get text from data-copy attribute

                // Copy to clipboard
                navigator.clipboard.writeText(textToCopy).then(() => {
                    // Change button text to "Copied"
                    const originalText = button.html();
                    button.html('<i class="ti ti-check"></i> Copied');

                    // Revert back to original text after 5 seconds
                    setTimeout(() => {
                        button.html(originalText);
                    }, 5000);
                }).catch(err => {
                    alert('Failed to copy text: ', err);
                });
            });
        });
    </script>
@endsection
