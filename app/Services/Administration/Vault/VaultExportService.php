<?php

namespace App\Services\Administration\Vault;

use Carbon\Carbon;
use App\Models\IncomeExpense\Income;
use App\Models\IncomeExpense\IncomeExpenseCategory;

class VaultExportService
{
    /**
     * Export incomes based on $vaults.
     *
     * @param $vaults
     * @return array|null
     */
    public function export($vaults)
    {
        if ($vaults->isEmpty()) {
            return null; // No vaults found
        }

        return [
            'vaults' => $vaults,
            'fileName' => $this->generateFileName(auth()->user()),
        ];
    }

    /**
     * Generate the file name for the vault credentials export.
     * The filename includes the current date and time in the format 'vault_credentials_backup_dd_mm_yyyy_hh_mm_ss.xlsx'.
     *
     * @param \Carbon\Carbon|null $dateTime The date and time to be used for the filename. If null, the current date and time will be used.
     * @return string The generated filename with the date and time.
     */
    private function generateFileName($user, $dateTime = null) 
    {
        // If no $dateTime is provided, use the current date and time
        $dateTime = $dateTime ?: now();

        // Format the date and time as 'd_m_Y_H_i_s'
        $formattedDateTime = $dateTime->format('d_m_Y_H_i_s');
        
        return 'vault_credentials_backup_at_' . $formattedDateTime. '_by_('. $user->name . ').xlsx';
    }
}
