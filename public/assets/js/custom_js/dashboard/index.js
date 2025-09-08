// Confetti colors
const confettiColors = [
    '#ff6b6b',
    '#4ecdc4',
    '#45b7d1',
    '#f9ca24',
    '#f0932b',
    '#eb4d4b',
    '#6c5ce7',
    '#a29bfe',
    '#fd79a8',
    '#fdcb6e'
];

// Create confetti function
function createConfetti() {
    const container = document.getElementById('confettiContainer');
    const containerRect = container.getBoundingClientRect();

    for (let i = 0; i < 50; i++) {
        const confetti = document.createElement('div');
        confetti.classList.add('confetti');

        // Random position across the width
        confetti.style.left = Math.random() * 100 + '%';

        // Random color
        confetti.style.backgroundColor =
            confettiColors[Math.floor(Math.random() * confettiColors.length)];

        // Random delay for staggered effect
        confetti.style.animationDelay = Math.random() * 2 + 's';

        // Random size variation
        const size = Math.random() * 6 + 4;
        confetti.style.width = size + 'px';
        confetti.style.height = size + 'px';

        // Sometimes make rectangles instead of squares
        if (Math.random() > 0.5) {
            confetti.style.height = size * 0.6 + 'px';
        }

        container.appendChild(confetti);

        // Activate confetti with slight delay
        setTimeout(() => {
            confetti.classList.add('active');
        }, i * 50);
    }

    // Clean up confetti after animation
    setTimeout(() => {
        container.innerHTML = '';
    }, 5000);
}

// Modal event handlers will be managed by MultiRecognitionCarousel class

// External Custom Javascript

$(document).on('shown.bs.modal', function (e) {
    $(e.target)
        .find('.select2')
        .select2({
            dropdownParent: $(e.target),
            width: '100%'
        });
});

// ShowLiveTime

$(document).ready(function () {
    // Function to update the clock
    function updateTime() {
        var currentTime = new Date();

        // Format hours, minutes, and seconds with leading zeros
        var hours = currentTime.getHours();
        var minutes = currentTime.getMinutes();
        var seconds = currentTime.getSeconds();

        // Convert to 12-hour format and determine AM/PM
        var ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'

        // Add leading zeros to minutes and seconds if needed
        minutes = minutes < 10 ? '0' + minutes : minutes;
        seconds = seconds < 10 ? '0' + seconds : seconds;

        // Create the time string in the format HH:MM:SS AM/PM
        var timeString = hours + ':' + minutes + ':' + seconds + ' ' + ampm;

        // Update the content of the #liveTime element
        $('#liveTime').text(timeString);
    }

    // Call the updateTime function every second (1000 milliseconds)
    setInterval(updateTime, 1000);

    // Call the function initially to show time immediately when the page loads
    updateTime();
});

// LiveClockInTimeCount

$(document).ready(function () {
    const liveWorkingTimeElement = $('#liveWorkingTime');

    if (liveWorkingTimeElement.length) {
        const clockInAt =
            parseInt(liveWorkingTimeElement.data('clock-in-at')) * 1000; // Convert to milliseconds

        // Function to calculate and display the elapsed time
        function updateliveWorkingTime() {
            const now = new Date().getTime();
            const elapsed = now - clockInAt;

            // Calculate hours, minutes, and seconds
            const hours = Math.floor(elapsed / (1000 * 60 * 60));
            const minutes = Math.floor(
                (elapsed % (1000 * 60 * 60)) / (1000 * 60)
            );
            const seconds = Math.floor((elapsed % (1000 * 60)) / 1000);

            // Format the time as hh:mm:ss
            const formattedTime =
                String(hours).padStart(2, '0') +
                ':' +
                String(minutes).padStart(2, '0') +
                ':' +
                String(seconds).padStart(2, '0');

            liveWorkingTimeElement.text(formattedTime);
        }

        // Update the time every second
        updateliveWorkingTime(); // Initial call
        setInterval(updateliveWorkingTime, 1000); // Update every second
    }
});

// Recognition Notification Functions

function showRecognitionNotification() {
    const notification = document.getElementById('recognitionNotification');
    notification.classList.remove('d-none');

    // Auto close notification after 5 seconds
    setTimeout(() => {
        closeRecognitionNotification();
    }, 5000);
}

function closeRecognitionNotification() {
    const notification = document.getElementById('recognitionNotification');
    notification.classList.add('notification-closing');

    setTimeout(() => {
        notification.classList.add('d-none');
        notification.classList.remove('notification-closing');
    }, 300);
}

