@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Candidate Details'))

@section('css_links')
    {{--  External CSS  --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
        .stage-timeline {
            position: relative;
        }
        .stage-timeline::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
        }
        .stage-item {
            position: relative;
            padding-left: 60px;
            margin-bottom: 30px;
        }
        .stage-icon {
            position: absolute;
            left: 0;
            top: 0;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            z-index: 1;
        }
        .stage-completed {
            background-color: #28a745;
            color: white;
        }
        .stage-current {
            background-color: #ffc107;
            color: #212529;
        }
        .stage-pending {
            background-color: #e9ecef;
            color: #6c757d;
        }
        .evaluation-card {
            border-left: 4px solid #e9ecef;
        }
        .evaluation-card.status-passed {
            border-left-color: #28a745;
        }
        .evaluation-card.status-failed {
            border-left-color: #dc3545;
        }
        .evaluation-card.status-in-progress {
            border-left-color: #ffc107;
        }
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('Candidate Details') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('administration.dashboard.index') }}">{{ __('Dashboard') }}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.hiring.index') }}">{{ __('Employee Hiring') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ $hiring_candidate->name }}</li>
@endsection

@section('content')

<!-- Candidate Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h4 class="mb-1">{{ $hiring_candidate->name }}</h4>
                        <p class="text-muted mb-2">{{ $hiring_candidate->expected_role }}</p>
                        <div class="d-flex gap-3 mb-3">
                            <small class="text-muted">
                                <i class="ti ti-mail"></i> {{ $hiring_candidate->email }}
                            </small>
                            <small class="text-muted">
                                <i class="ti ti-phone"></i> {{ $hiring_candidate->phone }}
                            </small>
                            @if($hiring_candidate->expected_salary)
                                <small class="text-muted">
                                    <i class="ti ti-currency-rupee"></i> {{ $hiring_candidate->expected_salary_formatted }}
                                </small>
                            @endif
                        </div>
                        <span class="badge {{ $hiring_candidate->status_badge_class }} fs-6">
                            {{ $hiring_candidate->status_formatted }}
                        </span>
                    </div>
                    <div class="text-end">
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                {{ __('Actions') }}
                            </button>
                            <ul class="dropdown-menu">
                                @canany(['Employee Hiring Everything', 'Employee Hiring Update'])
                                    <li>
                                        <a class="dropdown-item" href="{{ route('administration.hiring.edit', $hiring_candidate) }}">
                                            <i class="ti ti-edit"></i> {{ __('Edit Candidate') }}
                                        </a>
                                    </li>
                                @endcanany
                                @if($hiring_candidate->status === 'in_progress' && $hiring_candidate->current_stage >= 3)
                                    @canany(['Employee Hiring Everything'])
                                        <li>
                                            <a class="dropdown-item" href="{{ route('administration.hiring.complete.form', $hiring_candidate) }}">
                                                <i class="ti ti-user-plus"></i> {{ __('Complete Hiring') }}
                                            </a>
                                        </li>
                                    @endcanany
                                @endif
                                @if($hiring_candidate->status !== 'rejected' && $hiring_candidate->status !== 'hired')
                                    @canany(['Employee Hiring Everything', 'Employee Hiring Update'])
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item text-warning" href="#" onclick="rejectCandidate()">
                                                <i class="ti ti-x"></i> {{ __('Reject Candidate') }}
                                            </a>
                                        </li>
                                    @endcanany
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column - Candidate Info & Files -->
    <div class="col-lg-4">
        <!-- Candidate Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">{{ __('Candidate Information') }}</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted d-block">{{ __('Added by') }}</small>
                    <strong>{{ $hiring_candidate->creator->name ?? 'Unknown' }}</strong>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">{{ __('Added on') }}</small>
                    <strong>{{ $hiring_candidate->created_at->format('M d, Y \a\t g:i A') }}</strong>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">{{ __('Current Stage') }}</small>
                    <strong>{{ $hiring_candidate->current_stage_name }}</strong>
                </div>
                @if($hiring_candidate->hired_at)
                    <div class="mb-3">
                        <small class="text-muted d-block">{{ __('Hired on') }}</small>
                        <strong>{{ $hiring_candidate->hired_at->format('M d, Y \a\t g:i A') }}</strong>
                    </div>
                @endif
                @if($hiring_candidate->notes)
                    <div class="mb-3">
                        <small class="text-muted d-block">{{ __('Notes') }}</small>
                        <p class="mb-0">{{ $hiring_candidate->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Files -->
        @if($hiring_candidate->files->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">{{ __('Documents') }}</h6>
                </div>
                <div class="card-body">
                    @foreach($hiring_candidate->files as $file)
                        <div class="d-flex align-items-center mb-2">
                            <i class="ti ti-file-text me-2"></i>
                            <div class="flex-grow-1">
                                <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="text-decoration-none">
                                    {{ $file->file_name }}
                                </a>
                                @if($file->note)
                                    <small class="text-muted d-block">{{ $file->note }}</small>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Right Column - Stage Timeline -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">{{ __('Hiring Process Timeline') }}</h6>
            </div>
            <div class="card-body">
                <div class="stage-timeline">
                    @foreach($stages as $stage)
                        @php
                            $evaluation = $hiring_candidate->evaluations->where('hiring_stage_id', $stage->id)->first();
                            $stageClass = 'stage-pending';
                            if ($stage->stage_order < $hiring_candidate->current_stage) {
                                $stageClass = 'stage-completed';
                            } elseif ($stage->stage_order == $hiring_candidate->current_stage) {
                                $stageClass = 'stage-current';
                            }
                        @endphp

                        <div class="stage-item">
                            <div class="stage-icon {{ $stageClass }}">
                                {{ $stage->stage_order }}
                            </div>
                            <div>
                                <h6 class="mb-1">{{ $stage->name }}</h6>
                                <p class="text-muted mb-2">{{ $stage->description }}</p>

                                @if($evaluation)
                                    <div class="evaluation-card card mb-3 status-{{ $evaluation->status }}">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <strong>{{ __('Assigned to') }}: {{ $evaluation->assignedUser->name ?? 'Unknown' }}</strong>
                                                    <br>
                                                    @if($evaluation->scheduled_at)
                                                        <small class="text-muted">{{ __('Scheduled') }}: {{ $evaluation->scheduled_at->format('M d, Y \a\t g:i A') }}</small>
                                                        <br>
                                                    @endif
                                                    <small class="text-muted">{{ __('Status') }}:
                                                        <span class="badge {{ $evaluation->status_badge_class }}">
                                                            {{ $evaluation->status_formatted }}
                                                        </span>
                                                    </small>
                                                </div>
                                                @if($evaluation->rating)
                                                    <div class="text-end">
                                                        <small class="text-muted d-block">{{ __('Rating') }}</small>
                                                        <strong>{{ $evaluation->rating }}/10</strong>
                                                    </div>
                                                @endif
                                            </div>

                                            @if($evaluation->notes)
                                                <div class="mb-2">
                                                    <small class="text-muted d-block">{{ __('Notes') }}</small>
                                                    <p class="mb-0">{{ $evaluation->notes }}</p>
                                                </div>
                                            @endif

                                            @if($evaluation->feedback)
                                                <div class="mb-2">
                                                    <small class="text-muted d-block">{{ __('Feedback') }}</small>
                                                    <p class="mb-0">{{ $evaluation->feedback }}</p>
                                                </div>
                                            @endif

                                            @if($evaluation->completed_at)
                                                <small class="text-muted">
                                                    {{ __('Completed on') }}: {{ $evaluation->completed_at->format('M d, Y \a\t g:i A') }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    @if($stage->stage_order <= $hiring_candidate->current_stage)
                                        <div class="alert alert-info">
                                            <small>{{ __('No evaluation assigned yet') }}</small>
                                        </div>
                                    @endif
                                @endif

                                <!-- Evaluation Form for Current Stage -->
                                @if($stage->stage_order == $hiring_candidate->current_stage && $hiring_candidate->status !== 'hired' && $hiring_candidate->status !== 'rejected')
                                    @canany(['Employee Hiring Everything', 'Employee Hiring Update'])
                                        @php
                                            $currentStageEvaluations = $hiring_candidate->evaluations->where('hiring_stage_id', $stage->id);
                                            $myEvaluation = $currentStageEvaluations->where('assigned_to', auth()->id())->first();
                                        @endphp

                                        @if($myEvaluation)
                                            <div class="mt-3">
                                                <div class="card border-primary">
                                                    <div class="card-header bg-primary text-white">
                                                        <h6 class="mb-0 text-white">
                                                            <i class="ti ti-clipboard-check"></i> {{ __('My Evaluation for') }} {{ $stage->name }}
                                                        </h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <form action="{{ route('administration.hiring.evaluation.store') }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <input type="hidden" name="evaluation_id" value="{{ $myEvaluation->id }}">

                                                            <div class="row g-3">
                                                                <div class="col-md-6">
                                                                    <label for="status" class="form-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                                                                    <select class="form-select @error('status') is-invalid @enderror"
                                                                            name="status"
                                                                            id="status"
                                                                            required>
                                                                        <option value="pending" {{ $myEvaluation->status == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                                                        <option value="in_progress" {{ $myEvaluation->status == 'in_progress' ? 'selected' : '' }}>{{ __('In Progress') }}</option>
                                                                        <option value="completed" {{ $myEvaluation->status == 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                                                                        <option value="passed" {{ $myEvaluation->status == 'passed' ? 'selected' : '' }}>{{ __('Passed') }}</option>
                                                                        <option value="failed" {{ $myEvaluation->status == 'failed' ? 'selected' : '' }}>{{ __('Failed') }}</option>
                                                                    </select>
                                                                    @error('status')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <label for="rating" class="form-label">{{ __('Rating (1-10)') }}</label>
                                                                    <select class="form-select @error('rating') is-invalid @enderror"
                                                                            name="rating"
                                                                            id="rating">
                                                                        <option value="">{{ __('No Rating') }}</option>
                                                                        @for($i = 1; $i <= 10; $i++)
                                                                            <option value="{{ $i }}" {{ $myEvaluation->rating == $i ? 'selected' : '' }}>
                                                                                {{ $i }} - {{ $i <= 3 ? 'Poor' : ($i <= 6 ? 'Average' : ($i <= 8 ? 'Good' : 'Excellent')) }}
                                                                            </option>
                                                                        @endfor
                                                                    </select>
                                                                    @error('rating')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>

                                                                <div class="col-12">
                                                                    <label for="notes" class="form-label">{{ __('Notes') }}</label>
                                                                    <textarea class="form-control @error('notes') is-invalid @enderror"
                                                                              name="notes"
                                                                              id="notes"
                                                                              rows="3"
                                                                              placeholder="{{ __('Add any notes about this evaluation stage...') }}">{{ old('notes', $myEvaluation->notes) }}</textarea>
                                                                    @error('notes')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>

                                                                <div class="col-12">
                                                                    <label for="feedback" class="form-label">{{ __('Feedback') }}</label>
                                                                    <textarea class="form-control @error('feedback') is-invalid @enderror"
                                                                              name="feedback"
                                                                              id="feedback"
                                                                              rows="3"
                                                                              placeholder="{{ __('Provide detailed feedback for the candidate...') }}">{{ old('feedback', $myEvaluation->feedback) }}</textarea>
                                                                    @error('feedback')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>

                                                                <div class="col-12">
                                                                    <label for="files" class="form-label">{{ __('Evaluation Documents') }}</label>
                                                                    <input type="file"
                                                                           class="form-control @error('files.*') is-invalid @enderror"
                                                                           name="files[]"
                                                                           id="files"
                                                                           multiple
                                                                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                                                    <div class="form-text">{{ __('Upload any relevant documents for this evaluation. Max 5MB per file.') }}</div>
                                                                    @error('files.*')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>

                                                                <div class="col-12">
                                                                    <div class="d-flex justify-content-end">
                                                                        <button type="submit" class="btn btn-primary">
                                                                            <i class="ti ti-device-floppy"></i> {{ __('Save Evaluation') }}
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endcanany
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Candidate Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('administration.hiring.reject', $hiring_candidate) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Reject Candidate') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reason" class="form-label">{{ __('Reason for Rejection') }}</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3"
                                  placeholder="{{ __('Please provide a reason for rejecting this candidate...') }}"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-danger">{{ __('Reject Candidate') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script_links')
    {{--  External Javascript Links --}}
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        function rejectCandidate() {
            $('#rejectModal').modal('show');
        }

        // Any additional JavaScript can go here
    </script>
@endsection
