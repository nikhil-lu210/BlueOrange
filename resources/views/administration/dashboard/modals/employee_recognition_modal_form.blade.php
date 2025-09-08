<!-- Employee Recognition Modal -->
<div class="modal fade" data-bs-backdrop="static" id="recognitionModal" tabindex="-1" aria-hidden="true" data-store-url="{{ route('administration.recognition.store') }}">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content recognize_modal_form_content p-3 p-md-5">
            <div class="step-indicator">
                Step <span id="currentStep">1</span> of 4
            </div>

            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>

            <div class="modal-body recognize_modal_form_body">
                <div class="slide-container">
                    <!-- Step 1: Initial Recognition Prompt -->
                    {{-- <div class="slide active" id="step1">
                        <div class="text-center">
                            <div class="recognition-icon d-flex align-items-center justify-content-center mx-auto mb-4">
                                <i class="fas fa-star text-white fs-3"></i>
                            </div>
                            <h5 class="fw-semibold text-dark mb-3 lh-base">
                                You've not recognized anyone<br>in the last 15 days.
                            </h5>
                            <button class="btn recognize-btn text-white px-4 py-2" onclick="nextStep()">
                                Recognize Now!!
                            </button>
                        </div>
                    </div> --}}

                    <!-- Step 2: Select User -->
                    <div class="slide active" id="step1">
                        <div class="text-center">
                            <h5 class="fw-semibold text-dark mb-4">Select Employee to Recognize</h5>
                            <div class="text-primary mb-3 arrow-bounce">
                                <i class="fas fa-chevron-down fs-4"></i>
                            </div>

                            <select class="form-select w-100 mb-4 rounded-3 @error('user_id') is-invalid @enderror" id="userSelect" name="user_id" required>
                                <option value="" disabled selected>{{ __('Select an Employee...') }}</option>
                                @foreach (auth()->user()->tl_employees as $user)
                                    <option value="{{ $user->id }}">{{ $user->alias_name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror

                            <div class="d-flex gap-2 justify-content-center">
                                
                                <button class="btn recognize-form-next-btn text-white px-4 py-2" onclick="nextStep()" disabled id="userNextBtn">
                                    Next<i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Select Category -->
                    <div class="slide slide-right" id="step2">
                        <div class="text-center">
                            <h5 class="fw-semibold text-dark mb-4">Select Recognition Category</h5>
                            <div class="text-primary mb-3 arrow-bounce">
                                <i class="fas fa-chevron-down fs-4"></i>
                            </div>

                            <select name="category" class="form-select form-select-lg mb-4 rounded-3 @error('category') is-invalid @enderror" id="categorySelect" required>
                                <option value="" disabled selected>{{ __('Select Recognition Category') }}</option>
                                @foreach(config('recognition.categories') as $category)
                                    <option value="{{ $category }}">{{ $category }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror

                            <div class="d-flex gap-2 justify-content-center">
                                <button class="btn recognize-form-back-btn text-white px-4 py-2" onclick="prevStep()">
                                    <i class="fas fa-arrow-left me-2"></i>Back
                                </button>
                                <button class="btn recognize-form-next-btn text-white px-4 py-2" onclick="nextStep()" disabled id="categoryNextBtn">
                                    Next<i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Recognition Message -->
                    <div class="slide slide-right" id="step3">
                        <div class="position-relative">
                            <div class="position-absolute top-0 end-0">
                                <div class="points-label text-end">Points</div>
                                <select class="form-select form-select-sm points-dropdown @error('total_mark') is-invalid @enderror" name="total_mark" id="pointsSelect" style="width: 90px;" required>
                                    <option value="100" selected>100</option>
                                    <option value="250">250</option>
                                    <option value="500">500</option>
                                    <option value="1000">1000</option>
                                    <option value="1500">1500</option>
                                    <option value="2000">2000</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <h5 class="text-primary fw-semibold mb-0" id="recognitionTitle">
                                    You are now recognizing your team member
                                </h5>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small fw-semibold">Recognition Message</label>
                                <textarea class="form-control rounded-3" name="comment" rows="4" placeholder="Ex: He is a good employee." id="messageText" style="resize: none; background-color: #f8f9fa;">{{ old('comment') }}</textarea>
                                @error('comment')
                                    <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                                @enderror
                            </div>

                            <div class="text-center mb-3">
                                <div class="team-badge d-flex align-items-center justify-content-center mx-auto position-relative">
                                    <i class="fas fa-users text-warning fs-4" style="z-index: 1;"></i>
                                </div>
                                <div class="mt-2">
                                    <small class="fw-bold text-uppercase text-dark" style="font-size: 10px; letter-spacing: 1px;">
                                        TEAMPLAYER
                                    </small>
                                </div>
                            </div>

                            <div class="d-flex gap-2 justify-content-center">
                                <button class="btn recognize-form-back-btn text-white px-4 py-2" onclick="prevStep()">
                                    <i class="fas fa-arrow-left me-2"></i>Back
                                </button>
                                <button class="btn recognize-form-submit-btn text-white px-4 py-2 bg-primary" onclick="submitRecognition()">
                                    <i class="fas fa-paper-plane me-2"></i>Submit Recognition
                                </button>
                            </div>
                        </div>
                    </div>
                </div> <!-- slide-container -->
            </div> <!-- modal-body -->
        </div>
    </div>
</div>
