<!-- Modal -->
<div class="modal fade" data-bs-backdrop="static" id="markAsPaidModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">Mark As Paid</h3>
                    <p class="text-muted">Salary Mark As Paid for {{ $monthly_salary->user->alias_name }}</p>
                </div>
                <!-- form -->
                <form method="post" action="{{ route('administration.accounts.salary.monthly.mark.paid', ['monthly_salary' => $monthly_salary]) }}" enctype="multipart/form-data" class="row g-3" autocomplete="off">
                    @csrf
                    @php
                        // 'Cash','Bank Transfer','Cheque Book','Mobile Banking'
                        $paymentMediums = [
                            'Cash Payment','Bank Transfer','Cheque Book','Mobile Banking (bKash)','Mobile Banking (Nagad)','Mobile Banking (uNet)','Other'
                        ];
                    @endphp
                    <div class="mb-2 col-md-12">
                        <label for="paid_through" class="form-label">{{ __('Select Payment Medium') }} <strong class="text-danger">*</strong></label>
                        <select name="paid_through" id="paid_through" class="form-select bootstrap-select w-100 @error('paid_through') is-invalid @enderror"  data-style="btn-default" required>
                            <option value="" selected>{{ __('Select Medium') }}</option>
                            @foreach ($paymentMediums as $medium)
                                <option value="{{ $medium }}">{{ $medium }}</option>
                            @endforeach
                        </select>
                        @error('paid_through')
                            <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                        @enderror
                    </div>
                    <div class="mb-2 col-md-12">
                        <label for="paid_at" class="form-label">{{ __('Paid At') }} <strong class="text-danger">*</strong></label>
                        <input type="text" id="paid_at" name="paid_at" value="{{ old('paid_at', now()->format('Y-m-d H:i:s')) }}" placeholder="YYYY-MM-DD HH:MM" class="form-control date-time-picker @error('paid_at') is-invalid @enderror" required/>
                        @error('paid_at')
                            <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="payment_proof">Payment Proof <strong class="text-danger">*</strong></label>
                        <textarea class="form-control" name="payment_proof" rows="3" placeholder="Transaction ID / Cheque Book No">{{ old('payment_proof') }}</textarea>
                        @error('payment_proof')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">
                            <i class="ti ti-check"></i>
                            Mark As Paid
                        </button>
                    </div>
                </form>
                <!--/ form -->
            </div>
        </div>
    </div>
</div>
<!--/ Modal -->
