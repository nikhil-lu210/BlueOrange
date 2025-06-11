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
        Schema::create('penalties', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->comment('Employee who received the penalty');

            $table->foreignId('attendance_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->comment('Related attendance record');

            $table->enum('type', [
                'Dress Code Violation',
                'Unauthorized Break',
                'Bad Attitude',
                'Unexcused Absence',
                'Unauthorized Leave',
                'Unauthorized Overtime',
                'Other'
            ])->comment('Type of penalty');

            $table->integer('total_time')
                ->comment('Penalty amount in minutes');

            $table->text('reason')
                ->comment('Reason for the penalty');

            $table->foreignId('creator_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->comment('User who created the penalty');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penalties');
        Schema::table("penalties", function ($table) {
            $table->dropSoftDeletes();
        });
    }
};
