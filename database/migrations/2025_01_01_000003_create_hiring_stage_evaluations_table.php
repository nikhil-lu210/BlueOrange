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
        Schema::create('hiring_stage_evaluations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('hiring_candidate_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('hiring_stage_id')->constrained()->onUpdate('cascade')->onDelete('cascade');

            // Assigned evaluator (interviewer/workshop taker)
            $table->foreignId('assigned_to')->constrained('users')->onUpdate('cascade')->onDelete('cascade');

            // Scheduled date and time for evaluation
            $table->dateTime('scheduled_at')->nullable();

            // Evaluation details
            $table->enum('status', ['pending', 'in_progress', 'completed', 'passed', 'failed'])->default('pending');
            $table->text('notes')->nullable();
            $table->text('feedback')->nullable();
            $table->tinyInteger('rating')->nullable(); // 1-10 rating scale

            // Timestamps for tracking
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // Who created/updated the evaluation
            $table->foreignId('created_by')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();

            // Ensure one evaluation per candidate per stage
            $table->unique(['hiring_candidate_id', 'hiring_stage_id'], 'unique_candidate_stage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hiring_stage_evaluations');
    }
};
