<?php

use Exception;
use App\Models\PermissionModule;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penalties', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->comment('Employee who received the penalty');

            $table->foreignId('attendance_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->comment('Related attendance record');

            $table->enum('type', [
                'Dress Code Violation',
                'Unauthorized Break',
                'Bad Attitude',
                'Unexcused Absence',
                'Unauthorized Leave',
                'Unauthorized Overtime',
                'Other'
            ])->comment('Type of penalty');

            $table->integer('total_time')
                ->comment('Penalty amount in minutes');

            $table->text('reason')
                ->comment('Reason for the penalty');

            $table->foreignId('creator_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->comment('User who created the penalty');

            $table->timestamps();
            $table->softDeletes();
        });

        // Create penalty permissions
        $this->createPenaltyPermissions();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove penalty permissions
        $this->removePenaltyPermissions();

        Schema::dropIfExists('penalties');
        Schema::table("penalties", function ($table) {
            $table->dropSoftDeletes();
        });
    }

    /**
     * Create penalty permissions if they don't exist
     */
    private function createPenaltyPermissions(): void
    {
        try {
            // Create or get the Penalty permission module
            $penaltyModule = PermissionModule::firstOrCreate(['name' => 'Penalty']);

            // Define penalty permissions
            $permissions = ['Everything', 'Create', 'Read', 'Update', 'Delete'];

            foreach ($permissions as $permission) {
                $permissionName = "Penalty {$permission}";

                // Create permission if it doesn't exist
                Permission::firstOrCreate([
                    'permission_module_id' => $penaltyModule->id,
                    'name' => $permissionName,
                ]);
            }

            // Assign penalty permissions to Developer role if it exists. This is to ensure that developers have access to the penalty module. This is necessary for the development process.
            $developerRole = Role::where('name', 'Developer')->first();
            if ($developerRole) {
                $penaltyPermissions = [
                    'Penalty Everything',
                    'Penalty Create',
                    'Penalty Read',
                    'Penalty Update',
                    'Penalty Delete'
                ];

                // Only give permissions that don't already exist for this role
                foreach ($penaltyPermissions as $permissionName) {
                    if (!$developerRole->hasPermissionTo($permissionName)) {
                        $developerRole->givePermissionTo($permissionName);
                    }
                }
            }

        } catch (Exception $e) {
            // Log the error but don't fail the migration
            Log::warning('Failed to create penalty permissions during migration: ' . $e->getMessage());
        }
    }

    /**
     * Remove penalty permissions
     */
    private function removePenaltyPermissions(): void
    {
        try {
            // Find the Penalty permission module
            $penaltyModule = PermissionModule::where('name', 'Penalty')->first();

            if ($penaltyModule) {
                // Remove all permissions associated with this module
                Permission::where('permission_module_id', $penaltyModule->id)->delete();

                // Remove the permission module
                $penaltyModule->delete();
            }

        } catch (Exception $e) {
            // Log the error but don't fail the migration rollback
            Log::warning('Failed to remove penalty permissions during migration rollback: ' . $e->getMessage());
        }
    }
};
