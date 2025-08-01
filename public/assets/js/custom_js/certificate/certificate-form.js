/**
 * Certificate Form Dynamic Handler
 * Handles dynamic form field visibility based on certificate configuration
 */

class CertificateForm {
    constructor() {
        this.config = window.certificateConfig || {};
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

        // Get configuration for the selected certificate type
        const typeConfig = this.config[type];

        if (!typeConfig) {
            console.warn(`No configuration found for certificate type: ${type}`);
            return;
        }

        // Extract field names from required_fields and optional_fields (excluding basic fields)
        const basicFields = ['user_id', 'type', 'issue_date'];
        const requiredFields = typeConfig.required_fields.filter(field => !basicFields.includes(field));
        const optionalFields = typeConfig.optional_fields || [];

        // Show and set required fields
        if (requiredFields.length > 0) {
            this.showFields(requiredFields);
            this.setRequiredFields(requiredFields);
        }

        // Show and set optional fields
        if (optionalFields.length > 0) {
            this.showFields(optionalFields);
            this.setOptionalFields(optionalFields);
        }

        console.log(`Certificate type: ${type}`);
        console.log(`Required fields:`, requiredFields);
        console.log(`Optional fields:`, optionalFields);

        // Update form validation
        this.updateFormValidation();
    }

    hideAllConditionalFields() {
        // Get all possible fields from configuration
        const basicFields = ['user_id', 'type', 'issue_date'];
        const allFields = new Set();

        // Collect all fields from all certificate types
        Object.values(this.config).forEach(typeConfig => {
            typeConfig.required_fields.forEach(field => allFields.add(field));
            if (typeConfig.optional_fields) {
                typeConfig.optional_fields.forEach(field => allFields.add(field));
            }
        });

        // Filter out basic fields to get only conditional fields
        const conditionalFields = Array.from(allFields).filter(field => !basicFields.includes(field));

        // Hide all conditional fields and remove required attributes
        conditionalFields.forEach(field => {
            const fieldElement = $(`[name="${field}"]`);
            const containerElement = fieldElement.closest('.mb-3');

            containerElement.hide();
            fieldElement.removeAttr('required');

            // Remove required indicator from label
            containerElement.find('label .text-danger').remove();
        });
    }

    showFields(fields) {
        fields.forEach(field => {
            $(`[name="${field}"]`).closest('.mb-3').show();
        });
    }

    setRequiredFields(fields) {
        fields.forEach(field => {
            const fieldElement = $(`[name="${field}"]`);
            const containerElement = fieldElement.closest('.mb-3');
            const labelElement = containerElement.find('label');

            // Set required attribute
            fieldElement.attr('required', true);

            // Add required indicator to label if not already present
            if (!labelElement.find('.text-danger').length) {
                labelElement.append(' <strong class="text-danger">*</strong>');
            }
        });
    }

    setOptionalFields(fields) {
        fields.forEach(field => {
            const fieldElement = $(`[name="${field}"]`);
            const containerElement = fieldElement.closest('.mb-3');
            const labelElement = containerElement.find('label');

            // Remove required attribute
            fieldElement.removeAttr('required');

            // Remove required indicator from label
            labelElement.find('.text-danger').remove();
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
