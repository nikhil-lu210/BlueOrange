/**
 * Certificate Form Dynamic Handler
 * Handles dynamic form field visibility based on certificate type
 */

class CertificateForm {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
        this.initializeForm();
    }

    bindEvents() {
        // Certificate type change handler
        $(document).on('change', '#type', (e) => {
            this.handleTypeChange($(e.target).val());
        });

        // Form submission handler
        $(document).on('submit', 'form[action*="generate"]', (e) => {
            this.handleFormSubmission(e);
        });

        // Date picker initialization
        this.initializeDatePickers();

        // Select2 initialization
        this.initializeSelect2();

        // Bootstrap Select initialization
        this.initializeBootstrapSelect();
    }

    initializeForm() {
        // Hide all conditional fields initially
        this.hideAllConditionalFields();

        // Show fields based on current selection
        const currentType = $('#type').val();
        if (currentType) {
            this.handleTypeChange(currentType);
        }
    }

    handleTypeChange(type) {
        // Hide all conditional fields first
        this.hideAllConditionalFields();

        // Show fields based on selected type according to form comments
        switch (type) {
            case 'Appointment Letter':
                // Joining Date and Salary are required for Appointment Letter
                this.showFields(['joining_date', 'salary']);
                this.setRequiredFields(['joining_date', 'salary']);
                break;

            case 'Employment Certificate':
                // Joining Date and Salary are required for Employment Certificate
                this.showFields(['joining_date', 'salary']);
                this.setRequiredFields(['joining_date', 'salary']);
                break;

            case 'Experience Letter':
                // Only Resignation Date is required for Experience Letter
                this.showFields(['resignation_date']);
                this.setRequiredFields(['resignation_date']);
                break;

            case 'Release Letter':
                // Release Date and Release Reason are required for Release Letter
                this.showFields(['release_date', 'release_reason']);
                this.setRequiredFields(['release_date', 'release_reason']);
                break;

            case 'NOC/No Objection Letter':
                // Country Name, Visiting Purpose, Leave Starts From are required
                // Leave Ends On is optional for NOC Letter
                this.showFields(['country_name', 'visiting_purpose', 'leave_starts_from', 'leave_ends_on']);
                this.setRequiredFields(['country_name', 'visiting_purpose', 'leave_starts_from']);
                this.setOptionalFields(['leave_ends_on']);
                break;

            default:
                this.hideAllConditionalFields();
                break;
        }

        // Update form validation
        this.updateFormValidation();
    }

    hideAllConditionalFields() {
        const conditionalFields = [
            'joining_date', 'salary', 'resignation_date', 'release_date',
            'release_reason', 'country_name', 'visiting_purpose',
            'leave_starts_from', 'leave_ends_on'
        ];

        conditionalFields.forEach(field => {
            $(`[name="${field}"]`).closest('.mb-3').hide();
            $(`[name="${field}"]`).removeAttr('required');
        });
    }

    showFields(fields) {
        fields.forEach(field => {
            $(`[name="${field}"]`).closest('.mb-3').show();
        });
    }

    setRequiredFields(fields) {
        fields.forEach(field => {
            $(`[name="${field}"]`).attr('required', true);
            // Update label to show required indicator
            const label = $(`label[for="${field}"]`);
            if (!label.find('.text-danger').length) {
                label.append(' <strong class="text-danger">*</strong>');
            }
        });
    }

    setOptionalFields(fields) {
        fields.forEach(field => {
            $(`[name="${field}"]`).removeAttr('required');
            // Remove required indicator from label
            $(`label[for="${field}"] .text-danger`).remove();
        });
    }

    updateFormValidation() {
        // You can add custom validation logic here
        console.log('Form validation updated');
    }

    handleFormSubmission(e) {
        // Add loading state
        const submitBtn = $(e.target).find('button[type="submit"]');
        const originalText = submitBtn.html();

        submitBtn.prop('disabled', true);
        submitBtn.html('<span class="spinner-border spinner-border-sm me-2" role="status"></span>Generating...');

        // Reset button after 5 seconds (fallback)
        setTimeout(() => {
            submitBtn.prop('disabled', false);
            submitBtn.html(originalText);
        }, 5000);
    }

    initializeDatePickers() {
        $('.date-picker').each(function() {
            if (!$(this).hasClass('datepicker-initialized')) {
                $(this).datepicker({
                    format: 'yyyy-mm-dd',
                    todayHighlight: true,
                    autoclose: true,
                    orientation: 'auto right'
                }).addClass('datepicker-initialized');
            }
        });
    }

    initializeSelect2() {
        // Select2 is initialized in the main template
        // This method is kept for compatibility but doesn't reinitialize
        console.log('Select2 initialization handled by main template');
    }

    initializeBootstrapSelect() {
        $('.bootstrap-select').each(function() {
            if (!$(this).data('bs.select')) {
                $(this).selectpicker({
                    style: 'btn-default',
                    size: 4,
                    liveSearch: false,
                    showTick: true
                });
            }
        });
    }

    // Utility methods
    static showToast(message, type = 'success') {
        // Assuming you have a toast notification system
        if (typeof toast === 'function') {
            toast(message, type);
        } else {
            console.log(`${type.toUpperCase()}: ${message}`);
        }
    }

    static showError(message) {
        this.showToast(message, 'error');
    }

    static showSuccess(message) {
        this.showToast(message, 'success');
    }
}

// Initialize when document is ready
$(document).ready(function() {
    new CertificateForm();
});

// Export for use in other scripts
window.CertificateForm = CertificateForm;
