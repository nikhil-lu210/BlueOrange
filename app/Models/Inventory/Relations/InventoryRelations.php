<?php

namespace App\Models\Inventory\Relations;

use App\Models\User;
use App\Models\FileMedia\FileMedia;
use App\Models\Inventory\InventoryCategory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait InventoryRelations
{
    /**
     * Get the category that owns the Inventory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(InventoryCategory::class);
    }

    /**
     * Get the user who created the inventory.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Get the files associated with the inventory.
     */
    public function files(): MorphMany
    {
        return $this->morphMany(FileMedia::class, 'fileable');
    }
}
