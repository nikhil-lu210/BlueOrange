<?php

namespace App\Models\Inventory;

use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Models\Inventory\Mutators\InventoryCategoryMutators;
use App\Models\Inventory\Accessors\InventoryCategoryAccessors;
use App\Models\Inventory\Relations\InventoryCategoryRelations;
use App\Observers\Administration\Inventory\InventoryCategoryObserver;

#[ObservedBy([InventoryCategoryObserver::class])]
class InventoryCategory extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasCustomRouteId;

    // Relations
    use InventoryCategoryRelations;

    // Accessors & Mutators
    use InventoryCategoryAccessors, InventoryCategoryMutators;

    protected $cascadeDeletes = ['inventories'];

    protected $casts = [
        'name' => 'string',
        'description' => 'string',
        'creator_id' => 'integer',
    ];

    protected $fillable = [
        'name',
        'description',
        'creator_id',
    ];
}
