<?php

namespace App\Models\Quiz\QuizTest\Relations;

use App\Models\User;
use Illuminate\Support\Collection;
use App\Models\Quiz\QuizAnswer\QuizAnswer;
use App\Models\Quiz\QuizQuestion\QuizQuestion;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait QuizTestRelations
{
    /**
     * Get the user who created the test.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Get all questions submitted for this test.
     */
    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(QuizQuestion::class)
            ->withPivot(['selected_option', 'is_correct', 'answered_at'])
            ->withTimestamps();
    }
}
