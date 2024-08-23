<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
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
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('12345678'),
            'status' => Arr::random(['Active', 'Inactive', 'Fired', 'Resigned']),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }


    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            // Assign a random role
            $role = Role::inRandomOrder()->first();
            $user->assignRole($role);

            // Create associated EmployeeShift
            $user->employee_shifts()->create([
                'start_time' => '14:00:00',
                'end_time' => '22:00:00',
                'implemented_from' => now()->format('Y-m-d'),
            ]);
        });
    }
}
