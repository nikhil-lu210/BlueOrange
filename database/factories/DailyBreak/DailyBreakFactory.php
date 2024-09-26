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
        // Get an attendance that is of type 'Regular' and has a null clock_out
        $attendance = Attendance::where('type', 'Regular')
            ->inRandomOrder()
            ->first();

        if (!$attendance) {
            // Log a warning or handle the case where no valid attendance is found
            \Log::warning('No valid attendance found for DailyBreakFactory.');
            return [];
        }

        // Generate random times for break_in and break_out
        $breakInAt = Carbon::now()->subHours(rand(1, 4));
        $breakOutAt = (clone $breakInAt)->addMinutes(rand(15, 60)); // Ensure break out is after break in

        return [
            'user_id' => $attendance->user_id, // Get the user ID from the attendance
            'attendance_id' => $attendance->id, // Use the selected attendance ID
            'date' => $breakInAt->toDateString(), // Use the date of break_in_at
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
