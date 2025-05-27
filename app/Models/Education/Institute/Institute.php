<?php

namespace App\Models\Education\Institute;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Education\Institute\Mutators\InstituteMutators;
use App\Models\Education\Institute\Accessors\InstituteAccessors;
use App\Models\Education\Institute\Relations\InstituteRelations;

class Institute extends Model
{
    use HasFactory, SoftDeletes;

    // Relations
    use InstituteRelations;

    // Accessors & Mutators
    use InstituteAccessors, InstituteMutators;

    protected $casts = ['description' => PurifyHtmlOnGet::class];

    protected $fillable = ['name', 'slug', 'description'];
}
