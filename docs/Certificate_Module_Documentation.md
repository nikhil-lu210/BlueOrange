# Certificate Module Documentation

## Overview
The Certificate Module is a comprehensive system for generating, managing, and printing various types of employee certificates. It provides a user-friendly interface for creating professional certificates with dynamic templates and proper validation.

## Features

### ✅ Certificate Types Supported
1. **Appointment Letter** - New employee appointment with salary details
2. **Employment Certificate** - Professional employment verification
3. **Experience Letter** - Work experience certification with leave details
4. **Release Letter** - Employee release with reason
5. **NOC/No Objection Letter** - Travel permission with country/purpose details

### ✅ Key Functionality
- **Dynamic Form Fields** - Form fields change based on certificate type
- **Real-time Preview** - Live preview before saving
- **Professional Templates** - Print-ready A4 templates
- **Unique Reference Numbers** - Auto-generated 10-character alphanumeric reference numbers
- **Database Storage** - All certificates stored for future reference
- **Permission-based Access** - Role-based access control
- **Responsive Design** - Works on all devices

## File Structure

```
Certificate Module Structure:
├── app/
│   ├── Http/
│   │   ├── Controllers/Administration/Certificate/
│   │   │   └── CertificateController.php
│   │   └── Requests/Administration/Certificate/
│   │       └── CertificateRequest.php
│   ├── Models/Certificate/
│   │   ├── Certificate.php
│   │   ├── Accessors/CertificateAccessors.php
│   │   ├── Mutators/CertificateMutators.php
│   │   └── Relations/CertificateRelations.php
│   ├── Services/Administration/Certificate/
│   │   └── CertificateService.php
│   └── Helpers/
│       └── CertificateHelper.php
├── database/migrations/
│   └── 2025_07_30_000000_create_certificates_table.php
├── resources/views/administration/certificate/
│   ├── index.blade.php
│   ├── my.blade.php
│   ├── create.blade.php
│   ├── show.blade.php
│   ├── print.blade.php
│   ├── includes/
│   │   ├── generate_form.blade.php
│   │   └── generated_certificate.blade.php
│   └── templates/
│       ├── appointment_letter.blade.php
│       ├── employment_certificate.blade.php
│       ├── experience_letter.blade.php
│       ├── release_letter.blade.php
│       └── noc_letter.blade.php
├── routes/administration/certificate/
│   └── certificate.php
└── public/assets/js/custom_js/certificate/
    └── certificate-form.js
```

## Installation & Setup

### 1. Run Migration
```bash
php artisan migrate
```

### 2. Assign Permissions
Ensure users have appropriate permissions:
- `Certificate Everything` - Full access
- `Certificate Create` - Create certificates
- `Certificate Read` - View certificates
- `Certificate Update` - Edit certificates
- `Certificate Delete` - Delete certificates

### 3. Configure Certificate Types
Certificate types are now configurable in `config/certificate.php`. You can:
- Add new certificate types
- Modify field requirements
- Update templates and styling
- Customize company information

### 4. Ensure Employee Data
Make sure users have associated employee records, as the dropdown only shows users with employee relationships.

## Usage Guide

### Creating a Certificate

1. **Navigate to Certificate Module**
   - Go to Administration → Certificate → Create Certificate

2. **Select Employee**
   - Choose the employee from the dropdown

3. **Choose Certificate Type**
   - Select the type of certificate needed
   - Form fields will dynamically appear based on selection

4. **Fill Required Information**
   - Complete all required fields (marked with *)
   - Optional fields can be left blank

5. **Generate Preview**
   - Click "Generate Certificate" to see preview
   - Review the certificate content

6. **Create & Issue**
   - If satisfied with preview, click "Create & Issue Certificate"
   - Certificate will be saved to database

### Viewing Certificates

1. **All Certificates** - View all certificates (admin access)
2. **My Certificates** - View user's own certificates
3. **Certificate Details** - Click on any certificate to view details

### Printing Certificates

1. **From Certificate List** - Click printer icon
2. **From Certificate Details** - Click "Print Certificate" button
3. **Print Options** - Browser print dialog will open with A4 settings

## Technical Details

### Database Schema

```sql
certificates table:
- id (primary key)
- reference_no (string, unique, 10 characters)
- user_id (foreign key to users)
- creator_id (foreign key to users)
- type (enum: certificate types)
- issue_date (date)
- joining_date (date, nullable)
- salary (decimal, nullable)
- resignation_date (date, nullable)
- release_date (date, nullable)
- release_reason (string, nullable)
- country_name (string, nullable)
- visiting_purpose (string, nullable)
- leave_starts_from (date, nullable)
- leave_ends_on (date, nullable)
- created_at (timestamp)
- updated_at (timestamp)
- deleted_at (timestamp, nullable)
```

### Validation Rules

Each certificate type has specific validation rules:

**Appointment Letter:**
- user_id, type, issue_date, joining_date, salary (required)

**Employment Certificate:**
- user_id, type, issue_date, joining_date (required)
- salary (optional)

**Experience Letter:**
- user_id, type, issue_date, joining_date, resignation_date (required)
- leave_starts_from, leave_ends_on (optional)

