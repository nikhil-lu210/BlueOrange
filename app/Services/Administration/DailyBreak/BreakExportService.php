<?php

namespace App\Services\Administration\DailyBreak;


use Carbon\Carbon;
use App\Models\User;
use App\Models\DailyBreak\DailyBreak;

class BreakExportService
{
    public function exportBreaks($request)
    {
        // Building the query based on filters
        $query = DailyBreak::with(['user:id,name'])
            ->whereHas('user')
            ->orderBy(User::select('name')->whereColumn('users.id', 'daily_breaks.user_id')); // order by asc

        // Initialize variables for filename parts
        $userName = '';
        $monthYear = '';
        $breakInType = '';

        // Handle user_id filter
        if ($request->has('user_id') && !is_null($request->user_id)) {
            $query->where('user_id', $request->user_id);
            $user = User::find($request->user_id);
            $userName = $user ? '_of_' . strtolower(str_replace(' ', '_', $user->name)) : '';
        }

        // Handle created_month_year filter
        if ($request->has('created_month_year') && !is_null($request->created_month_year)) {
            $monthYearDate = Carbon::parse($request->created_month_year);
            $query->whereYear('date', $monthYearDate->year)
                ->whereMonth('date', $monthYearDate->month);
            $monthYear = '_of_' . $monthYearDate->format('m_Y');
        } else {
            if (!$request->has('filter_breaks')) {
                $query->whereBetween('date', [
                    Carbon::now()->startOfMonth()->format('Y-m-d'),
                    Carbon::now()->endOfMonth()->format('Y-m-d')
                ]);
            }
        }

        // Handle type filter
        if ($request->has('type') && !is_null($request->type)) {
            $query->where('type', $request->type);
            $breakInType = strtolower($request->type) . '_';
        }

        // Get the filtered breaks
        $breaks = $query->get();

        if ($breaks->count() < 1) {
            return null; // Indicate no breaks found
        }

        return [
            'breaks' => $breaks,
            'fileName' => $this->generateFileName($userName, $monthYear, $breakInType)
        ];
    }

    private function generateFileName($userName, $monthYear, $breakInType)
    {
        $downloadMonth = $monthYear ? $monthYear : '_' . date('m_Y');
        return $breakInType . 'daily_breaks_backup_of_' . $userName . $downloadMonth . '.xlsx';
    }
}
