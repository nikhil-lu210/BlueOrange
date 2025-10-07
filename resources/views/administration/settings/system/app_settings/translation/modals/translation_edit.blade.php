{{-- Translation Edit Modal --}}
<div class="modal fade" id="editTranslationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Translation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <!-- Locale -->
                        <div class="col-md-12 mb-3">
                            <label for="locale" class="form-label">Locale <span class="text-danger">*</span></label>
                            <select name="locale" id="locale" class="form-select @error('locale') is-invalid @enderror" required>
                                <option value="">Select Locale</option>
                                @foreach($localeDetails as $code => $locale)
                                    @if($code !== 'en' && $locale['will_use'])
                                        <option value="{{ $code }}">
                                            {{ $locale['name'] }} ({{ $locale['original'] }}) - {{ strtoupper($code) }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('locale')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Source Text -->
                        <div class="col-md-12 mb-3">
                            <label for="source_text" class="form-label">Source Text (English) <span class="text-danger">*</span></label>
                            <textarea 
                                name="source_text" 
                                id="source_text" 
                                rows="4" 
                                class="form-control @error('source_text') is-invalid @enderror" 
                                placeholder="Enter the original English text..."
                                required></textarea>
                            @error('source_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Maximum {{ config('translation.character_limits.source_text', 5000) }} characters</small>
                        </div>

                        <!-- Translated Text -->
                        <div class="col-md-12 mb-3">
                            <label for="translated_text" class="form-label">Translated Text <span class="text-danger">*</span></label>
                            <textarea 
                                name="translated_text" 
                                id="translated_text" 
                                rows="4" 
                                class="form-control @error('translated_text') is-invalid @enderror" 
                                placeholder="Enter the translated text..."
                                required></textarea>
                            @error('translated_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Maximum {{ config('translation.character_limits.translated_text', 10000) }} characters</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="tf-icon ti ti-device-floppy ti-xs me-1"></span>
                        Update Translation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
