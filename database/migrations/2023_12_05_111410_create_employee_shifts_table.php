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
        Schema::create('employee_shifts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained()
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
                  
            $table->time('start_time');
            $table->time('end_time');
            $table->string('total_time')->comment('hh:mm:ss format to be store');

            $table->date('implemented_from');
            $table->date('implemented_to')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['user_id', 'start_time', 'end_time', 'implemented_from'], 'user_id_start_time_end_time_implemented_from_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_shifts');
        Schema::table("employee_shifts", function ($table) {
            $table->dropSoftDeletes();
        });
    }
};
