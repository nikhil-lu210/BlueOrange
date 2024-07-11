function initializeDropzone(formSelector, dropzoneSelector) {
    $(document).ready(function() {
        'use strict';

        const previewTemplate = `<div class="dz-preview dz-file-preview">
            <div class="dz-details">
                <div class="dz-thumbnail">
                    <img data-dz-thumbnail>
                    <span class="dz-nopreview">No preview</span>
                    <div class="dz-success-mark"></div>
                    <div class="dz-error-mark"></div>
                    <div class="dz-error-message"><span data-dz-errormessage></span></div>
                    <div class="progress">
                        <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
                    </div>
                </div>
                <div class="dz-filename" data-dz-name></div>
                <div class="dz-size" data-dz-size></div>
            </div>
        </div>`;

        const form = $(formSelector);
        const dropzoneElement = form.find(dropzoneSelector);
        
        if (dropzoneElement.length) {
            const inputElement = dropzoneElement.find('input[type="file"]');
            const paramName = inputElement.attr('name') || 'files[]'; // Default to 'files[]' if not specified
            
            const dropzone = new Dropzone(dropzoneElement[0], {
                url: form.attr('action'),
                previewTemplate: previewTemplate,
                parallelUploads: 1,
                maxFilesize: 5,
                addRemoveLinks: true,
                autoProcessQueue: false,
                clickable: true,
                paramName: paramName
            });

            form.submit(function(e) {
                // If there are files in the queue, prevent the default form submit
                if (dropzone.getQueuedFiles().length > 0) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.processQueue();
                }
            });

            dropzone.on("sending", function(file, xhr, formData) {
                // Append all form data to the request
                form.serializeArray().forEach(function(input) {
                    formData.append(input.name, input.value);
                });
            });

            dropzone.on("queuecomplete", function() {
                // Re-submit the form once all files are uploaded
                form.off('submit').submit();
            });
        }
    });
}
