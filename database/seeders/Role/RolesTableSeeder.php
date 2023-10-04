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
                ];
            } elseif ($role === 'Super Admin') {
                $permissions = [
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
                ];
            } elseif ($role === 'Admin') {
                $permissions = [
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
                ];
            } elseif ($role === 'HR Manager') {
                $permissions = [
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
                ];
            } elseif ($role === 'Team Leader') {
                $permissions = [
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
                ];
            } elseif ($role === 'Employee') {
                $permissions = [
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
                ];
            } else {
                $permissions = [
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
                ];
            }

            $roleInstance = Role::findByName($role);
            $roleInstance->givePermissionTo($permissions);
        }
    }
}