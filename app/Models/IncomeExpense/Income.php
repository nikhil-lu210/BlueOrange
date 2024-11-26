<?php

namespace App\Models\IncomeExpense;

use App\Models\IncomeExpense\Accessors\IncomeAccessors;
use App\Models\IncomeExpense\Mutators\IncomeMutators;
use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\IncomeExpense\Relations\IncomeRelations;

class Income extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasCustomRouteId;

    // Relations 
    use IncomeRelations;

    // Accessors & Mutators
    use IncomeAccessors, IncomeMutators;
    
    protected $cascadeDeletes = [];

    protected $casts = [
        'description' => PurifyHtmlOnGet::class,
        'date' => 'date'
    ];

    protected $fillable = [
        'creator_id',
        'category_id',
        'source',
        'date',
        'total',
        'description',
    ];
}
