{{-- Recognition Notification Slider --}}
<div class="recognition-notification d-none" id="recognitionNotification">
    <div class="card bg-gradient-recognition border-0 shadow-notification recognition-card">
        <div class="card-header bg-transparent border-0 p-4 pb-3">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="icon-bg-white rounded-circle p-3 me-3">
                        <i data-lucide="star" class="text-white" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <h5 class="text-white mb-0 fw-bold">Team Recognition</h5>
                        <small class="text-white-80">Celebrating excellence together</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white btn-lg" onclick="closeRecognitionNotification()"></button>
            </div>
            <div class="progress-bar-recognition mt-3"></div>
        </div>
        
        <div class="card-body p-4 pt-3 notification-body">
            <div class="d-flex align-items-start">
                <div class="icon-bg-white rounded-circle p-3 me-4 flex-shrink-0">
                    <i data-lucide="award" class="text-white" style="width: 24px; height: 24px;"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="text-white mb-0 fw-bold">Employee Name</h4>
                        <div class="recognition-points rounded-pill px-3 py-2 bg-white-20">
                            <span class="text-white fw-bold fs-6">0 pts</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <p class="text-white-90 mb-1">
                            <span class="fw-semibold">Recognized by:</span> 
                            <span class="fw-medium">Manager</span>
                        </p>
                        <small class="text-white-70">Just now</small>
                    </div>
                    
                    <div class="mb-3">
                        <span class="badge category-badge fs-6 px-3 py-2">
                            <i data-lucide="award" style="width: 16px; height: 16px;" class="me-2"></i>
                            Recognition
                        </span>
                    </div>
                    
                    <div class="mt-3">
                        <p class="text-white-90 mb-0 fst-italic fs-6 lh-base">
                            "Great work!"
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-footer bg-transparent border-0 p-4 pt-2">
            <div class="d-flex align-items-center text-white-70">
                <i data-lucide="users" style="width: 16px; height: 16px;" class="me-2"></i>
                <small class="fs-6">Team announcement • Everyone can see this</small>
            </div>
        </div>
    </div>
</div>
