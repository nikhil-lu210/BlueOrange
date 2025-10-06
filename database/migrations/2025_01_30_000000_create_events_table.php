<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\PermissionModule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->longText('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('location')->nullable();
            $table->enum('event_type', ['meeting', 'training', 'celebration', 'conference', 'workshop', 'other'])->default('meeting');
            $table->enum('status', ['Draft', 'Published', 'Cancelled', 'Completed'])->default('draft');
            $table->boolean('is_all_day')->default(false);
            $table->string('color', 7)->default('#3788d8');
            $table->integer('max_participants')->nullable();
            $table->integer('current_participants')->default(0);
            $table->boolean('is_public')->default(true);
            $table->integer('reminder_before')->nullable();
            $table->enum('reminder_unit', ['minutes', 'hours', 'days'])->default('hours');

            $table->foreignId('organizer_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });

        // Create event permissions
        $this->createEventPermissions();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove event permissions
        $this->removeEventPermissions();

        Schema::dropIfExists('events');
        Schema::table("events", function ($table) {
            $table->dropSoftDeletes();
        });
    }

    /**
     * Create event permissions if they don't exist
     */
    private function createEventPermissions(): void
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

        } catch (\Exception $e) {
            // Log the error but don't fail the migration
            \Log::warning('Failed to create event permissions during migration: ' . $e->getMessage());
        }
    }

    /**
     * Remove event permissions
     */
    private function removeEventPermissions(): void
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

        } catch (\Exception $e) {
            // Log the error but don't fail the migration rollback
            \Log::warning('Failed to remove event permissions during migration rollback: ' . $e->getMessage());
        }
    }
};
