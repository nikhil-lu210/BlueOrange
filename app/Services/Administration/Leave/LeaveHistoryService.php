<?php

namespace App\Services\Administration\Leave;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class LeaveHistoryService
{
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
