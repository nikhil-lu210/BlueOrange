@extends('layouts.administration.app')

@section('page_title', __('Monthly Team Recognitions'))

@section('css_links')
    {{--  External CSS  --}}
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
    <b class="text-uppercase">{{ __('All Monthly Team Recognitions') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Recognitions') }}</li>
    <li class="breadcrumb-item active">{{ __('All Monthly Team Recognitions') }}</li>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-4">
            <form method="GET" action="{{ route('administration.employee_recognition.index') }}" autocomplete="off">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input type="text" name="month" value="{{ $month->format('M Y') ?? old('month') }}" class="form-control month-year-picker" placeholder="MM yyyy" tabindex="-1"/>
                                    <button type="submit" name="submit_month" value="true" class="btn btn-primary">
                                        <span class="tf-icon ti ti-calendar ti-xs me-1"></span>
                                        {{ __('Load Month') }}
                                    </button>
                                    @error('month')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('Recognition Panel of ' . $month->format('F Y')) }}</h5>

                    <div class="card-header-elements ms-auto">
                        <a href="{{ route('administration.employee_recognition.leaderboard', ['month' => $month->format('Y-m-d')]) }}" target="_blank" class="btn btn-sm btn-dark">
                            <span class="tf-icon ti ti-badge me-1"></span>
                            {{ __('Leaderboard') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(!$isWindowOpen)
                        <div class="alert alert-warning">{{ __('Recognition window is from 1st to 30th of each month.') }}</div>
                    @endif

                    <form method="POST" action="{{ route('administration.employee_recognition.store') }}" class="confirm-form-warning">
                        @csrf
                        <input type="hidden" name="month" value="{{ $month->format('Y-m-d') }}">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered align-middle">
                                <thead>
                                    <tr class="bg-label-primary">
                                        <th>{{ __('Employee') }}</th>
                                        <th class="text-center">{{ __('Behavior') }}</th>
                                        <th class="text-center">{{ __('Appreciation') }}</th>
                                        <th class="text-center">{{ __('Leadership') }}</th>
                                        <th class="text-center">{{ __('Loyalty') }}</th>
                                        <th class="text-center">{{ __('Dedication') }}</th>
                                        @if ($recognitions->isNotEmpty())
                                            <th class="text-center">{{ __('Total') }}</th>
                                            <th class="text-center">{{ __('Badge') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($teamMembers as $member)
                                        <tr>
                                            <td class="d-flex align-items-center gap-2">
                                                {!! show_user_name_and_avatar($member, role: null) !!}
                                            </td>
                                            @foreach(['behavior','appreciation','leadership','loyalty','dedication'] as $category)
                                                <td class="text-center" style="width: 10%">
                                                    @php
                                                        $score = $recognitions[$member->id]->$category ?? null;
                                                        $locked = $recognitions[$member->id]->locked_at ?? null;
                                                    @endphp

                                                    @if(is_null($score) && is_null($locked))
                                                        <input
                                                            type="number"
                                                            class="form-control"
                                                            name="scores[{{ $member->id }}][{{ $category }}]"
                                                            min="0"
                                                            max="20"
                                                            placeholder="0-20"
                                                            value="{{ old("scores.$member->id.$category", $score) }}"
                                                            {{ $locked ? 'readonly' : '' }}
                                                            required
                                                        />
                                                    @else
                                                        <b>{{ $score }}</b>
                                                    @endif
                                                </td>
                                            @endforeach
                                            @if ($recognitions->isNotEmpty())
                                                <td class="text-center">
                                                    <span class="badge bg-primary">{{ isset($recognitions[$member->id]) ? $recognitions[$member->id]->total_score : '-' }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge {{ isset($badgeMap[$member->id]) ? $badgeMap[$member->id]['class'] : 'bg-secondary' }}">{{ isset($badgeMap[$member->id]) ? ($badgeMap[$member->id]['emoji'].' '.$badgeMap[$member->id]['label']) : '-' }}</span>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary" {{ $teamMembers->isEmpty() ? 'disabled' : '' }}>
                                <span class="tf-icon ti ti-lock-check me-1"></span>
                                {{ __('Save & Lock Month') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection




@section('script_links')
    {{--  External Javascript Links --}}
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Custom Script Here
        $(document).ready(function() {
            $('.month-year-picker').datepicker({
                format: 'MM yyyy',         // Display format to show full month name and year
                minViewMode: 'months',     // Only allow month selection
                todayHighlight: true,
                autoclose: true,
                orientation: 'auto right'
            });
        });
    </script>
@endsection
