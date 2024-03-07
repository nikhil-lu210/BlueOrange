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
        Schema::create('monthly_salaries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained()
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreignId('salary_id')
                  ->constrained()
                  ->onUpdate('restrict')
                  ->onDelete('restrict');

            $table->float('salary');
            $table->enum('status', ['Paid', 'Pending', 'Canceled'])->default('Pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_salaries');
        Schema::table("monthly_salaries", function ($table) {
            $table->dropSoftDeletes();
        });
    }
};
