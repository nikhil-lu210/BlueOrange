<!-- Multi-User Recognition Modal -->
<div class="modal fade" data-bs-backdrop="static" id="recognizeCongratsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <!-- Confetti Container -->
            <div class="confetti-container" id="confettiContainer"></div>
            <a href="{{ route('administration.recognition.notification.mark_as_read') }}" class="btn-close btn-pinned"></a>

            <div class="modal-body">
                <!-- Recognition Carousel -->
                <div class="recognition-carousel">
                    <div class="recognition-slides" id="recognitionSlides">
                        @forelse ($recognitionData as $recognitionCongratulationData)
                            <div class="recognition-slide">
                                <div class="flip-container">
                                    <div class="flip-card">
                                        <div class="flip-front">
                                            <img src="{{ asset('assets/img/custom_images/recognize_congrats.gif') }}" class="medal-gif" alt="Award Medal">
                                        </div>
                                        <div class="flip-back">
                                            <div class="profile-circle">
                                                <img class="rounded-circle" src="{{ $recognitionCongratulationData['avatar_url'] }}" alt="User Avatar" width="170" height="170">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mb-4">
                                    <h3 class="role-title mb-2">Congratulations to {{ $recognitionCongratulationData['employee_name'] }}</h3>
                                    <p class="recognized-by">
                                        <span class="fw-normal">Recognised by:</span>
                                        <span class="fw-semibold">{{ $recognitionCongratulationData['recognizer_name'] }}</span>
                                    </p>
                                </div>

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

                                <div class="quote-section">
                                    <div class="quote-icon mb-2 text-center">
                                        <i class="fas fa-quote-left" style="font-size: 1.5rem;"></i>
                                    </div>
                                    <blockquote class="fs-6 fst-italic text-dark fw-medium text-center mb-3" style="line-height: 1.6;">
                                        {!! $recognitionCongratulationData['comment'] !!}
                                    </blockquote>
                                    <div class="quote-icon text-center">
                                        <i class="fas fa-quote-right" style="font-size: 1.5rem;"></i>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center">No recognitions available.</p>
                        @endforelse

                    </div>
                </div>

                @if(count($recognitionData) > 1)
                    <!-- Carousel Controls -->
                    <div class="carousel-controls">
                        <button class="carousel-btn" id="prevBtn">
                            <i class="fas fa-chevron-left"></i>
                        </button>

                        <div class="progress-counter" id="progressCounter">
                            1 of {{ count($recognitionData) }}
                        </div>

                        <button class="carousel-btn" id="nextBtn">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
