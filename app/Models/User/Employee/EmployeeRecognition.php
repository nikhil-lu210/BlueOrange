<?php

namespace App\Models\User\Employee;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeRecognition extends Model
{
    protected $fillable = [
        'employee_id',
        'recognizer_id',
        'category',
        'points',
        'comment',
    ];

    /**
     * Get the employee for the recognition.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    /**
     * Get the recognizer for the recognition.
     */
    public function recognizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recognizer_id');
    }
}
