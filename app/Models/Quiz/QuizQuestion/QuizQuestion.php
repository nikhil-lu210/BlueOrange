<?php

namespace App\Models\Quiz\QuizQuestion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Quiz\QuizQuestion\Mutators\QuizQuestionMutators;
use App\Models\Quiz\QuizQuestion\Accessors\QuizQuestionAccessors;
use App\Models\Quiz\QuizQuestion\Relations\QuizQuestionRelations;

class QuizQuestion extends Model
{
    use HasFactory, SoftDeletes;

    // Relations
    use QuizQuestionRelations;

    // Accessors & Mutators
    use QuizQuestionAccessors, QuizQuestionMutators;

    protected $casts = [];

    protected $fillable = [];
}
