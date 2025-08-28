<?php

namespace App\Models\Inventory;

use Illuminate\Support\Str;
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
        'office_inventory_code' => 'string',
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
        'office_inventory_code',
        'category_id',
        'creator_id',
        'name',
        'unique_number',
        'price',
        'description',
        'usage_for',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($inventory) {
            // Generate a unique code only if not already set
            if (empty($inventory->office_inventory_code)) {
                $inventory->office_inventory_code = self::generateUniqueCode();
            }
        });
    }

    /**
     * Generate a unique office inventory code.
     *
     * @return string
     */
    private static function generateUniqueCode(): string
    {
        do {
            // Example: SI-INV-1693234567123-ABC123
            $code = 'SINV-' . (int) (microtime(true) * 1000) . '-' . strtoupper(Str::random(4));
        } while (self::where('office_inventory_code', $code)->exists());

        return $code;
    }
}
