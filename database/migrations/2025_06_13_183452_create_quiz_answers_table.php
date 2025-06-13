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
        Schema::create('quiz_answers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('quiz_test_id')
                ->constrained('quiz_tests')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('quiz_question_id')
                ->constrained('quiz_questions')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->char('selected_option', 1)->comment('Option: A / B / C / D');
            $table->boolean('is_correct');
            $table->timestamp('answered_at')->nullable();

            $table->unique(['quiz_test_id', 'quiz_question_id'], 'quiz_test_id_quiz_question_id_unique');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_answers');
        Schema::table("quiz_answers", function ($table) {
            $table->dropSoftDeletes();
        });
    }
};
