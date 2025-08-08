<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('employee_monthly_evaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('team_leader_id');
            // Store the month as the first day of the month for uniqueness and range queries
            $table->date('month');

            // Five fixed criteria, each scored out of 20
            $table->unsignedTinyInteger('behavior');
            $table->unsignedTinyInteger('appreciation');
            $table->unsignedTinyInteger('leadership');
            $table->unsignedTinyInteger('loyalty');
            $table->unsignedTinyInteger('dedication');

            // Cached total score for easier reporting (0-100)
            $table->unsignedSmallInteger('total_score');

            // When set, the evaluation is locked for the month (cannot be edited)
            $table->timestamp('locked_at')->nullable();

            $table->timestamps();

            // Constraints
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('team_leader_id')->references('id')->on('users')->onDelete('cascade');

            // Ensure only one evaluation per employee per team leader per month
            $table->unique(['employee_id', 'team_leader_id', 'month'], 'uniq_employee_tl_month');

            // Useful indexes
            $table->index(['team_leader_id', 'month'], 'idx_tl_month');
            $table->index(['employee_id', 'month'], 'idx_employee_month');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_monthly_evaluations');
    }
};
