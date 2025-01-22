<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leave_alloweds', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
                
            $table->string('earned_leave', 20)->nullable()->comment('Store as hh:mm:ss format');
            $table->string('casual_leave', 20)->nullable()->comment('Store as hh:mm:ss format');
            $table->string('sick_leave', 20)->nullable()->comment('Store as hh:mm:ss format');

            $table->string('implemented_from', 5)->default('01-01')->comment('Store as mm-dd format');
            $table->string('implemented_to', 5)->default('12-31')->comment('Store as mm-dd format');

            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_alloweds');
        Schema::table("leave_alloweds", function ($table) {
            $table->dropSoftDeletes();
        });
    }
};
