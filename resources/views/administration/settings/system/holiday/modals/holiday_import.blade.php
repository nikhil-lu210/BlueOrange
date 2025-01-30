<!-- Holiday Import Modal -->
<div class="modal fade" data-bs-backdrop="static" id="importHolidayModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">Import Holidays</h3>
                    <p class="text-muted">Import New Holidays</p>
                </div>
                <!-- Holiday Import form -->
                <form method="post" action="{{ route('administration.settings.system.holiday.import') }}" class="row g-3" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3 col-md-12">
                        <label for="import_file" class="form-label">
                            Holidays File <sup class="text-dark text-bold">(.csv file only)</sup> <strong class="text-danger">*</strong>
                        </label>
                        <input type="file" id="import_file" name="import_file" value="{{ old('import_file') }}" placeholder="{{ __('Files') }}" class="form-control @error('import_file') is-invalid @enderror" accept=".csv" required/>
                        <small>
                            <span class="text-dark text-bold">Note:</span>
                            <span>Please select <b class="text-bold text-info">.csv</b> file only.</span>
                        </small>
                        <b class="float-end">
                            <a href="{{ asset('import_templates_sample/holidays_import_sample.csv') }}" class="text-primary text-bold">
                                <span class="tf-icon ti ti-download"></span>
                                {{ __('Download Formatted Template') }}
                            </a>
                        </b>
                        <br>
                        @error('import_file')
                            <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                        @enderror
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">
                            <i class="ti ti-check"></i>
                            Upload & Import
                        </button>
                    </div>
                </form>
                <!--/ Holiday Import form -->
            </div>
        </div>
    </div>
</div>
<!--/ Holiday Import Modal -->