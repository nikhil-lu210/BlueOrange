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
        Schema::create('daily_breaks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('attendance_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->date('date')->default(now()->toDateString());

            $table->dateTime('break_in_at');
            $table->dateTime('break_out_at')->nullable();
            $table->string('total_time')->nullable()->comment('hh:mm:ss');

            $table->enum('type', ['Short', 'Long'])->default('Short');

            $table->ipAddress('break_in_ip');
            $table->ipAddress('break_out_ip')->nullable();

            $table->text('note')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_breaks');
        Schema::table("daily_breaks", function ($table) {
            $table->dropSoftDeletes();
        });
    }
};
