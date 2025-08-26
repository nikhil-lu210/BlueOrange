@php
    switch ($leaveHistory->type) {
        case 'Earned':
            $typeBg = 'success';
            break;

        case 'Sick':
            $typeBg = 'warning';
            break;

        default:
            $typeBg = 'danger';
            break;
    }
@endphp
<!-- Status Modal -->
<div class="modal fade" data-bs-backdrop="static" id="approveLeaveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">Approve Leave</h3>
                    <p class="text-muted">Approve the <b class="text-{{ $typeBg }}">{{ $leaveHistory->type }} Leave</b> request of <b class="text-primary">{{ $leaveHistory->user->alias_name }}</b></p>
                </div>
                <!-- Status form -->
                <form method="post" action="{{ route('administration.leave.history.approve', ['leaveHistory' => $leaveHistory]) }}" enctype="multipart/form-data" class="row g-3" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="mb-3 col-md-12">
                        <label for="type" class="form-label">{{ __('Expected Leave Type') }} <strong class="text-danger">*</strong></label>
                        <div class="row">
                            <div class="col-md mb-md-0 mb-2">
                                <div class="form-check custom-option custom-option-basic form-check-success">
                                    <label class="form-check-label custom-option-content" for="typeEarned">
                                        <input name="type" class="form-check-input" type="radio" value="Earned" id="typeEarned" required {{ old('type', $leaveHistory->type ?? '') === 'Earned' ? 'checked' : '' }} />
                                        <span class="custom-option-header pb-0">
                                            <span class="h6 mb-0">Earned</span>
                                        </span>
                                    </label>
                                    @error('type')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-check custom-option custom-option-basic form-check-warning">
                                    <label class="form-check-label custom-option-content" for="typeSick">
                                        <input name="type" class="form-check-input" type="radio" value="Sick" id="typeSick" required {{ old('type', $leaveHistory->type ?? '') === 'Sick' ? 'checked' : '' }} />
                                        <span class="custom-option-header pb-0">
                                            <span class="h6 mb-0">Sick</span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-check custom-option custom-option-basic form-check-primary">
                                    <label class="form-check-label custom-option-content" for="typeCasual">
                                        <input name="type" class="form-check-input" type="radio" value="Casual" id="typeCasual" required {{ old('type', $leaveHistory->type ?? '') === 'Casual' ? 'checked' : '' }} />
                                        <span class="custom-option-header pb-0">
                                            <span class="h6 mb-0">Casual</span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-center mt-4">
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">
                            <i class="ti ti-check"></i>
                            Approve Leave
                        </button>
                    </div>
                </form>
                <!--/ Status form -->
            </div>
        </div>
    </div>
</div>
<!--/ Status Modal -->
