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
        Schema::create('recognitions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('category');
            $table->integer('total_mark');
            $table->text('comment');
            $table->foreignId('recognizer_id')->constrained('users')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });

        // Create Recognition permissions if they don't exist
        $this->createRecognitionPermissions();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recognitions');
        Schema::table("recognitions", function ($table) {
            $table->dropSoftDeletes();
        });
    }

    /**
     * Create Recognition permissions if they don't exist
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

            // Assign recognition permissions to Developer role if it exists
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
};
