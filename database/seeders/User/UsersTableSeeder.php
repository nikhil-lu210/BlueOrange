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
            'userid' => strtoupper(Str::random(8)),
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
        // Create associated EmployeeShift
        $developer->employee_shifts()->create([
            'start_time' => '14:00:00',
            'end_time' => '22:00:00',
            'implemented_from' => date('Y-m-d'),
        ]);
        
        
        // Create a superAdmin
        $superAdmin = User::create([
            'userid' => strtoupper(Str::random(8)),
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
        // Create associated EmployeeShift
        $superAdmin->employee_shifts()->create([
            'start_time' => '14:00:00',
            'end_time' => '22:00:00',
            'implemented_from' => date('Y-m-d'),
        ]);
        
        
        // Create a admin
        $admin = User::create([
            'userid' => strtoupper(Str::random(8)),
            'first_name' => 'Demo',
            'last_name' => 'Admin',
            'name' => 'Demo Admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);
        // Assign a role to the admin
        $adminRole = Role::findByName('Admin');
        $admin->assignRole($adminRole);
        // Create associated EmployeeShift
        $admin->employee_shifts()->create([
            'start_time' => '14:00:00',
            'end_time' => '22:00:00',
            'implemented_from' => date('Y-m-d'),
        ]);
        
        
        // Create a HR Manager
        $hr = User::create([
            'userid' => strtoupper(Str::random(8)),
            'first_name' => 'Demo',
            'last_name' => 'HR Manager',
            'name' => 'Demo HR Manager',
            'email' => 'hr@mail.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);
        // Assign a role to the HR Manager
        $hrRole = Role::findByName('HR Manager');
        $hr->assignRole($hrRole);
        // Create associated EmployeeShift
        $hr->employee_shifts()->create([
            'start_time' => '14:00:00',
            'end_time' => '22:00:00',
            'implemented_from' => date('Y-m-d'),
        ]);
        
        
        // Create a Team Leader
        $tl = User::create([
            'userid' => strtoupper(Str::random(8)),
            'first_name' => 'Demo',
            'last_name' => 'Team Leader',
            'name' => 'Demo Team Leader',
            'email' => 'tl@mail.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);
        // Assign a role to the Team Leader
        $tlRole = Role::findByName('Team Leader');
        $tl->assignRole($tlRole);
        // Create associated EmployeeShift
        $tl->employee_shifts()->create([
            'start_time' => '14:00:00',
            'end_time' => '22:00:00',
            'implemented_from' => date('Y-m-d'),
        ]);
        
        
        // Create a Employee
        $employee = User::create([
            'userid' => strtoupper(Str::random(8)),
            'first_name' => 'Demo',
            'last_name' => 'Employee',
            'name' => 'Demo Employee',
            'email' => 'employee@mail.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);
        // Assign a role to the Employee
        $employeeRole = Role::findByName('Employee');
        $employee->assignRole($employeeRole);
        // Create associated EmployeeShift
        $employee->employee_shifts()->create([
            'start_time' => '14:00:00',
            'end_time' => '22:00:00',
            'implemented_from' => date('Y-m-d'),
        ]);
    }
}
