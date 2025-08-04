<?php

namespace App\Models\Hiring;

use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use App\Models\Hiring\Relations\HiringStageRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HiringStage extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasCustomRouteId;

    // Relations
    use HiringStageRelations;

    protected $cascadeDeletes = ['evaluations'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $fillable = [
        'name',
        'description',
        'stage_order',
        'is_active',
    ];

    /**
     * Scope to get active stages
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by stage order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('stage_order');
    }
}
