<?php

use App\Models\Certificate\Certificate;

if (!function_exists('certificate_get_template_path')) {
    /**
     * Get certificate template path for the given certificate type.
     *
     * @param string $certificateType Certificate type
     * @return string Template path
     */
    function certificate_get_template_path(string $certificateType): string
    {
        $typeConfig = config("certificate.types.{$certificateType}");
        $template = $typeConfig['template'] ?? 'employment_certificate';

        return "administration.certificate.templates.{$template}";
    }
}

if (!function_exists('certificate_get_type_badge_class')) {
    /**
     * Get certificate type badge CSS class.
     *
     * @param string $type Certificate type
     * @return string Badge CSS class
     */
    function certificate_get_type_badge_class(string $type): string
    {
        $typeConfig = config("certificate.types.{$type}");
        return $typeConfig['badge_class'] ?? 'badge-primary';
    }
}

if (!function_exists('certificate_get_type_icon')) {
    /**
     * Get certificate type icon class.
     *
     * @param string $type Certificate type
     * @return string Icon CSS class
     */
    function certificate_get_type_icon(string $type): string
    {
        $typeConfig = config("certificate.types.{$type}");
        return $typeConfig['icon'] ?? 'ti-certificate';
    }
}

if (!function_exists('certificate_get_required_fields')) {
    /**
     * Get required fields for certificate type.
     *
     * @param string $type Certificate type
     * @return array Required fields
     */
    function certificate_get_required_fields(string $type): array
    {
        $typeConfig = config("certificate.types.{$type}");
        return $typeConfig['required_fields'] ?? [];
    }
}

if (!function_exists('certificate_get_optional_fields')) {
    /**
     * Get optional fields for certificate type.
     *
     * @param string $type Certificate type
     * @return array Optional fields
     */
    function certificate_get_optional_fields(string $type): array
    {
        $typeConfig = config("certificate.types.{$type}");
        return $typeConfig['optional_fields'] ?? [];
    }
}

if (!function_exists('certificate_get_field_labels')) {
    /**
     * Get field labels for certificate forms.
     *
     * @return array Field labels
     */
    function certificate_get_field_labels(): array
    {
        return config('certificate.field_labels', []);
    }
}

if (!function_exists('certificate_get_field_descriptions')) {
    /**
     * Get field descriptions for certificate forms.
     *
     * @return array Field descriptions
     */
    function certificate_get_field_descriptions(): array
    {
        return config('certificate.field_descriptions', []);
    }
}

if (!function_exists('certificate_validate_data')) {
    /**
     * Validate certificate data against required fields.
     *
     * @param array $data Certificate data
     * @param string $type Certificate type
     * @return array Missing required fields
     */
    function certificate_validate_data(array $data, string $type): array
    {
        $requiredFields = certificate_get_required_fields($type);
        $missingFields = [];

        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                $missingFields[] = $field;
            }
        }

        return $missingFields;
    }
}

if (!function_exists('certificate_format_data_for_display')) {
    /**
     * Format certificate data for display.
     *
     * @param Certificate $certificate Certificate instance
     * @return array Formatted data
     */
    function certificate_format_data_for_display(Certificate $certificate): array
    {
        $formatted = [];
        $fieldLabels = certificate_get_field_labels();

        foreach ($certificate->getAttributes() as $key => $value) {
            if (isset($fieldLabels[$key]) && !empty($value)) {
                $formatted[$fieldLabels[$key]] = $value;
            }
        }

        return $formatted;
    }
}

if (!function_exists('certificate_get_status')) {
    /**
     * Get certificate status.
     *
     * @param Certificate $certificate Certificate instance
     * @return string Certificate status
     */
    function certificate_get_status(Certificate $certificate): string
    {
        // You can add logic here to determine certificate status based on certificate data
        // For example: check if certificate is expired, revoked, etc.

        // For now, all certificates are considered active
        return 'Active';
    }
}

if (!function_exists('certificate_get_status_badge_class')) {
    /**
     * Get certificate status badge CSS class.
     *
     * @param string $status Certificate status
     * @return string Badge CSS class
     */
    function certificate_get_status_badge_class(string $status): string
    {
        $badgeMap = [
            'Active' => 'badge-success',
            'Inactive' => 'badge-secondary',
            'Expired' => 'badge-danger'
        ];

        return $badgeMap[$status] ?? 'badge-secondary';
    }
}

if (!function_exists('certificate_get_types')) {
    /**
     * Get all available certificate types.
     *
     * @return array Certificate types
     */
    function certificate_get_types(): array
    {
        return array_keys(config('certificate.types', []));
    }
}

if (!function_exists('certificate_get_type_config')) {
    /**
     * Get configuration for a specific certificate type.
     *
     * @param string|null $type Certificate type (null to get all types)
     * @return array|null Type configuration
     */
    function certificate_get_type_config(?string $type = null): ?array
    {
        $types = config('certificate.types', []);

        if ($type) {
            return $types[$type] ?? null;
        }

        return $types;
    }
}

if (!function_exists('certificate_get_company_info')) {
    /**
     * Get company information for certificates.
     *
     * @return array Company information
     */
    function certificate_get_company_info(): array
    {
        return config('certificate.company', []);
    }
}

if (!function_exists('certificate_generate_reference_number')) {
    /**
     * Generate a unique certificate reference number.
     *
     * @return string Unique reference number
     */
    function certificate_generate_reference_number(): string
    {
        return Certificate::generateUniqueReferenceNumber();
    }
}

if (!function_exists('certificate_format_reference_number')) {
    /**
     * Format reference number for display.
     *
     * @param string $referenceNo Raw reference number
     * @return string Formatted reference number
     */
    function certificate_format_reference_number(string $referenceNo): string
    {
        return 'CERT-' . $referenceNo;
    }
}
