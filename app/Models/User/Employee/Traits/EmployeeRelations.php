<?php

namespace App\Models\User\Employee\Traits;

use App\Models\Education\EducationLevel\EducationLevel;
use App\Models\Education\Institute\Institute;
use App\Models\User;
use App\Models\Religion\Religion;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait EmployeeRelations
{
    /**
     * Get the user that owns the employee.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the religion that owns the employee.
     */
    public function religion(): BelongsTo
    {
        return $this->belongsTo(Religion::class);
    }

    /**
     * Get the institute that owns the employee.
     */
    public function institute(): BelongsTo
    {
        return $this->belongsTo(Institute::class);
    }

    /**
     * Get the education_level that owns the employee.
     */
    public function education_level(): BelongsTo
    {
        return $this->belongsTo(EducationLevel::class);
    }
}
