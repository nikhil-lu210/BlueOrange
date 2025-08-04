@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('My Evaluations'))

@section('css_links')
    {{--  External CSS  --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
        .evaluation-card {
            transition: all 0.3s ease;
            border-left: 4px solid #e9ecef;
        }
        .evaluation-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 25px 0 rgba(0, 0, 0, 0.1);
        }
        .evaluation-card.status-pending {
            border-left-color: #6c757d;
        }
        .evaluation-card.status-in-progress {
            border-left-color: #ffc107;
        }
        .evaluation-card.status-completed {
            border-left-color: #17a2b8;
        }
        .evaluation-card.status-passed {
            border-left-color: #28a745;
        }
        .evaluation-card.status-failed {
            border-left-color: #dc3545;
        }
        .stage-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('My Evaluations') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('administration.dashboard.index') }}">{{ __('Dashboard') }}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.hiring.index') }}">{{ __('Employee Hiring') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('My Evaluations') }}</li>
@endsection

@section('content')

<!-- Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0">{{ __('My Assigned Evaluations') }}</h4>
                <p class="text-muted mb-0">{{ __('Candidates assigned to you for evaluation') }}</p>
            </div>
            <div>
                <a href="{{ route('administration.hiring.index') }}" class="btn btn-outline-primary">
                    <i class="ti ti-arrow-left"></i> {{ __('Back to All Candidates') }}
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Evaluations List -->
<div class="row">
    @forelse($evaluations as $evaluation)
        <div class="col-xl-6 col-lg-8 col-md-12 mb-4">
            <div class="card evaluation-card h-100 status-{{ $evaluation->status }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="card-title mb-1">{{ $evaluation->candidate->name }}</h5>
                            <p class="text-muted mb-0">{{ $evaluation->candidate->expected_role }}</p>
                        </div>
                        <div class="text-end">
                            <span class="badge stage-badge bg-primary">
                                {{ $evaluation->stage->name }}
                            </span>
                            <br>
                            <span class="badge {{ $evaluation->status_badge_class }} mt-1">
                                {{ $evaluation->status_formatted }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="row g-2">
                            <div class="col-6">
                                <small class="text-muted d-block">{{ __('Candidate Email') }}</small>
                                <strong>{{ $evaluation->candidate->email }}</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">{{ __('Phone') }}</small>
                                <strong>{{ $evaluation->candidate->phone }}</strong>
                            </div>
                            @if($evaluation->candidate->expected_salary)
                                <div class="col-6">
                                    <small class="text-muted d-block">{{ __('Expected Salary') }}</small>
                                    <strong>{{ $evaluation->candidate->expected_salary_formatted }}</strong>
                                </div>
                            @endif
                            @if($evaluation->rating)
                                <div class="col-6">
                                    <small class="text-muted d-block">{{ __('My Rating') }}</small>
                                    <strong>{{ $evaluation->rating }}/10</strong>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($evaluation->notes || $evaluation->feedback)
                        <div class="mb-3">
                            @if($evaluation->notes)
                                <div class="mb-2">
                                    <small class="text-muted d-block">{{ __('My Notes') }}</small>
                                    <p class="mb-0 small">{{ Str::limit($evaluation->notes, 100) }}</p>
                                </div>
                            @endif
                            @if($evaluation->feedback)
                                <div class="mb-2">
                                    <small class="text-muted d-block">{{ __('My Feedback') }}</small>
                                    <p class="mb-0 small">{{ Str::limit($evaluation->feedback, 100) }}</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="mb-3">
                        <div class="row g-2">
                            @if($evaluation->assigned_at)
                                <div class="col-6">
                                    <small class="text-muted d-block">{{ __('Assigned') }}</small>
                                    <strong>{{ $evaluation->assigned_at->format('M d, Y') }}</strong>
                                </div>
                            @endif
                            @if($evaluation->completed_at)
                                <div class="col-6">
                                    <small class="text-muted d-block">{{ __('Completed') }}</small>
                                    <strong>{{ $evaluation->completed_at->format('M d, Y') }}</strong>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            {{ __('Added by') }}: {{ $evaluation->candidate->creator->name ?? 'Unknown' }}
                        </small>
                        <div class="d-flex gap-2">
                            @if($evaluation->status === 'pending')
                                <button type="button"
                                        class="btn btn-sm btn-success"
                                        data-bs-toggle="modal"
                                        data-bs-target="#startEvaluationModal{{ $evaluation->id }}">
                                    <i class="ti ti-play"></i> {{ __('Start Evaluation') }}
                                </button>
                            @elseif(in_array($evaluation->status, ['in_progress', 'completed']))
                                <button type="button"
                                        class="btn btn-sm btn-warning"
                                        data-bs-toggle="modal"
                                        data-bs-target="#continueEvaluationModal{{ $evaluation->id }}">
                                    <i class="ti ti-edit"></i> {{ __('Continue Evaluation') }}
                                </button>
                            @endif
                            <a href="{{ route('administration.hiring.show', $evaluation->candidate) }}"
                               class="btn btn-sm btn-primary">
                                <i class="ti ti-eye"></i> {{ __('View Details') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="ti ti-clipboard-off display-4 text-muted mb-3"></i>
                    <h5 class="text-muted">{{ __('No evaluations assigned') }}</h5>
                    <p class="text-muted">{{ __('You have no candidate evaluations assigned to you at the moment.') }}</p>
                    <a href="{{ route('administration.hiring.index') }}" class="btn btn-primary">
                        <i class="ti ti-users"></i> {{ __('View All Candidates') }}
                    </a>
                </div>
            </div>
        </div>
    @endforelse
</div>

<!-- Evaluation Modals -->
@foreach($evaluations as $evaluation)
    @if($evaluation->status === 'pending')
        @include('administration.hiring.modals.start_evaluation', ['evaluation' => $evaluation])
    @elseif(in_array($evaluation->status, ['in_progress', 'completed']))
        @include('administration.hiring.modals.continue_evaluation', ['evaluation' => $evaluation])
    @endif
@endforeach

<!-- Pagination -->
@if($evaluations->hasPages())
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $evaluations->links() }}
            </div>
        </div>
    </div>
@endif

@endsection

@section('script_links')
    {{--  External Javascript Links --}}
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function() {
            // Any custom JavaScript can go here
        });
    </script>
@endsection
