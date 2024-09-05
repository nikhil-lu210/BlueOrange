<?php

namespace Database\Seeders\Role;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'Developer',
            'Super Admin',
            'Admin',
            'HR Manager',
            'Team Leader',
            'Employee',
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);

            // Assign permissions to roles based on the module
            if ($role === 'Developer') {
                $permissions = [
                    'Logs Read',

                    'Attendance Create',
                    'Attendance Read',
                    'Attendance Update',
                    'Attendance Delete',

                    'Announcement Create',
                    'Announcement Read',
                    'Announcement Update',
                    'Announcement Delete',

                    'Task Create',
                    'Task Read',
                    'Task Update',
                    'Task Delete',
                    
                    'Permission Create',
                    'Permission Read',
                    'Permission Update',
                    'Permission Delete',
                    
                    'Role Create',
                    'Role Read',
                    'Role Update',
                    'Role Delete',
                    
                    'User Create',
                    'User Read',
                    'User Update',
                    'User Delete',
                    
                    'Salary Create',
                    'Salary Read',
                    'Salary Update',
                    'Salary Delete',
                    
                    'Holiday Create',
                    'Holiday Read',
                    'Holiday Update',
                    'Holiday Delete',
                ];
            } elseif ($role === 'Super Admin') {
                $permissions = [
                    'Logs Read',
                    
                    'Attendance Create',
                    'Attendance Read',
                    'Attendance Update',
                    'Attendance Delete',

                    'Announcement Create',
                    'Announcement Read',
                    'Announcement Update',
                    'Announcement Delete',

                    'Task Create',
                    'Task Read',
                    'Task Update',
                    'Task Delete',
                    
                    'Permission Create',
                    'Permission Read',
                    'Permission Update',
                    'Permission Delete',
                    
                    'Role Create',
                    'Role Read',
                    'Role Update',
                    'Role Delete',
                    
                    'User Create',
                    'User Read',
                    'User Update',
                    'User Delete',
                    
                    'Salary Create',
                    'Salary Read',
                    'Salary Update',
                    'Salary Delete',
                    
                    'Holiday Create',
                    'Holiday Read',
                    'Holiday Update',
                    'Holiday Delete',
                ];
            } elseif ($role === 'Admin') {
                $permissions = [
                    'Attendance Read',
                    
                    'Announcement Read',

                    'Task Create',
                    'Task Read',
                    
                    'Permission Read',
                    
                    'Role Read',
                    
                    'User Create',
                    'User Read',
                    'User Update',
                    'User Delete',
                    
                    'Salary Create',
                    'Salary Read',
                    'Salary Update',
                    'Salary Delete',
                    
                    'Holiday Create',
                    'Holiday Read',
                    'Holiday Update',
                ];
            } elseif ($role === 'HR Manager') {
                $permissions = [
                    'Attendance Read',
                    
                    'Announcement Read',
                    
                    'Task Read',
                    
                    'Permission Read',
                    
                    'Role Read',
                    
                    'User Create',
                    'User Read',
                    'User Update',
                    
                    'Salary Create',
                    'Salary Read',
                    'Salary Update',
                    
                    'Holiday Create',
                    'Holiday Read',
                    'Holiday Update',
                ];
            } elseif ($role === 'Team Leader') {
                $permissions = [
                    'Announcement Read',
                    
                    'Task Read',

                    'User Read',

                    'Salary Read',
                    
                    'Holiday Read',
                ];
            } elseif ($role === 'Employee') {
                $permissions = [
                    'Announcement Read',
                    
                    'Task Read',
                    
                    'User Read',

                    'Salary Read',
                    
                    'Holiday Read',
                ];
            } else {
                $permissions = [
                    'Announcement Read',
                    
                    'Task Read',
                    
                    'User Read',

                    'Salary Read',
                    
                    'Holiday Read',
                ];
            }

            $roleInstance = Role::findByName($role);
            $roleInstance->givePermissionTo($permissions);
        }
    }
}