<!-- Holiday Create Modal -->
<div class="modal fade" data-bs-backdrop="static" id="assignNewHolidayModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">Assign Holiday</h3>
                    <p class="text-muted">Assign A New Holiday</p>
                </div>
                <!-- Holiday Create form -->
                <form method="post" action="{{ route('administration.settings.system.holiday.store') }}" class="row g-3" autocomplete="off">
                    @csrf
                    <div class="col-md-8">
                        <label class="form-label">Holiday For (Title) <strong class="text-danger">*</strong></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Ex: Internation Labour Day" tabindex="-1" required/>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Date <strong class="text-danger">*</strong></label>
                        <input type="text" name="date" value="{{ old('date') }}" class="form-control  date-picker" placeholder="YYYY-MM-DD" tabindex="-1" required/>
                        @error('date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Description <strong class="text-danger">*</strong></label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Ex: On this day, it is international labour day.">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">
                            <i class="ti ti-check"></i>
                            Assign Now
                        </button>
                    </div>
                </form>
                <!--/ Holiday Create form -->
            </div>
        </div>
    </div>
</div>
<!--/ Holiday Create Modal -->