// Global function to show notification with custom data
function showCustomRecognition(data) {
    const notification = document.getElementById('recognitionNotification');

    // Update notification content with real data
    notification.querySelector('.fw-bold').textContent =
        data.employeeName || 'Employee Name';
    notification.querySelector('.recognition-points span').textContent = `${
        data.points || 0
    } pts`;
    notification.querySelector('.fw-medium').nextSibling.textContent =
        data.recognizedBy || 'Manager';
    notification.querySelector('.category-badge').innerHTML = `
        <i data-lucide="${
            data.categoryIcon || 'award'
        }" style="width: 16px; height: 16px;" class="me-2"></i>
        ${data.category || 'Recognition'}
    `;
    notification.querySelector('.fst-italic').textContent = `"${
        data.message || 'Great work!'
    }"`;

    // Reinitialize icons and show
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
    notification.classList.remove('d-none');

    // Auto close after 5 seconds
    setTimeout(() => {
        closeRecognitionNotification();
    }, 5000);
}

// Initialize Lucide icons when page loads
$(document).ready(function () {
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});

$(document).ready(function () {
    let submitting = false;

    function handleSubmit(buttonClass, attendanceType) {
        $(buttonClass).click(function () {
            if (submitting) return; // Prevent double click
            submitting = true;

            $('#attendanceType').val(attendanceType);
            $(this).prop('disabled', true); // Disable button
            $(this).closest('form').submit();
        });
    }

    handleSubmit('.submit-regular', 'Regular');
    handleSubmit('.submit-overtime', 'Overtime');
});

function createConfettiBirthday() {
    const confetti = document.createElement('div');
    confetti.classList.add('confetti');
    confetti.style.left = Math.random() * 100 + 'vw';
    confetti.style.animationDuration = Math.random() * 3 + 2 + 's';
    confetti.style.opacity = Math.random();
    confetti.style.transform = `rotate(${Math.random() * 360}deg)`;

    // Limit confetti to the content area instead of body
    document.querySelector('.content-wrapper').appendChild(confetti);

    setTimeout(() => {
        confetti.remove();
    }, 5000);
}

// Only create confetti if it's a birthday
if (document.querySelector('.birthday-wish-container')) {
    setInterval(createConfettiBirthday, 200);
}

// Employee Recognition - Slider Logic
(function () {
    let currentStepNumber = 1;
    let selectedUser = '';
    let selectedCategory = '';

    function updateStepIndicator() {
        const el = document.getElementById('currentStep');
        if (el) el.textContent = currentStepNumber;
    }

    window.nextStep = function () {
        if (currentStepNumber < 4) {
            const currentSlide = document.getElementById(`step${currentStepNumber}`);
            const nextSlide = document.getElementById(`step${currentStepNumber + 1}`);
            if (!currentSlide || !nextSlide) return;
            currentSlide.classList.remove('active');
            currentSlide.classList.add('slide-left');
            nextSlide.classList.remove('slide-right');
            nextSlide.classList.add('active');
            currentStepNumber++;
            updateStepIndicator();

            if (currentStepNumber === 3 && selectedUser) {
                const userSelect = document.getElementById('userSelect');
                if (userSelect) {
                    const selectedUserText =
                        userSelect.options[userSelect.selectedIndex].text.split(' - ')[0];
                    const title = document.getElementById('recognitionTitle');
                    if (title) title.textContent = `You are now recognizing ${selectedUserText}`;
                }
            }
        }
    };

    window.prevStep = function () {
        if (currentStepNumber > 1) {
            const currentSlide = document.getElementById(`step${currentStepNumber}`);
            const prevSlide = document.getElementById(`step${currentStepNumber - 1}`);
            if (!currentSlide || !prevSlide) return;
            currentSlide.classList.remove('active');
            currentSlide.classList.add('slide-right');
            prevSlide.classList.remove('slide-left');
            prevSlide.classList.add('active');
            currentStepNumber--;
            updateStepIndicator();
        }
    };

    function enableDisableNext(selectId, btnId, setter) {
        const select = document.getElementById(selectId);
        const btn = document.getElementById(btnId);
        if (!select || !btn) return;
        select.addEventListener('change', function () {
            if (this.value) {
                setter(this.value);
                btn.disabled = false;
                btn.classList.remove('opacity-50');
            } else {
                setter('');
                btn.disabled = true;
                btn.classList.add('opacity-50');
            }
        });
    }

    enableDisableNext('userSelect', 'userNextBtn', (v) => {
        selectedUser = v;
    });
    enableDisableNext('categorySelect', 'categoryNextBtn', (v) => {
        selectedCategory = v;
    });

    window.submitRecognition = function () {
        const userSelect = document.getElementById('userSelect');
        const categorySelect = document.getElementById('categorySelect');
        const pointsSelect = document.getElementById('pointsSelect');
        const messageText = (document.getElementById('messageText')?.value || '').trim();

        const userId = userSelect ? userSelect.value : '';
        const category = categorySelect ? categorySelect.value : '';
        const points = pointsSelect ? pointsSelect.value : '';
        const comment = messageText;
        const modalEl = document.getElementById('recognitionModal');

        // Basic client validation
        if (!userId || !category || !points || !comment) {
            Swal.fire({
                toast: true,
                position: "top-end",
                icon: "error",
                title: "Please fill all fields",
                showConfirmButton: false,
                timer: 2500
            });
            return;
        }

        // Close modal
        if (modalEl && window.bootstrap) {
            const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modal.hide();
        }

        // Send data via AJAX (fetch API)
        const storeUrl = modalEl?.dataset.storeUrl;
        if (!storeUrl) {
            console.error("Recognition store URL not found on modal");
            Swal.fire({
                toast: true,
                position: "top-end",
                icon: "error",
                title: "Cannot submit right now. Try reloading the page.",
                showConfirmButton: false,
                timer: 2000
            });
            return;
        }
        fetch(storeUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                user_id: userId,
                category: category,
                total_mark: points,
                comment: comment
            })
        })
        .then(async (response) => {
            if (!response.ok) {
                const errorData = await response.json();
                throw errorData;
            }
            return response.json();
        })
        .then((data) => {
            // ✅ SweetAlert Toast on success
            Swal.fire({
                toast: true,
                position: "top-end",
                icon: "success",
                title: "Recognition submitted successfully!",
                showConfirmButton: false,
                timer: 2000
            });

            resetModal();
        })
        .catch((error) => {
            console.error("Error:", error);
            Swal.fire({
                toast: true,
                position: "top-end",
                icon: "error",
                title: "Something went wrong!",
                text: error.message || "Please check input fields",
                showConfirmButton: false,
                timer: 2000
            });
        });
    };


    function resetModal() {
        currentStepNumber = 1;
        updateStepIndicator();
        document.querySelectorAll('#recognitionModal .slide').forEach((slide, index) => {
            slide.classList.remove('active', 'slide-left', 'slide-right');
            if (index === 0) slide.classList.add('active');
            else slide.classList.add('slide-right');
        });
        const userSelect = document.getElementById('userSelect');
        const categorySelect = document.getElementById('categorySelect');
        const messageText = document.getElementById('messageText');
        const pointsSelect = document.getElementById('pointsSelect');
        if (userSelect) userSelect.value = '';
        if (categorySelect) categorySelect.value = '';
        if (messageText) messageText.value = '';
        if (pointsSelect) pointsSelect.value = '1000';
        const userNextBtn = document.getElementById('userNextBtn');
        const categoryNextBtn = document.getElementById('categoryNextBtn');
        if (userNextBtn) {
            userNextBtn.disabled = true;
            userNextBtn.classList.add('opacity-50');
        }
        if (categoryNextBtn) {
            categoryNextBtn.disabled = true;
            categoryNextBtn.classList.add('opacity-50');
        }
        selectedUser = '';
        selectedCategory = '';
    }

    document.getElementById('recognitionModal')?.addEventListener('hidden.bs.modal', resetModal);

    // Initialize disabled button opacity on load
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('userNextBtn')?.classList.add('opacity-50');
        document.getElementById('categoryNextBtn')?.classList.add('opacity-50');
    });
})();

