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
            'Logs',
            'Attendance',
            'Announcement',
            'Task',
            'Daily Work Update',
            'Permission',
            'Role',
            'User',
            'Salary',
            'Holiday',
        ];

        foreach ($modules as $module) {
            $permissionModule = PermissionModule::create([
                'name' => $module
            ]);

            $permissions = [
                'Create',
                'Read',
                'Update',
                'Delete',
            ];

            foreach ($permissions as $permission) {
                $permissionName = "{$permissionModule->name} {$permission}";
                
                Permission::create([
                    'permission_module_id' => $permissionModule->id,
                    'name' => $permissionName,
                ]);
            }
        }
    }
}