document.addEventListener('DOMContentLoaded', () => {
    const popup = document.getElementById('suggestionPopup');
    const overlay = document.getElementById('suggestionOverlay');
    const openBtn = document.getElementById('suggestionBtn');
    const openBtnIcon = openBtn ? openBtn.querySelector('i') : null;
    const openBtnText = openBtn ? openBtn.querySelector('.btn-text') : null;
    const closeBtn = document.getElementById('closeSuggestion');
    const cancelBtn = document.getElementById('cancelSuggestion');
    const form = document.getElementById('suggestionForm');

    if (!popup || !overlay || !openBtn || !closeBtn) {
        console.log('Suggestion popup elements not found.');
        return;
    }

    const lockScroll = () => { document.body.style.overflow = 'hidden'; };
    const unlockScroll = () => { document.body.style.overflow = ''; };

    const setOpenButtonAsClose = () => {
        if (!openBtn) return;
        openBtn.classList.add('is-open');
        if (openBtnText) {
            openBtnText.style.display = 'none';
        }
        if (openBtnIcon) {
            openBtnIcon.className = 'ti ti-x';
            openBtnIcon.style.marginLeft = '-2px';
        }
        // lock to circular close icon appearance
        openBtn.style.width = '3.2rem';
        openBtn.style.borderRadius = '50%';
        openBtn.style.justifyContent = 'center';
        openBtn.style.paddingLeft = '';
    };

    const resetOpenButton = () => {
        if (!openBtn) return;
        openBtn.classList.remove('is-open');
        if (openBtnText) {
            openBtnText.style.display = '';
        }
        if (openBtnIcon) {
            openBtnIcon.className = 'ti ti-message-plus';
            openBtnIcon.style.marginLeft = '5px';
        }
        openBtn.style.width = '';
        openBtn.style.borderRadius = '';
        openBtn.style.justifyContent = '';
        openBtn.style.paddingLeft = '';
    };

    const openPopup = () => {
        popup.classList.remove('d-none');
        overlay.classList.remove('d-none');
        lockScroll();
        setTimeout(() => {
            popup.classList.add('show');
            overlay.classList.add('show');
        }, 10);
        setOpenButtonAsClose();
    };

    const closePopup = () => {
        popup.classList.remove('show');
        overlay.classList.remove('show');
        unlockScroll();
        setTimeout(() => {
            popup.classList.add('d-none');
            overlay.classList.add('d-none');
        }, 300);
        resetOpenButton();
    };

    openBtn.addEventListener('click', (e) => {
        e.preventDefault();
        // toggle: if already open, act as close
        if (openBtn.classList.contains('is-open')) {
            closePopup();
        } else {
            openPopup();
        }
    });
    
    closeBtn.addEventListener('click', closePopup);
    if (cancelBtn) cancelBtn.addEventListener('click', closePopup);
    overlay.addEventListener('click', closePopup);

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !popup.classList.contains('d-none')) {
            closePopup();
        }
    });

    // if (form) {
    //     form.addEventListener('submit', async (e) => {
    //         e.preventDefault();
    //         const formData = new FormData(form);
    //         console.log(formData.get('title'));
    //         console.log(formData.get('type'));
    //         console.log(formData.get('module'));
    //         console.log(formData.get('message'));
    //         try {
    //             const response = await fetch(form.action, {
    //                 method: 'POST',
    //                 headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value },
    //                 body: formData,
    //             });

    //             const data = await response.json();

    //             if (data.success) {
    //                 alert('Thank you! Your suggestion has been submitted.');
    //                 form.reset();
    //                 closePopup();
    //             } else {
    //                 alert('Something went wrong. Please try again.');
    //             }
    //         } catch (err) {
    //             alert('Network error. Please try again.');
    //         }
    //     });
    // }

    // --- Auto attention trigger after 5 minutes ---
    // const notifyDelay = 1 * 60 * 1000; // 5 minutes (in milliseconds)
    // let userInteracted = false;

    // // If user interacts (clicks or hovers), cancel auto animation
    // const resetTimer = () => { userInteracted = true; };
    // openBtn.addEventListener('mouseenter', resetTimer);
    // openBtn.addEventListener('click', resetTimer);
    // document.addEventListener('mousemove', resetTimer, { once: true });
    // document.addEventListener('keydown', resetTimer, { once: true });

    // // Timer to auto-shake or expand button
    // setTimeout(() => {
    //     if (!userInteracted && openBtn && !openBtn.classList.contains('is-open')) {
    //         // Choose one effect (shake or expand)
    //         openBtn.classList.add('shake');
    //         // After animation ends, remove the class to allow re-trigger later
    //         openBtn.addEventListener('animationend', () => {
    //             openBtn.classList.remove('shake');
    //         }, { once: true });
    //     }
    // }, notifyDelay);

});