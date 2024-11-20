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
        Schema::create('leave_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('leave_allowed_id')
                ->constrained('leave_alloweds')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->date('date');
            $table->string('total_leave', 20)->comment('Store as hh:mm:ss format');
            $table->enum('type', ['Earned', 'Casual', 'Sick'])->default('Casual');
            $table->boolean('is_paid_leave')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->longText('reason');
            
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->dateTime('reviewed_at')->nullable();
            $table->text('reviewer_note')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_histories');
        Schema::table("leave_histories", function ($table) {
            $table->dropSoftDeletes();
        });
    }
};
