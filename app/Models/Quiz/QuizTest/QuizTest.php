<?php

namespace App\Models\QuizTest;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\QuizTest\Mutators\QuizTestMutators;
use App\Models\QuizTest\Accessors\QuizTestAccessors;
use App\Models\QuizTest\Relations\QuizTestRelations;
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