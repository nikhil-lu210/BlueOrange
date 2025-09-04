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
        Schema::create('functionality_walkthroughs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('creator_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('title');
            $table->json('assigned_roles')->nullable()->comment('JSON field to hold role IDs for assigned roles');
            $table->json('read_by_at')->nullable()->comment('JSON field to track read status by user');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('functionality_walkthrough_steps', function (Blueprint $table) {
            $table->id();

            $table->foreignId('walkthrough_id')
                ->constrained('functionality_walkthroughs')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('step_title');
            $table->longText('step_description');
            $table->integer('step_order')->default(1);

            $table->timestamps();
            $table->softDeletes();
        });

        assign_permission('Functionality Walkthrough');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('functionality_walkthroughs');
        Schema::table("functionality_walkthroughs", function ($table) {
            $table->dropSoftDeletes();
        });

        Schema::dropIfExists('functionality_walkthrough_steps');
        Schema::table("functionality_walkthrough_steps", function ($table) {
            $table->dropSoftDeletes();
        });
    }
};
