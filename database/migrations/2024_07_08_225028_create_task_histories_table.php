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
        Schema::create('task_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('task_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('user_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            
            $table->dateTime('started_at');
            $table->dateTime('ends_at')->nullable();
            $table->string('total_worked')->nullable();

            $table->text('note')->nullable();

            $table->tinyInteger('progress')->min(0)->max(100)->default(0);

            $table->enum('status', ['Working', 'Completed'])->default('Working');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_histories');
        Schema::table("task_histories", function ($table) {
            $table->dropSoftDeletes();
        });
    }
};
