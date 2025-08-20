(function () {
    function $(selector, root = document) {
        return root.querySelector(selector);
    }

    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function isValidImageFile(file) {
        if (!file) return false;
        const isImage = file.type.startsWith('image/');
        const maxBytes = 5 * 1024 * 1024; // 5MB
        return isImage && file.size <= maxBytes;
    }

    function readFileAsDataURL(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = () => resolve(reader.result);
            reader.onerror = reject;
            reader.readAsDataURL(file);
        });
    }

    async function confirmAndUpload(file, uploadUrl, imgEl) {
        if (!isValidImageFile(file)) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid file',
                text: 'Please select an image up to 5MB.',
            });
            return;
        }

        let previewUrl = '';
        try {
            previewUrl = await readFileAsDataURL(file);
        } catch (e) {
            // ignore preview errors
        }

        const { isConfirmed } = await Swal.fire({
            title: 'Update profile picture?',
            imageUrl: previewUrl || undefined,
            imageAlt: 'New profile picture preview',
            showCancelButton: true,
            confirmButtonText: 'Yes, update',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
        });

        if (!isConfirmed) return;

        const formData = new FormData();
        formData.append('avatar', file);

        const headers = {
            'X-Requested-With': 'XMLHttpRequest',
        };
        const csrf = getCsrfToken();
        if (csrf) headers['X-CSRF-TOKEN'] = csrf;

        try {
            Swal.fire({
                title: 'Uploading...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading(),
            });

            const res = await fetch(uploadUrl, {
                method: 'POST',
                headers,
                body: formData,
                credentials: 'same-origin',
            });

            if (!res.ok) {
                let msg = 'Failed to update profile picture.';
                try {
                    const data = await res.json();
                    if (data && data.message) msg = data.message;
                } catch (_) {
                    // ignore parse errors
                }
                throw new Error(msg);
            }

            const data = await res.json();
            const newUrl = (data && data.url) ? data.url : imgEl.src;

            // Cache-bust to ensure the browser fetches the latest image
            const bust = newUrl.includes('?') ? `&t=${Date.now()}` : `?t=${Date.now()}`;
            imgEl.src = newUrl + bust;

            Swal.close();
            Swal.fire({
                toast: true,
                icon: 'success',
                title: 'Profile picture updated',
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
            });
        } catch (err) {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: err.message || 'Failed to update profile picture.',
            });
        }
    }

    function initAvatarDropzone() {
        const dropzone = $('#avatar-dropzone');
        if (!dropzone) return;

        const imgEl = $('#user-avatar-img', dropzone);
        const fileInput = $('#avatar-file-input', dropzone);
        const uploadUrl = dropzone.getAttribute('data-upload-url');

        if (!uploadUrl) return;

        // Click to select
        dropzone.addEventListener('click', (e) => {
            // Avoid clicking through overlay to link etc.
            const target = e.target;
            if (target && target.id === 'avatar-file-input') return;
            fileInput && fileInput.click();
        });

        // Handle file input change
        fileInput && fileInput.addEventListener('change', (e) => {
            const file = e.target.files && e.target.files[0];
            if (!file) return;
            confirmAndUpload(file, uploadUrl, imgEl);
            // reset input so selecting the same file again triggers change
            e.target.value = '';
        });

        // Drag & drop
        ['dragenter', 'dragover'].forEach(evtName => {
            dropzone.addEventListener(evtName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('drag-over');
            });
        });

        ['dragleave', 'drop'].forEach(evtName => {
            dropzone.addEventListener(evtName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('drag-over');
            });
        });

        dropzone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            if (!dt || !dt.files || !dt.files.length) return;
            const file = dt.files[0];
            confirmAndUpload(file, uploadUrl, imgEl);
        });
    }

    document.addEventListener('DOMContentLoaded', initAvatarDropzone);
})();