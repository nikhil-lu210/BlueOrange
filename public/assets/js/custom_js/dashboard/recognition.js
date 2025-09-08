/**
 * Recognition System JavaScript
 * Handles recognition notifications and carousel functionality
 */

class RecognitionSystem {
    constructor() {
        this.confettiColors = [
            '#ff6b6b', '#4ecdc4', '#45b7d1', '#f9ca24', '#f0932b',
            '#eb4d4b', '#6c5ce7', '#a29bfe', '#fd79a8', '#fdcb6e'
        ];
        this.init();
    }

    init() {
        this.initNotificationSystem();
        this.initConfettiSystem();
        this.initMultiUserCarousel();
    }

    // Recognition Notification System
    initNotificationSystem() {
        // Global functions for recognition notification
        window.showRecognitionNotification = () => {
            const notification = document.getElementById('recognitionNotification');
            if (!notification) return;

            notification.classList.remove('d-none');

            // Auto close after 5 seconds
            setTimeout(() => {
                this.closeRecognitionNotification();
            }, 5000);
        };

        window.closeRecognitionNotification = () => {
            const notification = document.getElementById('recognitionNotification');
            if (!notification) return;

            notification.classList.add('notification-closing');

            setTimeout(() => {
                notification.classList.add('d-none');
                notification.classList.remove('notification-closing');
            }, 300);
        };

        window.showCustomRecognition = (data) => {
            const notification = document.getElementById('recognitionNotification');
            if (!notification) return;

            // Update notification content
            const elements = {
                employeeName: notification.querySelector('.fw-bold'),
                points: notification.querySelector('.recognition-points span'),
                recognizedBy: notification.querySelector('.fw-medium').nextSibling,
                category: notification.querySelector('.category-badge'),
                message: notification.querySelector('.fst-italic')
            };

            if (elements.employeeName) elements.employeeName.textContent = data.employeeName || 'Employee Name';
            if (elements.points) elements.points.textContent = `${data.points || 0} pts`;
            if (elements.recognizedBy) elements.recognizedBy.textContent = data.recognizedBy || 'Manager';
            if (elements.category) {
                elements.category.innerHTML = `
                    <i data-lucide="${data.categoryIcon || 'award'}" style="width: 16px; height: 16px;" class="me-2"></i>
                    ${data.category || 'Recognition'}
                `;
            }
            if (elements.message) elements.message.textContent = `"${data.message || 'Great work!'}"`;

            // Reinitialize icons and show
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            notification.classList.remove('d-none');

            setTimeout(() => {
                this.closeRecognitionNotification();
            }, 5000);
        };
    }

    // Confetti System
    initConfettiSystem() {
        window.createConfetti = () => {
            const container = document.getElementById('confettiContainer');
            if (!container) return;

            const containerRect = container.getBoundingClientRect();

            for (let i = 0; i < 50; i++) {
                const confetti = document.createElement('div');
                confetti.classList.add('confetti');

                // Random position and properties
                confetti.style.left = Math.random() * 100 + '%';
                confetti.style.backgroundColor = this.confettiColors[Math.floor(Math.random() * this.confettiColors.length)];
                confetti.style.animationDelay = Math.random() * 2 + 's';

                // Random size
                const size = Math.random() * 6 + 4;
                confetti.style.width = size + 'px';
                confetti.style.height = size + 'px';

                // Sometimes make rectangles
                if (Math.random() > 0.5) {
                    confetti.style.height = size * 0.6 + 'px';
                }

                container.appendChild(confetti);

                // Activate with delay
                setTimeout(() => {
                    confetti.classList.add('active');
                }, i * 50);
            }

            // Clean up after animation
            setTimeout(() => {
                container.innerHTML = '';
            }, 5000);
        };

        // Birthday confetti
        window.createConfettiBirthday = () => {
            const confetti = document.createElement('div');
            confetti.classList.add('confetti');
            confetti.style.left = Math.random() * 100 + 'vw';
            confetti.style.animationDuration = Math.random() * 3 + 2 + 's';
            confetti.style.opacity = Math.random();
            confetti.style.transform = `rotate(${Math.random() * 360}deg)`;

            const contentWrapper = document.querySelector('.content-wrapper');
            if (contentWrapper) {
                contentWrapper.appendChild(confetti);
                setTimeout(() => confetti.remove(), 5000);
            }
        };

        // Birthday confetti interval
        if (document.querySelector('.birthday-wish-container')) {
            setInterval(window.createConfettiBirthday, 200);
        }
    }

