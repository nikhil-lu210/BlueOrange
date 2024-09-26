<?php

namespace Database\Factories\DailyBreak;

use App\Models\User;
use Illuminate\Support\Carbon;
use App\Models\Attendance\Attendance;
use App\Models\DailyBreak\DailyBreak;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DailyBreak\DailyBreak>
 */
class DailyBreakFactory extends Factory
{
    protected $model = DailyBreak::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // Get a random user who has regular attendance
        $user = User::whereHas('attendances', function ($query) {
            $query->where('type', 'Regular')->whereNull('clock_out');
        })->inRandomOrder()->first();

        // If no user found, return an empty array
        if (!$user) {
            return [];
        }

        // Get the latest attendance record for the user
        $attendance = $user->attendances()->whereType('Regular')->latest()->first();

        // Ensure attendance exists
        if (!$attendance) {
            return [];
        }

        // Use the attendance clock-in date for break date
        $breakInAt = Carbon::parse($attendance->clock_in)->addHours(rand(1, 4)); // Ensuring break_in is within working hours
        $breakOutAt = (clone $breakInAt)->addMinutes(rand(15, 60)); // Ensure break out is after break in

        return [
            'user_id' => $user->id,
            'attendance_id' => $attendance->id,
            'date' => $attendance->clock_in_date, // Match the date with attendance's clock_in_date
            'break_in_at' => $breakInAt, // Random break-in time
            'break_out_at' => $breakOutAt, // Random break-out time
            'total_time' => $this->calculateTotalTime($breakInAt, $breakOutAt), // Calculate total time
            'over_break' => rand(0, 1) ? null : $this->faker->time(), // Random over break or null
            'type' => $this->faker->randomElement(['Short', 'Long']), // Random type
            'break_in_ip' => $this->faker->ipv4, // Generate a random IP address
            'break_out_ip' => $this->faker->ipv4, // Generate a random IP address or null
            'note' => $this->faker->sentence(), // Generate a random note
        ];
    }

    private function calculateTotalTime($breakInAt, $breakOutAt)
    {
        // Calculate total time in HH:MM:SS format
        if ($breakOutAt) {
            $totalSeconds = $breakInAt->diffInSeconds($breakOutAt);
            $hours = floor($totalSeconds / 3600);
            $minutes = floor(($totalSeconds % 3600) / 60);
            $seconds = $totalSeconds % 60;
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }
        return null; // Return null if no break out time
    }
}
