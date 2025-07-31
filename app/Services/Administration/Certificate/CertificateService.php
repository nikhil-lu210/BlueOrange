<?php

namespace App\Services\Administration\Certificate;

use App\Models\User;
use App\Models\Certificate\Certificate;

class CertificateService
{
    /**
     * Get all certificates with pagination
     */
    public function getAllCertificates($perPage = 15)
    {
        return Certificate::with(['user.employee', 'creator'])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get user's certificates with pagination
     */
    public function getUserCertificates($userId, $perPage = 15)
    {
        return Certificate::with(['user.employee', 'creator'])
            ->where('user_id', $userId)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get employees for dropdown
     */
    public function getEmployeesForDropdown()
    {
        return User::select(['id', 'name'])
            ->with('employee:id,user_id,alias_name')
            ->whereHas('employee')
            ->orderBy('name')
            ->get();
    }

    /**
     * Generate certificate preview
     */
    public function generateCertificatePreview(array $data)
    {
        // Get the user with employee data
        $user = User::with(['employee', 'roles'])->findOrFail($data['user_id']);

        // Create a certificate object for preview (not saved to database)
        $certificate = new Certificate($data);
        $certificate->user = $user;

        return $certificate;
    }

    /**
     * Create and store certificate
     */
    public function createCertificate(array $data)
    {
        // Create the certificate
        return Certificate::create($data);
    }

    /**
     * Get certificate with relationships
     */
    public function getCertificateWithRelations(Certificate $certificate)
    {
        return $certificate->load(['user.employee', 'creator']);
    }

    /**
     * Delete certificate
     */
    public function deleteCertificate(Certificate $certificate)
    {
        return $certificate->delete();
    }



    /**
     * Get certificate types for dropdown
     */
    public function getCertificateTypes()
    {
        return Certificate::getTypes();
    }

    /**
     * Get certificate type configuration
     */
    public function getCertificateTypeConfig($type = null)
    {
        return Certificate::getTypeConfig($type);
    }
}
