<div class="modal fade" id="continueEvaluationModal{{ $evaluation->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1">
                        <i class="ti ti-clipboard-check me-2"></i>{{ __('Evaluation') }} - {{ $evaluation->stage->name }}
                    </h5>
                    <small class="text-muted">{{ $evaluation->candidate->name }} ({{ $evaluation->candidate->expected_role }})</small>
                </div>
                <div class="d-flex align-items-center">
                    <!-- Timer Display -->
                    <div class="me-3">
                        <div class="badge bg-primary fs-6" id="timer{{ $evaluation->id }}">
                            <i class="ti ti-clock me-1"></i>
                            <span id="timerDisplay{{ $evaluation->id }}">00:00:00</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body">
                <form action="{{ route('administration.hiring.evaluation.complete') }}" method="POST" enctype="multipart/form-data" id="evaluationForm{{ $evaluation->id }}">
                    @csrf
                    <input type="hidden" name="evaluation_id" value="{{ $evaluation->id }}">
                    <input type="hidden" name="start_time" id="startTime{{ $evaluation->id }}" value="{{ $evaluation->started_at ? $evaluation->started_at->timestamp : time() }}">
                    
                    <div class="row g-4">
                        <!-- Candidate Information -->
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>{{ __('Email') }}:</strong><br>
                                            <span class="text-muted">{{ $evaluation->candidate->email }}</span>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>{{ __('Phone') }}:</strong><br>
                                            <span class="text-muted">{{ $evaluation->candidate->phone }}</span>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>{{ __('Expected Salary') }}:</strong><br>
                                            <span class="text-muted">{{ $evaluation->candidate->expected_salary_formatted }}</span>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>{{ __('Stage') }}:</strong><br>
                                            <span class="badge bg-info">{{ $evaluation->stage->name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Feedback Section -->
                        <div class="col-12">
                            <label for="feedback{{ $evaluation->id }}" class="form-label">{{ __('Evaluation Feedback') }} <span class="text-danger">*</span></label>
                            <div id="feedbackEditor{{ $evaluation->id }}" style="height: 200px;">
                                {!! old('feedback', $evaluation->feedback) !!}
                            </div>
                            <textarea name="feedback" id="feedbackHidden{{ $evaluation->id }}" style="display: none;" required>{{ old('feedback', $evaluation->feedback) }}</textarea>
                        </div>

                        <!-- Notes Section -->
                        <div class="col-12">
                            <label for="notes{{ $evaluation->id }}" class="form-label">{{ __('Additional Notes') }}</label>
                            <textarea class="form-control" 
                                      name="notes" 
                                      id="notes{{ $evaluation->id }}" 
                                      rows="3" 
                                      placeholder="{{ __('Any additional notes or observations...') }}">{{ old('notes', $evaluation->notes) }}</textarea>
                        </div>

                        <!-- Rating Section -->
                        <div class="col-md-6">
                            <label for="rating{{ $evaluation->id }}" class="form-label">{{ __('Rating (1-10)') }}</label>
                            <select class="form-select" name="rating" id="rating{{ $evaluation->id }}">
                                <option value="">{{ __('No Rating') }}</option>
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" {{ old('rating', $evaluation->rating) == $i ? 'selected' : '' }}>
                                        {{ $i }} - {{ $i <= 3 ? 'Poor' : ($i <= 6 ? 'Average' : ($i <= 8 ? 'Good' : 'Excellent')) }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <!-- File Upload -->
                        <div class="col-md-6">
                            <label for="files{{ $evaluation->id }}" class="form-label">{{ __('Evaluation Documents') }}</label>
                            <input type="file" 
                                   class="form-control" 
                                   name="files[]" 
                                   id="files{{ $evaluation->id }}" 
                                   multiple 
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <div class="form-text">{{ __('Max 5MB per file') }}</div>
                        </div>

                        <!-- Pass/Fail Decision -->
                        <div class="col-12">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0">{{ __('Evaluation Decision') }} <span class="text-danger">*</span></h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check form-check-success">
                                                <input class="form-check-input" 
                                                       type="radio" 
                                                       name="result" 
                                                       id="pass{{ $evaluation->id }}" 
                                                       value="passed" 
                                                       {{ old('result', $evaluation->status) == 'passed' ? 'checked' : '' }} 
                                                       required>
                                                <label class="form-check-label text-success fw-bold" for="pass{{ $evaluation->id }}">
                                                    <i class="ti ti-check-circle me-1"></i>{{ __('PASS') }}
                                                </label>
                                                <div class="form-text text-success">
                                                    @if($evaluation->candidate->current_stage < 3)
                                                        {{ __('Candidate will move to next stage') }}
                                                    @else
                                                        {{ __('Candidate will be marked as HIRED') }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-check-danger">
                                                <input class="form-check-input" 
                                                       type="radio" 
                                                       name="result" 
                                                       id="fail{{ $evaluation->id }}" 
                                                       value="failed" 
                                                       {{ old('result', $evaluation->status) == 'failed' ? 'checked' : '' }} 
                                                       required>
                                                <label class="form-check-label text-danger fw-bold" for="fail{{ $evaluation->id }}">
                                                    <i class="ti ti-x-circle me-1"></i>{{ __('FAIL') }}
                                                </label>
                                                <div class="form-text text-danger">
                                                    {{ __('Candidate will be REJECTED') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i>{{ __('Complete Evaluation') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Quill editor for this evaluation
    if (typeof Quill !== 'undefined') {
        const quill{{ $evaluation->id }} = new Quill('#feedbackEditor{{ $evaluation->id }}', {
            theme: 'snow',
            placeholder: '{{ __("Write detailed feedback about the candidate's performance...") }}',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['clean']
                ]
            }
        });

        // Update hidden textarea when form is submitted
        document.getElementById('evaluationForm{{ $evaluation->id }}').addEventListener('submit', function() {
            document.getElementById('feedbackHidden{{ $evaluation->id }}').value = quill{{ $evaluation->id }}.root.innerHTML;
        });
    }

    // Timer functionality
    let startTime{{ $evaluation->id }} = {{ $evaluation->started_at ? $evaluation->started_at->timestamp : 'Date.now() / 1000' }};
    let timerInterval{{ $evaluation->id }};

    function updateTimer{{ $evaluation->id }}() {
        const now = Date.now() / 1000;
        const elapsed = Math.floor(now - startTime{{ $evaluation->id }});
        
        const hours = Math.floor(elapsed / 3600);
        const minutes = Math.floor((elapsed % 3600) / 60);
        const seconds = elapsed % 60;
        
        const display = String(hours).padStart(2, '0') + ':' + 
                       String(minutes).padStart(2, '0') + ':' + 
                       String(seconds).padStart(2, '0');
        
        document.getElementById('timerDisplay{{ $evaluation->id }}').textContent = display;
    }

    // Start timer when modal is shown
    document.getElementById('continueEvaluationModal{{ $evaluation->id }}').addEventListener('shown.bs.modal', function() {
        updateTimer{{ $evaluation->id }}();
        timerInterval{{ $evaluation->id }} = setInterval(updateTimer{{ $evaluation->id }}, 1000);
    });

    // Stop timer when modal is hidden
    document.getElementById('continueEvaluationModal{{ $evaluation->id }}').addEventListener('hidden.bs.modal', function() {
        if (timerInterval{{ $evaluation->id }}) {
            clearInterval(timerInterval{{ $evaluation->id }});
        }
    });
});
</script>
