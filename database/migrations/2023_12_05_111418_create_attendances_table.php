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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained()
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreignId('employee_shift_id')
                  ->constrained()
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->date('clock_in_date');
            $table->dateTime('clock_in');
            $table->dateTime('clock_out')->nullable();
            $table->string('total_time')->nullable()->comment('hh:mm:ss format to be store');
            $table->string('total_adjusted_time')->nullable()->comment('hh:mm:ss format to be store');
            $table->enum('type', ['Regular', 'Overtime'])->default('Regular');

            $table->enum('clockin_medium', ['Manual', 'QR-Code', 'Barcode'])->default('Manual');
            $table->enum('clockout_medium', ['Manual', 'QR-Code', 'Barcode'])->nullable();

            $table->foreignId('clockin_scanner_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('clockout_scanner_id')->nullable()->constrained('users')->onDelete('cascade');

            $table->ipAddress('ip_address')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('time_zone')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
        Schema::table("attendances", function ($table) {
            $table->dropSoftDeletes();
        });
    }
};
