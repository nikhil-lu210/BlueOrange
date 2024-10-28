<?php

namespace App\Services\Administration\Leave;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Leave\LeaveHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class LeaveHistoryService
{
    /**
     * Build the query for retrieving daily breaks.
     *
     * @param Request $request
     * @param int|null $userId
     * @return Builder
     */
    public function getLeavesQuery($request, int $userId = null): Builder
    {
        $query = LeaveHistory::with([
            'user:id,userid,name', 
            'user.media', 
            'user.roles',
        ])
        ->orderByDesc('date')
        ->orderBy('created_at');

        // Apply user ID filter if provided
        if ($userId) {
            $query->whereUserId($userId);
        }

        // Apply user ID filter if request user_id provided
        if ($request->user_id) {
            $query->whereUserId($request->user_id);
        }

        // Handle month/year filtering
        if ($request->has('leave_month_year') && !is_null($request->leave_month_year)) {
            $monthYear = Carbon::createFromFormat('F Y', $request->leave_month_year);
            $query->whereYear('date', $monthYear->year)
                ->whereMonth('date', $monthYear->month);
        } else {
            // Default to current month if no specific filter is applied
            if (!$request->has('filter_leaves')) {
                $query->whereBetween('date', [
                    Carbon::now()->startOfMonth()->format('Y-m-d'),
                    Carbon::now()->endOfMonth()->format('Y-m-d')
                ]);
            }
        }

        // Apply type filter if specified
        if ($request->has('type') && !is_null($request->type)) {
            $query->where('type', $request->type);
        }

        return $query;
    }

    
    /**
     * Store leave history records for the user.
     *
     * @param User $user
     * @param array $data
     * @return void
     */
    public function store(User $user, array $data): void
    {
        // dd(isset($data['files']), !empty($data['files']));
        DB::transaction(function () use ($user, $data) {
            foreach ($data['leave_days']['date'] as $index => $date) {
                // Format total_leave to hh:mm:ss
                $totalLeave = sprintf(
                    '%02d:%02d:%02d',
                    $data['total_leave']['hour'][$index] ?? 0,
                    $data['total_leave']['min'][$index] ?? 0,
                    $data['total_leave']['sec'][$index] ?? 0
                );

                // Create leave history entry for each date
                $leaveHistory = $user->leave_histories()->create([
                    'leave_allowed_id' => $user->allowed_leave->id,
                    'date' => $date,
                    'total_leave' => $totalLeave,
                    'type' => $data['type'],
                    'reason' => $data['reason']
                ]);

                // Check and store associated files if provided in the 'files' key
                if (isset($data['files']) && !empty($data['files'])) {
                    foreach ($data['files'] as $file) {
                        $directory = 'leaves/' . $user->userid;
                        store_file_media($file, $leaveHistory, $directory);
                    }
                }
            }
        }, 5);
    }
}
