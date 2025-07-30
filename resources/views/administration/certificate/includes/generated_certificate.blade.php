<form action="{{ route('administration.certificate.store') }}" method="post">
    @csrf

    {{-- Hidden fields to pass certificate data --}}
    <input type="hidden" name="user_id" value="{{ $certificate->user_id }}">
    <input type="hidden" name="type" value="{{ $certificate->type }}">
    <input type="hidden" name="issue_date" value="{{ $certificate->issue_date }}">
    <input type="hidden" name="joining_date" value="{{ $certificate->joining_date }}">
    <input type="hidden" name="salary" value="{{ $certificate->salary }}">
    <input type="hidden" name="resignation_date" value="{{ $certificate->resignation_date }}">
    <input type="hidden" name="release_date" value="{{ $certificate->release_date }}">
    <input type="hidden" name="release_reason" value="{{ $certificate->release_reason }}">
    <input type="hidden" name="country_name" value="{{ $certificate->country_name }}">
    <input type="hidden" name="visiting_purpose" value="{{ $certificate->visiting_purpose }}">
    <input type="hidden" name="leave_starts_from" value="{{ $certificate->leave_starts_from }}">
    <input type="hidden" name="leave_ends_on" value="{{ $certificate->leave_ends_on }}">

    <div class="card mb-4">
        <div class="card-header header-elements">
            <h5 class="mb-0 text-bold">Generated Certificate Preview</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mb-3">
                    {{-- Certificate Preview --}}
                    <div class="certificate-preview" style="border: 1px solid #ddd; padding: 20px; background: #f9f9f9; border-radius: 8px;">
                        @include('administration.certificate.templates.' . $certificate->getTemplateName(), ['isPrint' => false])
                    </div>
                </div>

                <div class="col-md-12 text-end mt-3">
                    <button type="submit" class="btn btn-success">
                        <span class="tf-icon ti ti-check ti-xs me-1"></span>
                        {{ __('Create & Issue Certificate') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
