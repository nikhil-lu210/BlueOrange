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
        Schema::create('dining_room_bookings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('employee_shift_id')->constrained()->onUpdate('cascade')->onDelete('cascade');

            $table->date('booking_date');
            $table->time('booking_time');

            $table->enum('status', ['Active', 'Cancelled'])->default('Active');

            $table->timestamps();
            $table->softDeletes();
        });

        assign_permission('Dining Room Booking');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dining_room_bookings');
        Schema::table("dining_room_bookings", function ($table) {
            $table->dropSoftDeletes();
        });
    }
};
