<!-- Employee Info Update Modal -->
<div class="modal fade" data-bs-backdrop="static" id="recognizeCongratsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content py-5">
            <!-- Confetti Container -->
            <div class="confetti-container" id="confettiContainer"></div>

            <a href="{{ route('administration.notification.mark_as_read_and_redirect', ['notification_id' => $recognitionCongratulationData['notification_id']]) }}" class="btn-close btn-pinned" ></a>

            <div class="modal-body">
                <!-- Flip Animation Container -->
                <div class="flip-container">
                    <div class="flip-card">
                        <!-- Front Side - Medal GIF -->
                        <div class="flip-front">
                            <img src="{{ asset('assets/img/custom_images/recognize_congrats.gif') }}" 
                                    class="medal-gif" 
                                    alt="Award Medal">
                        </div>
                        <!-- Back Side - Profile Icon -->
                        <div class="flip-back">
                            <div class="profile-circle">
                                <img class="rounded-circle" src="{{ $recognitionCongratulationData['avatar_url'] }}" alt="User Avatar" width="170" height="170">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Title Section -->
                <div class="text-center mb-2">
                    <h3 class="role-title mb-2">Congratulations To {{ $recognitionCongratulationData['employee_name'] }}</h3>
                    <p class="recognized-by">
                        <span class="fw-normal">Recognised by: {{ $recognitionCongratulationData['recognizer_name'] }}</span> 
                        <span class="fw-semibold"></span>
                    </p>
                </div>

                <!-- Award Details -->
                <div class="award-details">
                    <div class="award-badge">
                        <i class="fas fa-heart"></i>
                        <span>{{ $recognitionCongratulationData['category'] }}</span>
                    </div>
                    <div class="award-badge points-badge">
                        <i class="fas fa-coins"></i>
                        <span>{{ $recognitionCongratulationData['total_mark'] }}</span>
                    </div>
                </div>

                <!-- Inspirational Quote -->
                <div class="quote-section mx-5">
                    <div class="quote-icon mb-2 text-center">
                        <i class="fas fa-quote-left"></i>
                    </div>
                    <blockquote class="fs-6 fst-italic fw-medium text-center mb-3" style="line-height: 1.4; color:#242121">
                        {!! $recognitionCongratulationData['comment'] !!}
                    </blockquote>
                    <div class="quote-icon text-center">
                        <i class="fas fa-quote-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
