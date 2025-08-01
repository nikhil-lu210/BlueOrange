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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();

            $table->string('reference_no', 10)->unique()->comment('Unique 10-character reference number');

            $table->foreignId('user_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('creator_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('type');

            $table->date('issue_date');

            // Fields for different certificate types
            $table->decimal('salary', 10, 2)->nullable();
            $table->date('resignation_date')->nullable();
            $table->date('resign_application_date')->nullable();
            $table->date('resignation_approval_date')->nullable();
            $table->date('release_date')->nullable();
            $table->string('release_reason')->nullable();
            $table->string('country_name')->nullable();
            $table->string('visiting_purpose')->nullable();
            $table->date('leave_starts_from')->nullable();
            $table->date('leave_ends_on')->nullable();
            $table->integer('email_sent')->default(0)->comment('Number of times email was sent to employee');

            $table->timestamps();
            $table->softDeletes();
        });

        // Create Certificate permissions if they don't exist
        $this->createCertificatePermissions();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
        Schema::table("certificates", function ($table) {
            $table->dropSoftDeletes();
        });
    }

    /**
     * Create certificate permissions if they don't exist
     */
    private function createCertificatePermissions(): void
    {
        try {
            // Create or get the Certificate permission module
            $certificateModule = PermissionModule::firstOrCreate(['name' => 'Certificate']);

            // Define certificate permissions
            $permissions = ['Everything', 'Create', 'Read', 'Update', 'Delete'];

            foreach ($permissions as $permission) {
                $permissionName = "Certificate {$permission}";

                // Create permission if it doesn't exist
                Permission::firstOrCreate([
                    'permission_module_id' => $certificateModule->id,
                    'name' => $permissionName,
                ]);
            }

            // Assign certificate permissions to Developer role if it exists
            $developerRole = Role::where('name', 'Developer')->first();
            if ($developerRole) {
                $certificatePermissions = [
                    'Certificate Everything',
                    'Certificate Create',
                    'Certificate Read',
                    'Certificate Update',
                    'Certificate Delete'
                ];

                // Only give permissions that don't already exist for this role
                foreach ($certificatePermissions as $permissionName) {
                    if (!$developerRole->hasPermissionTo($permissionName)) {
                        $developerRole->givePermissionTo($permissionName);
                    }
                }
            }

        } catch (\Exception $e) {
            // Log the error but don't fail the migration
            \Log::warning('Failed to create certificate permissions during migration: ' . $e->getMessage());
        }
    }
};
