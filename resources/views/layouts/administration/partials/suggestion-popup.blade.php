@can('Suggestion Create')
<div>
    <!-- Floating Button -->
    <div class="buy-now">
        <button id="suggestionBtn" class="btn btn-primary gap-2 btn-buy-now waves-effect waves-light shadow-lg" aria-label="Open suggestion popup">
            <i class="ti ti-message-plus"></i>
            <span class="btn-text">Suggestion</span>
        </button>
    </div>

    <!-- Overlay -->
    <div id="suggestionOverlay" class="d-none" style="position:fixed; inset:0; background:rgba(0,0,0,0.5); backdrop-filter:blur(4px); z-index:2147482900; transition:all 0.3s ease;"></div>

    <!-- Popup -->
    <div id="suggestionPopup"
        class="d-none bg-white shadow-lg rounded-4"
        style="position:fixed; bottom:6.5rem; right:1.5rem; z-index:2147483000; width:26rem; overflow:hidden; transform:translateY(20px); opacity:0; transition:all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);">
        
        <!-- Header with Gradient -->
        <div class="d-flex justify-content-between align-items-center p-4" style="background:linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-opacity-25 rounded-circle p-2">
                    <i class="ti ti-bulb text-white fs-5"></i>
                </div>
                <h3 class="h5 mb-0 text-white fw-semibold" id="suggestionPopupTitle">Share Your Ideas</h3>
            </div>
            <button id="closeSuggestion" 
                class="btn btn-link text-white p-0 fs-4 opacity-75 hover-opacity-100" 
                aria-label="Close suggestion popup"
                style="transition:opacity 0.2s;">
                &times;
            </button>
        </div>

        <!-- Form Content -->
        <form id="suggestionForm" class="p-4" method="POST" action="{{ route('administration.suggestion.store') }}" aria-labelledby="suggestionPopupTitle" role="dialog">
            @csrf
            
            <p class="text-muted small mb-4">
                <i class="ti ti-info-circle me-1"></i>
                We value your feedback! Share your suggestions to help us improve.
            </p>

            <div class="mb-3">
                <label class="form-label fw-medium text-dark">
                    <i class="ti ti-color-picker me-1 text-primary"></i>
                    Feedback Type *
                </label>
                <select name="type" class="form-control border rounded-lg p-2" required style="transition:border-color 0.2s;">
                    <option value="">Select Feedback Type</option>
                    @foreach (config('feedback.types') as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium text-dark">
                    <i class="ti ti-blocks me-1 text-primary"></i>
                    Which Module?
                </label>
                <select name="module" class="form-control border rounded-lg p-2" style="transition:border-color 0.2s;">
                    <option value="">Select Module</option>
                    @foreach (config('feedback.modules') as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium text-dark">
                    <i class="ti ti-pencil me-1 text-primary"></i>
                    Title
                </label>

                <input type="text" 
                    name="title" 
                    class="form-control form-control-lg border-2" 
                    placeholder="Brief title for your suggestion"
                    required
                    style="transition:border-color 0.2s;">
            </div>

            <div class="mb-4">
                <label class="form-label fw-medium text-dark">
                    <i class="ti ti-message-2 me-1 text-primary"></i>
                    Your Suggestion
                </label>
                <textarea name="message" 
                    rows="4" 
                    class="form-control border-2" 
                    placeholder="Describe your idea in detail..."
                    required
                    style="transition:border-color 0.2s; resize:none;"></textarea>
                <small class="text-muted">
                    <i class="ti ti-lock me-1"></i>
                    Your feedback is confidential
                </small>
            </div>

            <div class="d-flex gap-2 justify-content-end">
                <button type="button" 
                    id="cancelSuggestion"
                    class="btn btn-light px-4">
                    Cancel
                </button>
                <button type="submit" 
                    class="btn btn-primary px-4 shadow-sm"
                    style="background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); border:none;">
                    <i class="ti ti-send me-1"></i>
                    Send Suggestion
                </button>
            </div>
        </form>
    </div>
</div>
@endcan