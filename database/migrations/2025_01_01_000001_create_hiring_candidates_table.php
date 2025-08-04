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
        Schema::create('hiring_candidates', function (Blueprint $table) {
            $table->id();
            
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('expected_role');
            $table->decimal('expected_salary', 10, 2)->nullable();
            $table->text('notes')->nullable();
            
            // Status: shortlisted, in_progress, rejected, hired
            $table->enum('status', ['shortlisted', 'in_progress', 'rejected', 'hired'])->default('shortlisted');
            
            // Current stage (1: Basic Interview, 2: Workshop, 3: Final Interview)
            $table->tinyInteger('current_stage')->default(1);
            
            // HR who added the candidate
            $table->foreignId('created_by')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            
            // User account created after hiring (nullable until hired)
            $table->foreignId('user_id')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');
            
            $table->timestamp('hired_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hiring_candidates');
    }
};
