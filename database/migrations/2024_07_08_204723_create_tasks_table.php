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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            $table->string('taskid')->unique();

            $table->foreignId('chatting_id')
                ->unique()
                ->nullable()
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('creator_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            
            $table->string('title');
            $table->longText('description');
            $table->date('deadline')->nullable();

            $table->enum('priority', ['Low', 'Medium', 'Average', 'High'])->default('Average');
            $table->enum('status', ['Active', 'Running', 'Completed', 'Cancelled'])->default('Active');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
        Schema::table("tasks", function ($table) {
            $table->dropSoftDeletes();
        });
    }
};
