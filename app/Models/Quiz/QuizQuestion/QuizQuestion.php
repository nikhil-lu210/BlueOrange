<?php

namespace App\Models\QuizQuestion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\QuizQuestion\Mutators\QuizQuestionMutators;
use App\Models\QuizQuestion\Accessors\QuizQuestionAccessors;
use App\Models\QuizQuestion\Relations\QuizQuestionRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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