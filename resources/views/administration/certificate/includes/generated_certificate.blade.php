<form action="{{ route('administration.certificate.store') }}" method="post">
    @csrf

    {{-- Hidden fields to pass certificate data - dynamically generated based on config --}}
    @php
        $certificateConfig = config('certificate.types')[$certificate->type] ?? null;
        $allFields = [];

        if ($certificateConfig) {
            // Get all required and optional fields for this certificate type
            $allFields = array_merge(
                $certificateConfig['required_fields'] ?? [],
                $certificateConfig['optional_fields'] ?? []
            );
        }
    @endphp

    @if($certificateConfig && !empty($allFields))
        @foreach($allFields as $field)
            @if(isset($certificate->$field) && $certificate->$field !== null)
                <input type="hidden" name="{{ $field }}" value="{{ $certificate->$field }}">
            @endif
        @endforeach
    @else
        {{-- Fallback: Basic required fields --}}
        <input type="hidden" name="user_id" value="{{ $certificate->user_id }}">
        <input type="hidden" name="type" value="{{ $certificate->type }}">
        <input type="hidden" name="issue_date" value="{{ $certificate->issue_date }}">
    @endif

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
