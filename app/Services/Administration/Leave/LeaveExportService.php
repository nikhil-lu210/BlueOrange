<?php

namespace App\Services\Administration\Leave;


use Carbon\Carbon;
use App\Models\User;
use App\Models\Leave\LeaveHistory;

class LeaveExportService
{
    public function export($request)
    {
        // Building the query based on filters
        $query = LeaveHistory::with(['user:id,name'])
            ->whereHas('user')
            ->orderBy(User::select('name')->whereColumn('users.id', 'leave_histories.user_id')); // order by asc

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

        // Handle leave_month_year filter
        if ($request->has('leave_month_year') && !is_null($request->leave_month_year)) {
            $monthYearDate = Carbon::createFromFormat('F Y', $request->leave_month_year);
            $query->whereYear('date', $monthYearDate->year)
                ->whereMonth('date', $monthYearDate->month);
            $monthYear = '_of_' . $monthYearDate->format('m_Y');
        } else {
            if (!$request->has('filter_leaves')) {
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

        // Get the filtered leaves
        $leaves = $query->get();

        if ($leaves->count() < 1) {
            return null; // Indicate no leaves found
        }

        return [
            'leaves' => $leaves,
            'fileName' => $this->generateFileName($userName, $monthYear, $breakInType)
        ];
    }

    private function generateFileName($userName, $monthYear, $breakInType)
    {
        $downloadMonth = $monthYear ? $monthYear : '_' . date('m_Y');
        return $breakInType . 'leaves_backup_of_' . $userName . $downloadMonth . '.xlsx';
    }
}
