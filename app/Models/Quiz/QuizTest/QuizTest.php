<?php

namespace App\Models\Quiz\QuizTest;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Quiz\QuizTest\Mutators\QuizTestMutators;
use App\Models\Quiz\QuizTest\Accessors\QuizTestAccessors;
use App\Models\Quiz\QuizTest\Relations\QuizTestRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuizTest extends Model
{
    use HasFactory, SoftDeletes;

    // Relations
    use QuizTestRelations;

    // Accessors & Mutators
    use QuizTestAccessors, QuizTestMutators;

    protected $casts = [];

    protected $fillable = [];
}
