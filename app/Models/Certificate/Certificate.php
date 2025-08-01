<?php

namespace App\Models\Certificate;

use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Certificate\Relations\CertificateRelations;
use App\Models\Certificate\Accessors\CertificateAccessors;
use App\Models\Certificate\Mutators\CertificateMutators;

class Certificate extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasCustomRouteId;

    // Relations
    use CertificateRelations;

    // Accessors & Mutators
    use CertificateAccessors, CertificateMutators;

    protected $cascadeDeletes = [];

    protected $casts = [
        'issue_date' => 'date',
        'resignation_date' => 'date',
        'resign_application_date' => 'date',
        'resignation_approval_date' => 'date',
        'release_date' => 'date',
        'leave_starts_from' => 'date',
        'leave_ends_on' => 'date',
        'salary' => 'decimal:2',
    ];

    protected $fillable = [
        'reference_no',
        'user_id',
        'creator_id',
        'type',
        'issue_date',
        'salary',
        'resignation_date',
        'resign_application_date',
        'resignation_approval_date',
        'release_date',
        'release_reason',
        'country_name',
        'visiting_purpose',
        'leave_starts_from',
        'leave_ends_on',
        'email_sent',
    ];

    /**
     * Boot method to set creator_id and reference_no automatically
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($certificate) {
            // Set creator_id automatically
            if (auth()->check() && is_null($certificate->creator_id)) {
                $certificate->creator_id = auth()->id();
            }

            // Generate unique reference number if not set
            if (empty($certificate->reference_no)) {
                $certificate->reference_no = self::generateUniqueReferenceNumber();
            }
        });
    }

    /**
     * Get the certificate types
     */
    public static function getTypes()
    {
        return array_keys(config('certificate.types', []));
    }

    /**
     * Get certificate type configuration
     */
    public static function getTypeConfig($type = null)
    {
        $types = config('certificate.types', []);

        if ($type) {
            return $types[$type] ?? null;
        }

        return $types;
    }

    /**
     * Get the template name for the certificate type
     */
    public function getTemplateName()
    {
        $typeConfig = self::getTypeConfig($this->type);
        return $typeConfig['template'] ?? 'employment_certificate';
    }

    /**
     * Get required fields for this certificate type
     */
    public function getRequiredFields()
    {
        $typeConfig = self::getTypeConfig($this->type);
        return $typeConfig['required_fields'] ?? [];
    }

    /**
     * Get optional fields for this certificate type
     */
    public function getOptionalFields()
    {
        $typeConfig = self::getTypeConfig($this->type);
        return $typeConfig['optional_fields'] ?? [];
    }

    /**
     * Generate a unique 10-character reference number (alphanumeric)
     */
    public static function generateUniqueReferenceNumber()
    {
        do {
            // Generate a 10-character alphanumeric string
            $referenceNo = strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10));
        } while (self::where('reference_no', $referenceNo)->exists());

        return $referenceNo;
    }

    /**
     * Get formatted reference number for display
     */
    public function getFormattedReferenceNoAttribute()
    {
        return $this->reference_no ? 'CERT-' . $this->reference_no : null;
    }
}
