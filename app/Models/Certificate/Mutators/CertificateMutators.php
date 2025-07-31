<?php

namespace App\Models\Certificate\Mutators;

trait CertificateMutators
{
    /**
     * Set the salary attribute
     */
    public function setSalaryAttribute($value)
    {
        $this->attributes['salary'] = $value ? (float) $value : null;
    }

    /**
     * Set the release reason attribute
     */
    public function setReleaseReasonAttribute($value)
    {
        $this->attributes['release_reason'] = $value ? trim($value) : null;
    }

    /**
     * Set the country name attribute
     */
    public function setCountryNameAttribute($value)
    {
        $this->attributes['country_name'] = $value ? trim($value) : null;
    }

    /**
     * Set the visiting purpose attribute
     */
    public function setVisitingPurposeAttribute($value)
    {
        $this->attributes['visiting_purpose'] = $value ? trim($value) : null;
    }
}
