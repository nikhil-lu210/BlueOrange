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
        Schema::create('quiz_question_quiz_test', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_test_id')->constrained()->onDelete('cascade');
            $table->foreignId('quiz_question_id')->constrained()->onDelete('cascade');

            // Extra fields
            $table->string('selected_option')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->timestamp('answered_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_question_quiz_test');
    }
};