    // Multi-User Recognition Carousel
    initMultiUserCarousel() {
        class MultiRecognitionCarousel {
            constructor() {
                this.currentSlide = 0;
                this.autoPlayInterval = null;
                this.isAutoPlaying = false;
                this.slideShowDuration = 10000; // 10 seconds

                this.initializeElements();
                if (!this.slidesContainer) return;

                this.totalSlides = this.slidesContainer.children.length;
                this.setupEventListeners();
                this.generateIndicators();
                this.updateDisplay();
            }

            initializeElements() {
                this.modal = document.getElementById('recognizeCongratsModal');
                this.slidesContainer = document.getElementById('recognitionSlides');
                this.prevBtn = document.getElementById('prevBtn');
                this.nextBtn = document.getElementById('nextBtn');
                this.indicators = document.getElementById('indicators');
                this.progressCounter = document.getElementById('progressCounter');
                this.confettiContainer = document.getElementById('confettiContainer');

                // Fix avatar clipping
                const carousel = this.modal?.querySelector('.recognition-carousel');
                if (carousel) {
                    carousel.style.overflowX = 'hidden';
                    carousel.style.overflowY = 'visible';
                    carousel.style.position = 'relative';
                    carousel.style.width = '100%';
                }
            }

            setupEventListeners() {
                this.prevBtn?.addEventListener('click', () => this.previousSlide());
                this.nextBtn?.addEventListener('click', () => this.nextSlide());
                this.modal?.addEventListener('show.bs.modal', () => this.onModalShow());
                this.modal?.addEventListener('hidden.bs.modal', () => this.onModalHide());
            }

            generateIndicators() {
                if (!this.indicators) return;
                this.indicators.innerHTML = '';

                for (let i = 0; i < this.totalSlides; i++) {
                    const indicator = document.createElement('div');
                    indicator.classList.add('indicator');
                    if (i === 0) indicator.classList.add('active');
                    indicator.addEventListener('click', () => this.goToSlide(i));
                    this.indicators.appendChild(indicator);
                }
            }

            updateDisplay() {
                // Update slide position
                const translateX = -this.currentSlide * 100;
                this.slidesContainer.style.transform = `translateX(${translateX}%)`;

                // Update indicators
                const indicatorElements = this.indicators?.querySelectorAll('.indicator') || [];
                indicatorElements.forEach((indicator, index) => {
                    indicator.classList.toggle('active', index === this.currentSlide);
                });

                // Update progress counter
                if (this.progressCounter) {
                    this.progressCounter.textContent = `${this.currentSlide + 1} of ${this.totalSlides}`;
                }

                // Update navigation buttons
                if (this.prevBtn) this.prevBtn.disabled = this.currentSlide === 0;
                if (this.nextBtn) this.nextBtn.disabled = this.currentSlide === this.totalSlides - 1;

                // Reset and animate current slide
                this.resetCurrentSlideAnimation();

                // Trigger confetti
                setTimeout(() => {
                    if (typeof window.createConfetti === 'function') {
                        window.createConfetti();
                    }
                }, 300);
            }

            goToSlide(index) {
                this.currentSlide = index;
                this.updateDisplay();
            }

            nextSlide() {
                if (this.currentSlide < this.totalSlides - 1) {
                    this.currentSlide++;
                    this.updateDisplay();
                } else if (this.isAutoPlaying) {
                    this.currentSlide = 0;
                    this.updateDisplay();
                }
            }

            previousSlide() {
                if (this.currentSlide > 0) {
                    this.currentSlide--;
                    this.updateDisplay();
                }
            }

            startAutoPlay() {
                this.isAutoPlaying = true;
                this.autoPlayInterval = setInterval(() => this.nextSlide(), this.slideShowDuration);
            }

            stopAutoPlay() {
                this.isAutoPlaying = false;
                if (this.autoPlayInterval) {
                    clearInterval(this.autoPlayInterval);
                    this.autoPlayInterval = null;
                }
            }

            resetCurrentSlideAnimation() {
                const currentSlideElement = this.slidesContainer.children[this.currentSlide];
                const flipCard = currentSlideElement?.querySelector('.flip-card');
                if (!flipCard) return;

                flipCard.classList.remove('animate');
                flipCard.style.transform = 'rotateY(0deg)';
                void flipCard.offsetWidth; // Force reflow

                setTimeout(() => {
                    flipCard.classList.add('animate');
                }, 1000);
            }

            onModalShow() {
                this.currentSlide = 0;
                this.updateDisplay();

                const carousel = this.modal?.querySelector('.recognition-carousel');
                if (carousel) {
                    carousel.style.overflowX = 'hidden';
                    carousel.style.overflowY = 'visible';
                }

                this.startAutoPlay();
            }

            onModalHide() {
                this.stopAutoPlay();
                if (this.confettiContainer) this.confettiContainer.innerHTML = '';
                this.currentSlide = 0;
            }
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', () => {
            const hasMultiCarousel = document.querySelector('#recognizeCongratsModal .recognition-slides');
            if (hasMultiCarousel) {
                window.multiRecognitionCarousel = new MultiRecognitionCarousel();
            }
        });
    }
}

// Initialize recognition system
document.addEventListener('DOMContentLoaded', () => {
    window.recognitionSystem = new RecognitionSystem();
});
