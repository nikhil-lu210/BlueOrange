<?php

namespace App\Observers\Administration\Inventory;

use App\Models\Inventory\InventoryCategory;


class InventoryCategoryObserver
{
    /**
     * Handle the InventoryCategory "created" event.
     */
    public function created(InventoryCategory $inventoryCategory): void
    {
        //
    }

    /**
     * Handle the InventoryCategory "updated" event.
     */
    public function updated(InventoryCategory $inventoryCategory): void
    {
        //
    }

    /**
     * Handle the InventoryCategory "deleted" event.
     */
    public function deleted(InventoryCategory $inventoryCategory): void
    {
        //
    }

    /**
     * Handle the InventoryCategory "restored" event.
     */
    public function restored(InventoryCategory $inventoryCategory): void
    {
        //
    }

    /**
     * Handle the InventoryCategory "force deleted" event.
     */
    public function forceDeleted(InventoryCategory $inventoryCategory): void
    {
        //
    }
}
