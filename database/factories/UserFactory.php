<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{

    /**
     * To create a bunch of users by tinker is: 
     * App\Models\User::factory()->count(100)->create();
     * 
     * This will create 100 users at a time
     */

    protected $model = User::class;

    public function definition()
    {
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;
        return [
            'userid' => strtoupper(Str::random(8)),
            'first_name' => $firstName,
            'last_name' => $lastName,
            'name' => $firstName . ' ' . $lastName,
            'email' => strtolower(Str::random(8)).$this->faker->unique(true)->safeEmail,
            'password' => Hash::make('12345678'),
            'status' => Arr::random(['Active', 'Inactive', 'Fired', 'Resigned']),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }
    

    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            DB::transaction(function () use ($user) {
                // Assign a random role
                $role = Role::inRandomOrder()->first();
                $user->assignRole($role);
                
                // Assign a random team_leader
                $teamLeader = User::inRandomOrder()->first();
                $user->employee_team_leaders()->attach($teamLeader->id, ['is_active' => true]);
                
                // Assign a random interacted user
                $interactedUser = User::inRandomOrder()->first();
                $user->interacted_users()->attach($interactedUser->id);

                // Generate a random start time between 01:00 and 23:00
                $start_time = Carbon::createFromTime(rand(1, 23), 0, 0);

                // Calculate end time (8 hours later)
                $end_time = $start_time->copy()->addHours(8);

                // Format end time to handle cases where it exceeds 24:00
                $formatted_end_time = $end_time->format('H:i:s');

                // Create associated EmployeeShift
                $user->employee_shifts()->create([
                    'start_time' => $start_time->format('H:i:s'),
                    'end_time' => $formatted_end_time,
                    'implemented_from' => now()->format('Y-m-d'),
                ]);

                // Create associated employee for the user
                $user->employee()->create([
                    'joining_date' => $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
                    'alias_name' => $this->faker->firstNameMale(),
                    'father_name' => $this->faker->name('male'),
                    'mother_name' => $this->faker->name('female'),
                    'personal_email' => $this->faker->unique()->safeEmail,
                    'official_email' => $this->faker->email(),
                    'personal_contact_no' => $this->faker->phoneNumber(),
                    'official_contact_no' => $this->faker->unique()->phoneNumber(),
                ]);
            }, 5);
        });
    }
}
