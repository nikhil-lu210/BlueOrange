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
        Schema::create('work_schedule_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_schedule_id')->constrained()->onDelete('cascade');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('work_type', ['Client', 'Internal', 'Bench']);
            $table->string('work_title');
            $table->integer('duration_minutes'); // Calculated duration
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['work_schedule_id', 'start_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_schedule_items');
    }
};
