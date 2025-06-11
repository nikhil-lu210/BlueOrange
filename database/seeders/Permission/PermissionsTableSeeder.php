<?php

namespace Database\Seeders\Permission;

use App\Models\PermissionModule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            'App Setting',
            'Logs',
            'Vault',
            'IT Ticket',
            'Attendance',
            'Daily Break',
            'User Interaction',
            'Announcement',
            'Task',
            'Daily Work Update',
            'Permission',
            'Role',
            'User',
            'Salary',
            'Income',
            'Expense',
            'Weekend',
            'Holiday',
            'Group Chatting',
            'Leave Allowed',
            'Leave History',
            'Dining Room Booking',
            'Penalty',
        ];

        foreach ($modules as $module) {
            // Use firstOrCreate to avoid duplicate permission modules
            $permissionModule = PermissionModule::firstOrCreate([
                'name' => $module
            ]);

            $permissions = [
                'Everything',
                'Create',
                'Read',
                'Update',
                'Delete',
            ];

            foreach ($permissions as $permission) {
                $permissionName = "{$permissionModule->name} {$permission}";

                // Use firstOrCreate to avoid duplicate permissions
                Permission::firstOrCreate([
                    'name' => $permissionName,
                ], [
                    'permission_module_id' => $permissionModule->id,
                    'guard_name' => 'web', // Default guard name for Spatie permissions
                ]);
            }
        }
    }
}