**Release Letter:**
- user_id, type, issue_date, joining_date, release_date, release_reason (required)

**NOC Letter:**
- user_id, type, issue_date, joining_date, country_name, visiting_purpose, leave_starts_from (required)
- leave_ends_on (optional)

### API Endpoints

```php
GET    /certificate/all          - List all certificates
GET    /certificate/my           - List user's certificates
GET    /certificate/create       - Show create form
GET    /certificate/generate     - Generate preview
POST   /certificate/store        - Store certificate
GET    /certificate/show/{id}    - Show certificate details
GET    /certificate/print/{id}   - Print certificate
GET    /certificate/destroy/{id} - Delete certificate
```

## Configuration

### Certificate Types Configuration

Certificate types are now managed through `config/certificate.php`. This makes it easy to add new types without modifying core code.

```php
'types' => [
    'New Certificate Type' => [
        'label' => 'New Certificate Type',
        'template' => 'new_certificate_template',
        'icon' => 'ti-certificate',
        'badge_class' => 'badge-primary',
        'required_fields' => [
            'user_id',
            'type',
            'issue_date',
            // Add other required fields
        ],
        'optional_fields' => [
            // Add optional fields
        ],
        'description' => 'Description of the new certificate type'
    ],
]
```

## Customization

### Adding New Certificate Types

1. **Add to Configuration**
   - Open `config/certificate.php`
   - Add new certificate type with required configuration

2. **Create Template**
   - Create new blade template in `templates/` directory
   - Follow existing template structure

3. **Update Validation (if needed)**
   - The validation automatically uses the config
   - Add custom validation rules in `CertificateRequest.php` if needed

4. **Update JavaScript (if needed)**
   - Add new type handling in `certificate-form.js` if special field handling is required

### Customizing Templates

Templates are located in `resources/views/administration/certificate/templates/`

Each template receives a `$certificate` object with:
- User information (`$certificate->user`)
- Employee details (`$certificate->user->employee`)
- Certificate data (dates, salary, etc.)
- Formatted accessors (e.g., `$certificate->formatted_issue_date`)

## Troubleshooting

### Common Issues

1. **Undefined Variable Error**
   - Ensure all variables are properly initialized in controllers
   - Check if relationships are loaded

2. **Template Not Found**
   - Verify template file exists in correct directory
   - Check template name mapping in `getTemplateName()` method

3. **Validation Errors**
   - Check field requirements for each certificate type
   - Ensure all required fields are filled

4. **Print Layout Issues**
   - Check CSS print styles in templates
   - Verify A4 page settings

### Debug Mode

Enable debug mode to see detailed error messages:
```php
// In .env file
APP_DEBUG=true
```

## Security Considerations

1. **Permission Checks** - All routes are protected with appropriate permissions
2. **Input Validation** - All inputs are validated using Form Requests
3. **SQL Injection Prevention** - Using Eloquent ORM prevents SQL injection
4. **XSS Protection** - All outputs are properly escaped

## Performance Optimization

1. **Eager Loading** - Relationships are loaded efficiently
2. **Pagination** - Large lists are paginated
3. **Caching** - Consider implementing caching for frequently accessed data
4. **Database Indexing** - Ensure proper indexes on foreign keys

## Testing

### 🔍 **Testing Checklist:**

1. ✅ Employee dropdown populates with users who have employee records
2. ✅ Certificate types load from configuration
3. ✅ Field visibility changes correctly based on certificate type
4. ✅ Validation matches field requirements exactly
5. ✅ New certificate types can be added via config only
6. ✅ Helper functions follow proper Laravel conventions
7. ✅ All helper functions use `certificate_` prefix

### 🧪 **Testing the Module:**

You can test the certificate functionality by visiting `/test-certificate` route which will show:
- Number of employees available for dropdown
- List of employees with their data
- Available certificate types from config
- Helper function test

### 🔧 **Helper Functions Available:**

All helper functions use the `certificate_` prefix and follow Laravel conventions:

- `certificate_get_types()` - Get all certificate types
- `certificate_get_type_config($type)` - Get type configuration
- `certificate_get_template_path($type)` - Get template path
- `certificate_get_type_badge_class($type)` - Get badge CSS class
- `certificate_get_type_icon($type)` - Get icon class
- `certificate_get_required_fields($type)` - Get required fields
- `certificate_get_optional_fields($type)` - Get optional fields
- `certificate_get_field_labels()` - Get field labels
- `certificate_get_field_descriptions()` - Get field descriptions
- `certificate_validate_data($data, $type)` - Validate certificate data
- `certificate_format_data_for_display($certificate)` - Format for display
- `certificate_get_status($certificate)` - Get certificate status
- `certificate_get_status_badge_class($status)` - Get status badge class
- `certificate_get_company_info()` - Get company information
- `certificate_generate_reference_number()` - Generate unique reference number
- `certificate_format_reference_number($referenceNo)` - Format reference number for display

## Support

For technical support or feature requests, please contact the development team.

---

**Last Updated:** July 30, 2025
**Version:** 2.0.0 - Enhanced with configurable types and proper helper functions
