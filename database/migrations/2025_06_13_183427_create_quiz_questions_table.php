<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
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
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('creator_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->text('question');
            $table->text('option_a');
            $table->text('option_b');
            $table->text('option_c');
            $table->text('option_d');
            $table->char('correct_option', 1)->comment('Option: A / B / C / D');
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });

        // Create Quiz permissions
        $this->createQuizPermissions();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove Quiz permissions
        $this->removeQuizPermissions();

        Schema::dropIfExists('quiz_questions');
        Schema::table("quiz_questions", function ($table) {
            $table->dropSoftDeletes();
        });
    }




    /**
     * Create quiz permissions if they don't exist
     */
    private function createQuizPermissions(): void
    {
        try {
            // Create or get the Quiz permission module
            $quizModule = PermissionModule::firstOrCreate(['name' => 'Quiz']);

            // Define quiz permissions
            $permissions = ['Everything', 'Create', 'Read', 'Update', 'Delete'];

            foreach ($permissions as $permission) {
                $permissionName = "Quiz {$permission}";

                // Create permission if it doesn't exist
                Permission::firstOrCreate([
                    'permission_module_id' => $quizModule->id,
                    'name' => $permissionName,
                ]);
            }

            // Assign quiz permissions to Developer role if it exists. This is to ensure that developers have access to the quiz module. This is necessary for the development process.
            $developerRole = Role::where('name', 'Developer')->first();
            if ($developerRole) {
                $quizPermissions = [
                    'Quiz Everything',
                    'Quiz Create',
                    'Quiz Read',
                    'Quiz Update',
                    'Quiz Delete'
                ];

                // Only give permissions that don't already exist for this role
                foreach ($quizPermissions as $permissionName) {
                    if (!$developerRole->hasPermissionTo($permissionName)) {
                        $developerRole->givePermissionTo($permissionName);
                    }
                }
            }

        } catch (\Exception $e) {
            // Log the error but don't fail the migration
            \Log::warning('Failed to create quiz permissions during migration: ' . $e->getMessage());
        }
    }

    /**
     * Remove quiz permissions
     */
    private function removeQuizPermissions(): void
    {
        try {
            // Find the quiz permission module
            $quizModule = PermissionModule::where('name', 'Quiz')->first();

            if ($quizModule) {
                // Remove all permissions associated with this module
                Permission::where('permission_module_id', $quizModule->id)->delete();

                // Remove the permission module
                $quizModule->delete();
            }

        } catch (\Exception $e) {
            // Log the error but don't fail the migration rollback
            \Log::warning('Failed to remove quiz permissions during migration rollback: ' . $e->getMessage());
        }
    }
};
