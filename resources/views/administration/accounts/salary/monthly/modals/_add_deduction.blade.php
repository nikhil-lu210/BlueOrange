<!-- Holiday Create Modal -->
<div class="modal fade" data-bs-backdrop="static" id="addDeductionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">Add Deduction</h3>
                    <p class="text-muted">Add Deduction for {{ $monthly_salary->user->alias_name }}</p>
                </div>
                <!-- Holiday Create form -->
                <form method="post" action="{{ route('administration.accounts.salary.monthly.add.deduction', ['monthly_salary' => $monthly_salary]) }}" enctype="multipart/form-data" class="row g-3" autocomplete="off">
                    @csrf
                    <div class="mb-3 col-md-12">
                        <label for="total" class="form-label">{{ __('Amount') }} <strong class="text-danger">*</strong></label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ti ti-currency-taka"></i></span>
                            <input type="number" step="0.01" min="0" name="total" id="total" value="{{ old('total') }}" class="form-control" placeholder="2,000" required>
                        </div>
                        @error('total')
                            <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-12">
                        <label for="reason" class="form-label">{{ __('Reason') }} <strong class="text-danger">*</strong></label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ti ti-note"></i></span>
                            <input type="text" maxlength="50" name="reason" id="reason" value="{{ old('reason') }}" class="form-control" placeholder="Dress Code Issue" required>
                        </div>
                        @error('reason')
                            <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                        @enderror
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="submit" class="btn btn-danger me-sm-3 me-1">
                            <i class="ti ti-minus"></i>
                            Add The Deduction
                        </button>
                    </div>
                </form>
                <!--/ Holiday Create form -->
            </div>
        </div>
    </div>
</div>
<!--/ Holiday Create Modal -->
