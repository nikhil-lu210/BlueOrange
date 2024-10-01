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
        // Create a developer
        $developer = User::create([
            'userid' => '20230201',
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
            'implemented_from' => date('Y-m-d'),
        ]);
        // Create associated employee for the developer
        $developer->employee()->create([
            'joining_date' => fake()->dateTimeBetween('-10 years', 'now')->format('Y-m-d'),
            'alias_name' => fake()->name('male'),
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
            'userid' => '20202020',
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
            'implemented_from' => date('Y-m-d'),
        ]);
        // Create associated employee for the superAdmin
        $superAdmin->employee()->create([
            'joining_date' => fake()->dateTimeBetween('-10 years', 'now')->format('Y-m-d'),
            'alias_name' => fake()->name('male'),
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