// Multi-User Recognition Carousel Logic
(function () {
    class MultiRecognitionCarousel {
        constructor() {
            this.currentSlide = 0;
            this.autoPlayInterval = null;
            this.isAutoPlaying = false;
            this.slideShowDuration = 10000; // 10 seconds per slide

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

            // Fix avatar clipping: hide horizontally (for slider) but allow vertical overflow
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

            this.modal.addEventListener('show.bs.modal', () => this.onModalShow());
            this.modal.addEventListener('hidden.bs.modal', () => this.onModalHide());
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

            // Trigger confetti for each slide
            setTimeout(() => {
                if (typeof createConfetti === 'function') {
                    createConfetti();
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
                // Loop back to first slide in autoplay
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

        toggleAutoPlay() {
            if (this.isAutoPlaying) {
                this.stopAutoPlay();
            } else {
                this.startAutoPlay();
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

            // Reset flip animation using a dedicated class
            flipCard.classList.remove('animate');
            flipCard.style.transform = 'rotateY(0deg)';
            // Force reflow to restart animation
            void flipCard.offsetWidth;
            setTimeout(() => {
                flipCard.classList.add('animate');
            }, 1000);
        }

        onModalShow() {
            this.currentSlide = 0;
            this.updateDisplay();
            // Ensure overflow settings in case stylesheets override them
            const carousel = this.modal?.querySelector('.recognition-carousel');
            if (carousel) {
                carousel.style.overflowX = 'hidden';
                carousel.style.overflowY = 'visible';
            }
            // Start autoplay automatically
            this.startAutoPlay();
        }

        onModalHide() {
            this.stopAutoPlay();
            if (this.confettiContainer) this.confettiContainer.innerHTML = '';
            this.currentSlide = 0;
        }
    }

    // Initialize when the page loads if the multi-user carousel exists
    document.addEventListener('DOMContentLoaded', () => {
        const hasMultiCarousel = document.querySelector('#recognizeCongratsModal .recognition-slides');
        if (hasMultiCarousel) {
            window.multiRecognitionCarousel = new MultiRecognitionCarousel();
        }
    });
})();
