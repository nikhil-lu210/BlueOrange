<?php

namespace App\Models\Quiz\QuizQuestion;

use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Quiz\QuizQuestion\Mutators\QuizQuestionMutators;
use App\Models\Quiz\QuizQuestion\Accessors\QuizQuestionAccessors;
use App\Models\Quiz\QuizQuestion\Relations\QuizQuestionRelations;

class QuizQuestion extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasCustomRouteId;

    // Relations
    use QuizQuestionRelations;

    // Accessors & Mutators
    use QuizQuestionAccessors, QuizQuestionMutators;

    protected $cascadeDeletes = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $fillable = [
        'creator_id',
        'question',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_option',
        'is_active',
    ];
}
