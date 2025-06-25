<?php

namespace App\Models\Quiz\QuizQuestion\Relations;

use App\Models\User;
use App\Models\Quiz\QuizTest\QuizTest;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait QuizQuestionRelations
{
    /**
     * Get the user who created the question.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Get all tests submitted for this question.
     */
    public function tests(): BelongsToMany
    {
        return $this->belongsToMany(QuizTest::class)
            ->withPivot(['selected_option', 'is_correct', 'answered_at'])
            ->withTimestamps();
    }
}
