<?php

namespace App\Models\Holiday;

use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use App\Models\Holiday\Traits\Relations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Holiday extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, Relations, HasCustomRouteId;
    
    protected $cascadeDeletes = [];

    protected $casts = [
        'description' => PurifyHtmlOnGet::class,
    ];

    protected $fillable = [
        'date',
        'name',
        'description',
        'is_active'
    ];
}
