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
        Schema::create('monthly_salary_breakdowns', function (Blueprint $table) {
            $table->id();

            $table->foreignId('monthly_salary_id')
                  ->constrained()
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->enum('type', ['Plus (+)', 'Minus (-)'])->default('Plus (+)');
            $table->string('reason');
            $table->float('total', 8, 2);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_salary_breakdowns');
        Schema::table("monthly_salary_breakdowns", function ($table) {
            $table->dropSoftDeletes();
        });
    }
};
