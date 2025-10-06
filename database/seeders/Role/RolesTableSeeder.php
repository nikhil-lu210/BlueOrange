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
            // Use firstOrCreate to avoid duplicate roles
            $roleInstance = Role::firstOrCreate([
                'name' => $role,
            ], [
                'guard_name' => 'web', // Default guard name for Spatie roles
            ]);

            // Assign permissions to roles based on the module
            if ($role === 'Developer') {
                $permissions = [
                    'App Setting Everything',
                    'App Setting Create',
                    'App Setting Read',
                    'App Setting Update',
                    'App Setting Delete',

                    'Logs Everything',
                    'Logs Create',
                    'Logs Read',
                    'Logs Update',
                    'Logs Delete',

                    'Certificate Everything',
                    'Certificate Create',
                    'Certificate Read',
                    'Certificate Update',
                    'Certificate Delete',

                    'Vault Everything',
                    'Vault Create',
                    'Vault Read',
                    'Vault Update',
                    'Vault Delete',

                    'IT Ticket Everything',
                    'IT Ticket Create',
                    'IT Ticket Read',
                    'IT Ticket Update',
                    'IT Ticket Delete',

                    'User Interaction Everything',
                    'User Interaction Create',
                    'User Interaction Read',
                    'User Interaction Update',
                    'User Interaction Delete',

                    'Attendance Everything',
                    'Attendance Create',
                    'Attendance Read',
                    'Attendance Update',
                    'Attendance Delete',

                    'Leave Allowed Everything',
                    'Leave Allowed Create',
                    'Leave Allowed Read',
                    'Leave Allowed Update',
                    'Leave Allowed Delete',

                    'Leave History Everything',
                    'Leave History Create',
                    'Leave History Read',
                    'Leave History Update',
                    'Leave History Delete',

                    'Daily Break Everything',
                    'Daily Break Create',
                    'Daily Break Read',
                    'Daily Break Update',
                    'Daily Break Delete',

                    'Announcement Everything',
                    'Announcement Create',
                    'Announcement Read',
                    'Announcement Update',
                    'Announcement Delete',

                    'Task Everything',
                    'Task Create',
                    'Task Read',
                    'Task Update',
                    'Task Delete',

                    'Daily Work Update Everything',
                    'Daily Work Update Create',
                    'Daily Work Update Read',
                    'Daily Work Update Update',
                    'Daily Work Update Delete',

                    'Permission Everything',
                    'Permission Create',
                    'Permission Read',
                    'Permission Update',
                    'Permission Delete',

                    'Role Everything',
                    'Role Create',
                    'Role Read',
                    'Role Update',
                    'Role Delete',

                    'User Everything',
                    'User Create',
                    'User Read',
                    'User Update',
                    'User Delete',

                    'Salary Everything',
                    'Salary Create',
                    'Salary Read',
                    'Salary Update',
                    'Salary Delete',

                    'Income Everything',
                    'Income Create',
                    'Income Read',
                    'Income Update',
                    'Income Delete',

                    'Expense Everything',
                    'Expense Create',
                    'Expense Read',
                    'Expense Update',
                    'Expense Delete',

                    'Weekend Everything',
                    'Weekend Create',
                    'Weekend Read',
                    'Weekend Update',
                    'Weekend Delete',

                    'Holiday Everything',
                    'Holiday Create',
                    'Holiday Read',
                    'Holiday Update',
                    'Holiday Delete',

                    'Group Chatting Everything',
                    'Group Chatting Create',
                    'Group Chatting Read',
                    'Group Chatting Update',
                    'Group Chatting Delete',

                    'Dining Room Booking Everything',
                    'Dining Room Booking Create',
                    'Dining Room Booking Read',
                    'Dining Room Booking Update',
                    'Dining Room Booking Delete',

                    'Event Everything',
                    'Event Create',
                    'Event Read',
                    'Event Update',
                    'Event Delete',
                ];
            } elseif ($role === 'Super Admin') {
                $permissions = [
                    'Logs Read',

                    'Certificate Everything',
                    'Certificate Create',
                    'Certificate Read',
                    'Certificate Update',
                    'Certificate Delete',

                    'Vault Create',
                    'Vault Read',
                    'Vault Update',
                    'Vault Delete',

                    'IT Ticket Create',
                    'IT Ticket Read',
                    'IT Ticket Update',
                    'IT Ticket Delete',

                    'User Interaction Create',
                    'User Interaction Read',
                    'User Interaction Update',
                    'User Interaction Delete',

                    'Attendance Create',
                    'Attendance Read',
                    'Attendance Update',
                    'Attendance Delete',

                    'Leave Allowed Create',
                    'Leave Allowed Read',
                    'Leave Allowed Update',
                    'Leave Allowed Delete',

                    'Leave History Create',
                    'Leave History Read',
                    'Leave History Update',
                    'Leave History Delete',

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

                    'Income Create',
                    'Income Read',
                    'Income Update',
                    'Income Delete',

                    'Expense Create',
                    'Expense Read',
                    'Expense Update',
                    'Expense Delete',

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

                    'Dining Room Booking Everything',
                    'Dining Room Booking Create',
                    'Dining Room Booking Read',
                    'Dining Room Booking Update',
                    'Dining Room Booking Delete',

                    'Penalty Everything',
                    'Penalty Create',
                    'Penalty Read',
                    'Penalty Update',
                    'Penalty Delete',

                    'Event Create',
                    'Event Read',
                    'Event Update',
                    'Event Delete',
                ];
            } else {
                $permissions = [
                    'Announcement Read',

                    'Certificate Read',

                    'Attendance Read',

                    'Leave Allowed Read',

                    'Leave History Create',
                    'Leave History Read',

                    'Daily Break Create',
                    'Daily Break Read',

                    'Task Read',

                    'Daily Work Update Create',
                    'Daily Work Update Read',

                    'User Read',

                    'Salary Read',

                    'Holiday Read',

                    'Group Chatting Read',

                    'Dining Room Booking Create',
                    'Dining Room Booking Read',
                ];
            }

            // Sync permissions to handle both new and existing roles properly
            $roleInstance->syncPermissions($permissions);
        }
    }
}
