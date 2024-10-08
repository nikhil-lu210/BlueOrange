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
            'Super Admin'
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);

            // Assign permissions to roles based on the module
            if ($role === 'Developer') {
                $permissions = [
                    'Logs Create',
                    'Logs Read',
                    'Logs Update',
                    'Logs Delete',

                    'User Interaction Create',
                    'User Interaction Read',
                    'User Interaction Update',
                    'User Interaction Delete',

                    'Attendance Create',
                    'Attendance Read',
                    'Attendance Update',
                    'Attendance Delete',

                    'Daily Break Create',
                    'Daily Break Read',
                    'Daily Break Update',
                    'Daily Break Delete',

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
                    
                    'Weekend Create',
                    'Weekend Read',
                    'Weekend Update',
                    'Weekend Delete',
                    
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

                    'Daily Break Create',
                    'Daily Break Read',
                    'Daily Break Update',
                    'Daily Break Delete',

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
                    
                    'Role Create',
                    'Role Read',
                    'Role Update',
                    
                    'User Create',
                    'User Read',
                    'User Update',
                    'User Delete',
                    
                    'Salary Create',
                    'Salary Read',
                    'Salary Update',
                    'Salary Delete',
                    
                    'Weekend Create',
                    'Weekend Read',
                    'Weekend Update',
                    'Weekend Delete',
                    
                    'Holiday Create',
                    'Holiday Read',
                    'Holiday Update',
                    'Holiday Delete',
                    
                    'Group Chatting Create',
                    'Group Chatting Read',
                    'Group Chatting Update',
                    'Group Chatting Delete',
                ];
            } else {
                $permissions = [
                    'Announcement Read',
                    
                    'Attendance Read',

                    'Daily Break Create',
                    'Daily Break Read',
                    
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