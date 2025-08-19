<?php

namespace App\Models\Recognition\Relations;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait RecognitionRelations
{
    /**
     * Get the user who created the recognition.
     */
    public function recognizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recognizer_id');
    }

    /**
     * Get the user who received the recognition.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
