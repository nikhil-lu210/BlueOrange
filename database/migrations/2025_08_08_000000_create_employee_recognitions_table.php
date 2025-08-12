<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\PermissionModule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('employee_recognitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('team_leader_id');
            // Store the month as the first day of the month for uniqueness and range queries
            $table->date('month');

            // Five fixed criteria, each scored out of 20
            $table->unsignedTinyInteger('behavior');
            $table->unsignedTinyInteger('appreciation');
            $table->unsignedTinyInteger('leadership');
            $table->unsignedTinyInteger('loyalty');
            $table->unsignedTinyInteger('dedication');

            // Cached total score for easier reporting (0-100)
            $table->unsignedSmallInteger('total_score');

            // When set, the recognition is locked for the month (cannot be edited)
            $table->timestamp('locked_at')->nullable();

            $table->timestamps();

            // Constraints
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('team_leader_id')->references('id')->on('users')->onDelete('cascade');

            // Ensure only one recognition per employee per team leader per month
            $table->unique(['employee_id', 'team_leader_id', 'month'], 'uniq_employee_tl_month');
            $table->unique(['team_leader_id', 'employee_id', 'month'], 'uniq_tl_employee_month');

            // Useful indexes
            $table->index(['team_leader_id', 'month'], 'idx_tl_month');
            $table->index(['employee_id', 'month'], 'idx_employee_month');
        });

        // Create recognition permissions
        $this->createRecognitionPermissions();
    }

    public function down(): void
    {
        // Remove recognition permissions
        $this->removeRecognitionPermissions();

        Schema::dropIfExists('employee_recognitions');
    }

    /**
     * Create recognition permissions if they don't exist
     */
    private function createRecognitionPermissions(): void
    {
        try {
            // Create or get the Recognition permission module
            $recognitionModule = PermissionModule::firstOrCreate(['name' => 'Recognition']);

            // Define recognition permissions
            $permissions = ['Everything', 'Create', 'Read', 'Update', 'Delete'];

            foreach ($permissions as $permission) {
                $permissionName = "Recognition {$permission}";

                // Create permission if it doesn't exist
                Permission::firstOrCreate([
                    'permission_module_id' => $recognitionModule->id,
                    'name' => $permissionName,
                ]);
            }

            // Assign recognition permissions to Developer role if it exists. This is to ensure that developers have access to the recognition module. This is necessary for the development process.
            $developerRole = Role::where('name', 'Developer')->first();
            if ($developerRole) {
                $recognitionPermissions = [
                    'Recognition Everything',
                    'Recognition Create',
                    'Recognition Read',
                    'Recognition Update',
                    'Recognition Delete'
                ];

                // Only give permissions that don't already exist for this role
                foreach ($recognitionPermissions as $permissionName) {
                    if (!$developerRole->hasPermissionTo($permissionName)) {
                        $developerRole->givePermissionTo($permissionName);
                    }
                }
            }

        } catch (\Exception $e) {
            // Log the error but don't fail the migration
            \Log::warning('Failed to create recognition permissions during migration: ' . $e->getMessage());
        }
    }

    /**
     * Remove recognition permissions
     */
    private function removeRecognitionPermissions(): void
    {
        try {
            // Find the Recognition permission module
            $recognitionModule = PermissionModule::where('name', 'Recognition')->first();

            if ($recognitionModule) {
                // Remove all permissions associated with this module
                Permission::where('permission_module_id', $recognitionModule->id)->delete();

                // Remove the permission module
                $recognitionModule->delete();
            }

        } catch (\Exception $e) {
            // Log the error but don't fail the migration rollback
            \Log::warning('Failed to remove recognition permissions during migration rollback: ' . $e->getMessage());
        }
    }
};
