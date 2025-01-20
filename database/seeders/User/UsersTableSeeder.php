<?php

namespace Database\Seeders\User;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $joiningDate = fake()->dateTimeBetween('-10 years', 'now')->format('Y-m-d');

        // Create a developer
        $developer = User::create([
            'userid' => '00000001',
            'first_name' => 'Demo',
            'last_name' => 'Developer',
            'name' => 'Demo Developer',
            'email' => 'developer@mail.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);
        // Assign a role to the developer
        $developerRole = Role::findByName('Developer');
        $developer->assignRole($developerRole);
        // Assign team_leader
        $developer->employee_team_leaders()->attach($developer->id, ['is_active' => true]);
        // Attach the interaction for this developer
        $developer->interacted_users()->attach($developer->id);
        // Create associated EmployeeShift
        $developer->employee_shifts()->create([
            'start_time' => '14:00:00',
            'end_time' => '22:00:00',
            'total_time' => '08:00:00',
            'implemented_from' => date('Y-m-d'),
        ]);
        // Create associated Leave
        $developer->leave_alloweds()->create([
            'earned_leave' => '120:00:00',
            'casual_leave' => '120:00:00',
            'sick_leave' => '120:00:00',
            'implemented_from' => '01-01',
            'implemented_to' => '12-31',
        ]);
        // Create associated Salary
        $developer->salaries()->create([
            'basic_salary' => 5000,
            'house_benefit' => 1000,
            'transport_allowance' => 1000,
            'medical_allowance' => 1000,
            'night_shift_allowance' => 1000,
            'other_allowance' => 1000,
            'implemented_from' => $joiningDate,
            'total' => 10000,
        ]);
        // Create associated employee for the developer
        $developer->employee()->create([
            'joining_date' => $joiningDate,
            'alias_name' => 'Administration',
            'father_name' => fake()->name('male'),
            'mother_name' => fake()->name('female'),
            'birth_date' => fake()->dateTimeBetween('-30 years', '-20 years')->format('Y-m-d'),
            'personal_email' => fake()->unique()->safeEmail,
            'official_email' => fake()->email(),
            'personal_contact_no' => fake()->phoneNumber(),
            'official_contact_no' => fake()->unique()->phoneNumber(),
        ]);
        
        
        // Create a superAdmin
        $superAdmin = User::create([
            'userid' => '00000002',
            'first_name' => 'Demo',
            'last_name' => 'Super Admin',
            'name' => 'Demo Super Admin',
            'email' => 'superadmin@mail.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);
        // Assign a role to the superAdmin
        $superAdminRole = Role::findByName('Super Admin');
        $superAdmin->assignRole($superAdminRole);
        // Assign team_leader
        $superAdmin->employee_team_leaders()->attach($developer->id, ['is_active' => true]);
        // Attach the interaction for this superAdmin
        $superAdmin->interacted_users()->attach($developer->id);
        // Create associated EmployeeShift
        $superAdmin->employee_shifts()->create([
            'start_time' => '14:00:00',
            'end_time' => '22:00:00',
            'total_time' => '08:00:00',
            'implemented_from' => date('Y-m-d'),
        ]);
        // Create associated Leave
        $superAdmin->leave_alloweds()->create([
            'earned_leave' => '120:00:00',
            'casual_leave' => '120:00:00',
            'sick_leave' => '120:00:00',
            'implemented_from' => '01-01',
            'implemented_to' => '12-31',
        ]);
        // Create associated Salary
        $superAdmin->salaries()->create([
            'basic_salary' => 5000,
            'house_benefit' => 1000,
            'transport_allowance' => 1000,
            'medical_allowance' => 1000,
            'night_shift_allowance' => 1000,
            'other_allowance' => 1000,
            'implemented_from' => $joiningDate,
            'total' => 10000,
        ]);
        // Create associated employee for the superAdmin
        $superAdmin->employee()->create([
            'joining_date' => $joiningDate,
            'alias_name' => 'Controller',
            'father_name' => fake()->name('male'),
            'mother_name' => fake()->name('female'),
            'birth_date' => fake()->dateTimeBetween('-30 years', '-20 years')->format('Y-m-d'),
            'personal_email' => fake()->unique()->safeEmail,
            'official_email' => fake()->email(),
            'personal_contact_no' => fake()->phoneNumber(),
            'official_contact_no' => fake()->unique()->phoneNumber(),
        ]);

        // Seed fake users
        // User::factory()->count(100)->create();
    }
}
