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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained()
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
                  
            $table->float('basic_salary');
            $table->float('house_benefit');
            $table->float('transport_allowance');
            $table->float('medical_allowance');
            $table->float('night_shift_allowance')->nullable();
            $table->float('other_allowance')->nullable();
            $table->float('total');
            
            $table->date('implemented_from');
            $table->date('implemented_to')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['user_id', 'implemented_from'], 'user_id_implemented_from_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
        Schema::table("salaries", function ($table) {
            $table->dropSoftDeletes();
        });
    }
};
