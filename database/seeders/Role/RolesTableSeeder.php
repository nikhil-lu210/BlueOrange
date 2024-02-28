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
                    'Attendance Create',
                    'Attendance Read',
                    'Attendance Update',
                    'Attendance Delete',
                    
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
                ];
            } elseif ($role === 'Super Admin') {
                $permissions = [
                    'Attendance Create',
                    'Attendance Read',
                    'Attendance Update',
                    'Attendance Delete',
                    
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
                ];
            } elseif ($role === 'Admin') {
                $permissions = [
                    'Attendance Read',
                    
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
                ];
            } elseif ($role === 'HR Manager') {
                $permissions = [
                    'Attendance Read',
                    
                    'Permission Read',
                    
                    'Role Read',
                    
                    'User Create',
                    'User Read',
                    'User Update',
                    
                    'Salary Create',
                    'Salary Read',
                    'Salary Update',
                ];
            } elseif ($role === 'Team Leader') {
                $permissions = [
                    'User Read',

                    'Salary Read',
                ];
            } elseif ($role === 'Employee') {
                $permissions = [
                    'User Read',

                    'Salary Read',
                ];
            } else {
                $permissions = [
                    'User Read',

                    'Salary Read',
                ];
            }

            $roleInstance = Role::findByName($role);
            $roleInstance->givePermissionTo($permissions);
        }
    }
}