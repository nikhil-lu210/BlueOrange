<?php

namespace App\Models\Weekend;

use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use App\Models\Weekend\Traits\Relations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Weekend extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, Relations, HasCustomRouteId;
    
    protected $cascadeDeletes = [];

    protected $fillable = [
        'day',
        'is_active'
    ];
}
