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

                    'User Interaction Create',
                    'User Interaction Read',
                    'User Interaction Update',
                    'User Interaction Delete',

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

                    'Daily Work Update Create',
                    'Daily Work Update Read',
                    'Daily Work Update Update',
                    'Daily Work Update Delete',
                    
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
                    
                    'Group Chatting Create',
                    'Group Chatting Read',
                    'Group Chatting Update',
                    'Group Chatting Delete',
                ];
            } elseif ($role === 'Super Admin') {
                $permissions = [
                    'Logs Read',

                    'User Interaction Create',
                    'User Interaction Read',
                    'User Interaction Update',
                    'User Interaction Delete',
                    
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

                    'Daily Work Update Create',
                    'Daily Work Update Read',
                    'Daily Work Update Update',
                    'Daily Work Update Delete',
                    
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
                    
                    'Group Chatting Create',
                    'Group Chatting Read',
                    'Group Chatting Update',
                    'Group Chatting Delete',
                ];
            } elseif ($role === 'Admin') {
                $permissions = [
                    'Attendance Read',
                    
                    'User Interaction Read',
                    'User Interaction Update',
                    
                    'Announcement Read',

                    'Task Create',
                    'Task Read',

                    'Daily Work Update Create',
                    'Daily Work Update Read',
                    'Daily Work Update Update',
                    
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
                    
                    'Group Chatting Create',
                    'Group Chatting Read',
                    'Group Chatting Update',
                    'Group Chatting Delete',
                ];
            } elseif ($role === 'HR Manager') {
                $permissions = [
                    'Attendance Read',
                    
                    'User Interaction Read',
                    'User Interaction Update',
                    
                    'Announcement Read',
                    
                    'Task Read',

                    'Daily Work Update Create',
                    'Daily Work Update Read',
                    
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
                    
                    'Group Chatting Create',
                    'Group Chatting Read',
                    'Group Chatting Update',
                    'Group Chatting Delete',
                ];
            } elseif ($role === 'Team Leader') {
                $permissions = [
                    'Announcement Read',
                    
                    'User Interaction Read',
                    'User Interaction Update',
                    
                    'Task Read',

                    'Daily Work Update Create',
                    'Daily Work Update Read',
                    'Daily Work Update Update',

                    'User Read',

                    'Salary Read',
                    
                    'Holiday Read',
                    
                    'Group Chatting Create',
                    'Group Chatting Read',
                    'Group Chatting Update',
                    'Group Chatting Delete',
                ];
            } elseif ($role === 'Employee') {
                $permissions = [
                    'Announcement Read',
                    
                    'Task Read',

                    'Daily Work Update Create',
                    'Daily Work Update Read',
                    
                    'User Read',

                    'Salary Read',
                    
                    'Holiday Read',
                    
                    'Group Chatting Read',
                ];
            } else {
                $permissions = [
                    'Announcement Read',
                    
                    'Task Read',

                    'Daily Work Update Create',
                    'Daily Work Update Read',
                    
                    'User Read',

                    'Salary Read',
                    
                    'Holiday Read',
                    
                    'Group Chatting Read',
                ];
            }

            $roleInstance = Role::findByName($role);
            $roleInstance->givePermissionTo($permissions);
        }
    }
}