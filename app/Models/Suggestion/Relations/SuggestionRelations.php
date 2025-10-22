<?php

namespace App\Models\Suggestion\Relations;

use App\Models\User;

trait SuggestionRelations
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}