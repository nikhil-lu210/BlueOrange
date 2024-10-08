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

            $table->string('payslip_id')->unique();

            $table->foreignId('user_id')
                  ->constrained()
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreignId('salary_id')
                  ->constrained()
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
                  
            $table->string('for_month')->comment('previous month in Y-m format');
            $table->tinyInteger('total_workable_days');
            $table->tinyInteger('total_weekends');
            $table->tinyInteger('total_holidays')->nullable();
            $table->float('hourly_rate', 8, 2);

            $table->float('total_payable', 8, 2);
            $table->foreignId('paid_by')->nullable()->constrained('users');

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
