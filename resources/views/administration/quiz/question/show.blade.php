@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Quiz Question Details'))

@section('css_links')
    {{--  External CSS  --}}
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
        /* Custom CSS Here */
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Quiz Question Details') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('administration.dashboard.index') }}">{{ __('Dashboard') }}</a>
    </li>
    <li class="breadcrumb-item">{{ __('Quiz') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.quiz.question.index') }}">{{ __('All Questions') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Quiz Question Details') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">
                    <span class="text-bold">{{ $question->question }}'s</span> Details
                    <small class="text-bold badge bg-{{ $question->is_active ? 'success' : 'danger' }}">{{ $question->is_active ? 'Active' : 'Inactive' }}</small>
                </h5>
            </div>
            <div class="card-body">
                <div class="row justify-content-left">
                    <div class="col-md-6">
                        @include('administration.quiz.question.includes.question_details')
                    </div>

                    <div class="col-md-6">
                        <div class="card card-action mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">{{ __('All Answers') }}</h5>
                                <h6 class="m-0 badge bg-dark">{{ $question->tests->count() }}</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>{{ __('SL') }}</th>
                                            <th>{{ __('Candidate') }}</th>
                                            <th>{{ __('Answer') }}</th>
                                            <th>{{ __('Answered At') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($question->tests as $test)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <a href="{{ route('administration.quiz.test.show', ['test' => $test]) }}" target="_blank" class="text-primary text-bold" title="Show Test Details of {{ $test->candidate_name }}">{{ $test->candidate_name }}</a>
                                                    <br>
                                                    <span class="text-muted">{{ $test->candidate_email }}</span>
                                                </td>
                                                <td>
                                                    @if ($test->pivot->selected_option)
                                                        <strong class="text-{{ $test->pivot->is_correct ? 'success' : 'danger' }}">{{ $test->pivot->selected_option }}</strong>
                                                    @else
                                                        <span class="text-muted">Not Answered</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($test->pivot->answered_at)
                                                        {{ get_date_only($test->pivot->answered_at) }}
                                                        <br>
                                                        at {{ show_time($test->pivot->answered_at) }}
                                                    @else
                                                        {{ show_status($test->status) }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">
                                                    <span class="text-muted">{{ __('No Answers Found') }}</span>
                                                </td>
                                            </tr>
                                        @endforelse
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
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function () {
            //
        });
    </script>
@endsection
