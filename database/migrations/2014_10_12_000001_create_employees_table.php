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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->restrictOnDelete();

            $table->date('joining_date');
            $table->string('alias_name')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('personal_email')->unique('personal_email')->nullable();
            $table->string('official_email')->nullable();
            $table->string('personal_contact_no')->unique('personal_contact_no')->nullable();
            $table->string('official_contact_no')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
        Schema::table("employees", function ($table) {
            $table->dropSoftDeletes();
        });
    }
};
