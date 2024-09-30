<?php

namespace Database\Factories\Attendance;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance\Attendance;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition()
    {
        // Get a user with an active shift
        $user = User::whereHas('employee_shifts', function ($query) {
            $query->where('status', 'active');
        })->inRandomOrder()->first();

        // Get the current shift for the user
        $employeeShift = $user ? $user->currentShift : null;

        // Generate a random date within the last 2 years and this year
        $clockInDate = $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d');

        // Generate a random clock_in time between 00:00 and 16:00
        $clockInTime = Carbon::createFromFormat('Y-m-d', $clockInDate)
            ->addHours(rand(0, 16))
            ->format('H:i:s');

        // Calculate clock_out time, ensuring it is not more than 10 hours after clock_in
        $clockIn = Carbon::createFromFormat('Y-m-d H:i:s', $clockInDate . ' ' . $clockInTime);
        $maxClockOut = $clockIn->copy()->addHours(10);
        $clockOut = $this->faker->dateTimeBetween($clockIn, $maxClockOut)->format('Y-m-d H:i:s');

        // Calculate total time
        $totalTime = $clockOut ? $clockIn->diff($clockOut)->format('%H:%I:%S') : null;

        return [
            'user_id' => $user ? $user->id : User::factory(),
            'employee_shift_id' => $employeeShift ? $employeeShift->id : null,
            'clock_in_date' => $clockInDate,
            'clock_in' => $clockIn->format('Y-m-d H:i:s'),
            'clock_out' => $clockOut,
            'total_time' => $totalTime,
            'type' => $this->faker->randomElement(['Regular', 'Overtime']),
            'ip_address' => $this->faker->ipv4(),
            'country' => $this->faker->country(),
            'city' => $this->faker->city(),
            'zip_code' => $this->faker->postcode(),
            'time_zone' => $this->faker->timezone(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
        ];
    }
}