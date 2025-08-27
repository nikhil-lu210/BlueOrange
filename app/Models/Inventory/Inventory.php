<?php

namespace App\Models\Inventory;

use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use App\Models\Inventory\Mutators\InventoryMutators;
use App\Models\Inventory\Accessors\InventoryAccessors;
use App\Models\Inventory\Relations\InventoryRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\Administration\Inventory\InventoryObserver;

#[ObservedBy([InventoryObserver::class])]
class Inventory extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasCustomRouteId;

    // Relations
    use InventoryRelations;

    // Accessors & Mutators
    use InventoryAccessors, InventoryMutators;

    protected $cascadeDeletes = [];

    protected $casts = [
        'category_id' => 'integer',
        'creator_id' => 'integer',
        'name' => 'string',
        'unique_number' => 'string',
        'price' => 'float',
        'description' => 'string',
        'usage_for' => 'string',
        'status' => 'string',
    ];

    protected $fillable = [
        'category_id',
        'creator_id',
        'name',
        'unique_number',
        'price',
        'description',
        'usage_for',
        'status',
    ];
}
