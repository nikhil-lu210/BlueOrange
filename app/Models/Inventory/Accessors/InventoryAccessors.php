<?php

namespace App\Models\Inventory\Accessors;

trait InventoryAccessors
{
    /**
     * Get the office inventory code with 'oic' short code.
     *
     * Usage: $inventory->oic
     *
     * @return string|null
     */
    public function getOicAttribute(): ?string
    {
        if (!empty($this->office_inventory_code)) {
            return $this->office_inventory_code;
        }

        return null;
    }

    /**
     * Get the status badge HTML class for Bootstrap.
     *
     * Usage: $inventory->status_badge
     *
     * @return string
     */
    public function getStatusBadgeAttribute(): string
    {
        switch ($this->status) {
            case 'Available':
                $class = 'success';
                break;
            case 'In Use':
                $class = 'primary';
                break;
            case 'Out of Service':
                $class = 'warning';
                break;
            case 'Damaged':
                $class = 'danger';
                break;
            default:
                $class = 'secondary';
        }

        return "badge bg-{$class}";
    }
}
