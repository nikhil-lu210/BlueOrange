<?php

namespace App\Models;

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

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function recognizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recognizer_id');
    }
}
