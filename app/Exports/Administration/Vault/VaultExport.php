<?php

namespace App\Exports\Administration\Vault;

use App\Exports\Global\BaseExportSettings;
use Maatwebsite\Excel\Concerns\FromCollection;

class VaultExport extends BaseExportSettings implements FromCollection
{
    protected $vaults;

    public function __construct($vaults)
    {
        $this->vaults = $vaults;
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->vaults->map(function ($vault) {
            return [
                'name' => $vault->name,
                'url' => $vault->url ?? 'N/A',
                'username' => strval($vault->username),
                'password' => strval($vault->password),
                'created_at' => get_date_only($vault->created_at),
                'creator' => $vault->creator->name,
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
            'Name',
            'URL',
            'Username',
            'Password',
            'Created At',
            'Created By',
        ];
    }
}
