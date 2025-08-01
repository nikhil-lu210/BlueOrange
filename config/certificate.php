<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Certificate Types Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration defines all available certificate types and their
    | field requirements. You can easily add new certificate types here.
    |
    */

    'types' => [
        'Appointment Letter' => [
            'label' => 'Appointment Letter',
            'template' => 'appointment_letter',
            'icon' => 'ti-user-plus',
            'badge_class' => 'badge-success',
            'required_fields' => [
                'user_id',
                'type',
                'issue_date',
                'salary'
            ],
            'optional_fields' => [],
            'description' => 'New employee appointment letter with salary details'
        ],

        'Employment Certificate' => [
            'label' => 'Employment Certificate',
            'template' => 'employment_certificate',
            'icon' => 'ti-certificate',
            'badge_class' => 'badge-primary',
            'required_fields' => [
                'user_id',
                'type',
                'issue_date',
                'salary'
            ],
            'optional_fields' => [],
            'description' => 'Professional employment verification certificate'
        ],

        'Experience Letter' => [
            'label' => 'Experience Letter',
            'template' => 'experience_letter',
            'icon' => 'ti-briefcase',
            'badge_class' => 'badge-info',
            'required_fields' => [
                'user_id',
                'type',
                'issue_date',
                'resignation_date'
            ],
            'optional_fields' => [],
            'description' => 'Work experience certification letter'
        ],

        'Release Letter by Employee' => [
            'label' => 'Release Letter by Employee',
            'template' => 'release_letter_employee_resigned',
            'icon' => 'ti-user-minus',
            'badge_class' => 'badge-warning',
            'required_fields' => [
                'user_id',
                'type',
                'issue_date',
                'resignation_date',
                'resign_application_date',
                'resignation_approval_date',
                'release_date',
                'release_reason'
            ],
            'optional_fields' => [],
            'description' => 'Release letter for resigned employee'
        ],

        'Release Letter by Employer' => [
            'label' => 'Release Letter by Employer',
            'template' => 'release_letter_employer',
            'icon' => 'ti-user-x',
            'badge_class' => 'badge-danger',
            'required_fields' => [
                'user_id',
                'type',
                'issue_date',
                'release_date',
                'release_reason'
            ],
            'optional_fields' => [],
            'description' => 'Employer-initiated release letter with reason'
        ],

        'NOC/No Objection Letter' => [
            'label' => 'NOC/No Objection Letter',
            'template' => 'noc_letter',
            'icon' => 'ti-plane',
            'badge_class' => 'badge-secondary',
            'required_fields' => [
                'user_id',
                'type',
                'issue_date',
                'country_name',
                'visiting_purpose',
                'leave_starts_from'
            ],
            'optional_fields' => [
                'leave_ends_on'
            ],
            'description' => 'No objection certificate for travel purposes'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Field Labels and Descriptions
    |--------------------------------------------------------------------------
    |
    | Human-readable labels and descriptions for form fields
    |
    */

    'field_labels' => [
        'user_id' => 'Employee',
        'type' => 'Certificate Type',
        'issue_date' => 'Issue Date',
        'salary' => 'Salary',
        'resignation_date' => 'Resignation Date',
        'resign_application_date' => 'Resign Application Date',
        'resignation_approval_date' => 'Resign Approval Date',
        'release_date' => 'Release Date',
        'release_reason' => 'Release Reason',
        'country_name' => 'Country Name',
        'visiting_purpose' => 'Visiting Purpose',
        'leave_starts_from' => 'Leave Starts From',
        'leave_ends_on' => 'Leave Ends On'
    ],

    'field_descriptions' => [
        'user_id' => 'Select the employee for whom the certificate is being generated',
        'type' => 'Choose the type of certificate to generate',
        'issue_date' => 'Date when the certificate is being issued',
        'salary' => 'Monthly salary amount in BDT',
        'resignation_date' => 'Date when the employee resigned',
        'resign_application_date' => 'Date when the employee submitted resignation application',
        'resignation_approval_date' => 'Date when the resignation was approved by management',
        'release_date' => 'Date when the employee is being released',
        'release_reason' => 'Reason for releasing the employee',
        'country_name' => 'Name of the country to visit',
        'visiting_purpose' => 'Purpose of the visit',
        'leave_starts_from' => 'Date when the leave starts',
        'leave_ends_on' => 'Date when the leave ends (optional)'
    ],

    /*
    |--------------------------------------------------------------------------
    | Company Information
    |--------------------------------------------------------------------------
    |
    | Default company information for certificates
    |
    */

    'company' => [
        'name' => 'Staff-India',
        'logo' => 'Logo/si_logo_png.png',
        'signature' => [
            'name' => 'MD. Abdul Razzak Chowdhury',
            'designation' => 'General Manager',
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Print Settings
    |--------------------------------------------------------------------------
    |
    | Default print settings for certificates
    |
    */

    'print' => [
        'page_size' => 'A4',
        'orientation' => 'portrait',
        'margin' => '20mm',
        'font_family' => 'Montserrat, sans-serif'
    ]
];
