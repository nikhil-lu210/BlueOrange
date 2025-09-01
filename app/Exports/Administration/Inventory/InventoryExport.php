<?php

namespace App\Exports\Administration\Inventory;

use App\Models\Inventory\Inventory;
use App\Exports\Global\BaseExportSettings;
use Maatwebsite\Excel\Concerns\FromCollection;

class InventoryExport extends BaseExportSettings implements FromCollection
{
    protected $inventories;

    public function __construct($inventories)
    {
        $this->inventories = $inventories->load([
            'category',
            'creator'
        ]);
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->inventories->map(function ($inventory) {
            return [
                'office_inventory_code' => $inventory->office_inventory_code,
                'name' => $inventory->name,
                'unique_number' => $inventory->unique_number,
                'category' => $inventory->category->name,
                'price' => $inventory->price,
                'description' => $inventory->description,
                'usage_for' => $inventory->usage_for,
                'status' => $inventory->status,
                'creator' => $inventory->creator->name,
                'created_at' => get_date_only($inventory->created_at),
            ];
        });
    }

    /**
     * Define the headings for the export.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Office Inventory Code (OIC)',
            'Name',
            'Unique Number',
            'Category',
            'Price',
            'Description',
            'Usage For',
            'Status',
            'Created By',
            'Created Date',
        ];
    }
}
