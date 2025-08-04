<div class="modal fade" id="startEvaluationModal{{ $evaluation->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ti ti-play me-2"></i>{{ __('Start Evaluation') }} - {{ $evaluation->stage->name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h6 class="text-primary">{{ $evaluation->candidate->name }}</h6>
                    <p class="text-muted mb-0">{{ $evaluation->candidate->expected_role }}</p>
                    @if($evaluation->scheduled_at)
                        <small class="text-muted">{{ __('Scheduled for') }}: {{ $evaluation->scheduled_at->format('M d, Y \a\t g:i A') }}</small>
                    @endif
                </div>

                <form action="{{ route('administration.hiring.evaluation.start') }}" method="POST" id="startEvaluationForm{{ $evaluation->id }}">
                    @csrf
                    <input type="hidden" name="evaluation_id" value="{{ $evaluation->id }}">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="start_date{{ $evaluation->id }}" class="form-label">{{ __('Start Date') }} <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control date-picker" 
                                   id="start_date{{ $evaluation->id }}" 
                                   name="start_date" 
                                   value="{{ date('Y-m-d') }}" 
                                   required>
                        </div>
                        <div class="col-md-6">
                            <label for="start_time{{ $evaluation->id }}" class="form-label">{{ __('Start Time') }} <span class="text-danger">*</span></label>
                            <input type="time" 
                                   class="form-control time-picker" 
                                   id="start_time{{ $evaluation->id }}" 
                                   name="start_time" 
                                   value="{{ date('H:i') }}" 
                                   required>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="ti ti-play me-1"></i>{{ __('Start Evaluation') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
