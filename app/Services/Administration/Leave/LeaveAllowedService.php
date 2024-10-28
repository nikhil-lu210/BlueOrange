<?php
namespace App\Services\Administration\Leave;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class LeaveAllowedService
{
    /**
     * Store allowed leave for a user.
     *
     * @param User $user
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function store(User $user, array $data): void
    {
        // Format time fields to 'hh:mm:ss'
        $earned_leave = sprintf('%02d:%02d:%02d', $data['earned_leave_hour'], $data['earned_leave_min'], $data['earned_leave_sec']);
        $sick_leave = sprintf('%02d:%02d:%02d', $data['sick_leave_hour'], $data['sick_leave_min'], $data['sick_leave_sec']);
        $casual_leave = sprintf('%02d:%02d:%02d', $data['casual_leave_hour'], $data['casual_leave_min'], $data['casual_leave_sec']);

        // Prepare the date strings in 'mm-dd' format
        $implemented_from_date = sprintf('%02d-%02d', $data['implemented_from_month'], $data['implemented_from_date']);
        $implemented_to_date = sprintf('%02d-%02d', $data['implemented_to_month'], $data['implemented_to_date']);

        // Perform the database transaction
        DB::transaction(function () use ($user, $earned_leave, $sick_leave, $casual_leave, $implemented_from_date, $implemented_to_date) {
            // Update `is_active` to false for existing leave_alloweds for this user
            if ($user->leave_alloweds()->exists()) {
                $user->leave_alloweds()->update(['is_active' => false]);
            }

            // Create a new leave_allowed entry
            $user->leave_alloweds()->create([
                'earned_leave' => $earned_leave,
                'sick_leave' => $sick_leave,
                'casual_leave' => $casual_leave,
                'implemented_from' => $implemented_from_date,
                'implemented_to' => $implemented_to_date,
                'is_active' => true,
            ]);
        }, 5);
    }
}