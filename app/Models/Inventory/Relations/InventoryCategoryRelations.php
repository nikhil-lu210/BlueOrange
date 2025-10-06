<?php

namespace App\Models\Inventory\Relations;

use App\Models\User;
use App\Models\Inventory\Inventory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait InventoryCategoryRelations
{
    /**
     * Get all the inventories for the InventoryCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    /**
     * Get the user who created the inventory.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
