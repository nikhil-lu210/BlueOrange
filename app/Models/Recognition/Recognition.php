<?php

namespace App\Models\Recognition;

use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Recognition\Mutators\RecognitionMutators;
use App\Models\Recognition\Accessors\RecognitionAccessors;
use App\Models\Recognition\Relations\RecognitionRelations;

class Recognition extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasCustomRouteId;

    // Relations
    use RecognitionRelations;

    // Accessors & Mutators
    use RecognitionAccessors, RecognitionMutators;

    protected $cascadeDeletes = [];

    protected $casts = [
        'comment' => PurifyHtmlOnGet::class,
        'total_mark' => 'integer',
    ];

    protected $fillable = [
        'user_id',
        'category',
        'total_mark',
        'comment',
        'recognizer_id',
    ];
}
