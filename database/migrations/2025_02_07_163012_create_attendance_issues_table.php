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
        Schema::create('attendance_issues', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('attendance_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('employee_shift_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');

            $table->string('title');
            $table->date('clock_in_date');
            $table->dateTime('clock_in');
            $table->dateTime('clock_out');
            $table->text('reason');

            $table->enum('type', ['Regular', 'Overtime'])->default('Regular');
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->text('note')->nullable();

            $table->unique(['user_id', 'attendance_id', 'clock_in_date'], 'user_attendance_clock_in_date_unique');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_issues');
        Schema::table("attendance_issues", function ($table) {
            $table->dropSoftDeletes();
        });
    }
};
