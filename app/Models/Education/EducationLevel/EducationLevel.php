<?php

namespace App\Models\Education\EducationLevel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Education\EducationLevel\Mutators\EducationLevelMutators;
use App\Models\Education\EducationLevel\Accessors\EducationLevelAccessors;
use App\Models\Education\EducationLevel\Relations\EducationLevelRelations;

class EducationLevel extends Model
{
    use HasFactory, SoftDeletes;

    // Relations
    use EducationLevelRelations;

    // Accessors & Mutators
    use EducationLevelAccessors, EducationLevelMutators;

    protected $casts = ['description' => PurifyHtmlOnGet::class];

    protected $fillable = ['title', 'slug', 'description'];
}
