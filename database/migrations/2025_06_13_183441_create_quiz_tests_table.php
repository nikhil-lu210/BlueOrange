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
        Schema::create('quiz_tests', function (Blueprint $table) {
            $table->id();

            $table->string('testid')->unique();

            $table->foreignId('creator_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('candidate_name');
            $table->string('candidate_email');

            $table->integer('total_questions')->default(10)->comment('Total questions in the test');
            $table->integer('total_time')->default(10)->comment('In minutes');
            $table->integer('passing_score')->default(6)->comment('Passing score out of total questions. Must be less than or equal to total questions.');
            $table->json('question_ids');

            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->integer('attempted_questions')->nullable();
            $table->integer('total_score')->nullable();

            $table->boolean('auto_submitted')->default(false);
            $table->enum('status', ['Pending', 'Running', 'Completed', 'Cancelled'])->default('Pending');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_tests');
        Schema::table("quiz_tests", function ($table) {
            $table->dropSoftDeletes();
        });
    }
};
