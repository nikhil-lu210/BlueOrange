<?php

namespace App\Models\Religion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Religion\Mutators\ReligionMutators;
use App\Models\Religion\Accessors\ReligionAccessors;
use App\Models\Religion\Relations\ReligionRelations;
use App\Traits\HasCustomRouteId;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Religion extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasCustomRouteId;

    // Relations
    use ReligionRelations;

    // Accessors & Mutators
    use ReligionAccessors, ReligionMutators;

    protected $casts = [];

    protected $fillable = ['name', 'slug'];
}
