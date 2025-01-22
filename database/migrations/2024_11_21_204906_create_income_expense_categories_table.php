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
        Schema::create('income_expense_categories', function (Blueprint $table) {
            $table->id();

            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income_expense_categories');
        Schema::table("income_expense_categories", function ($table) {
            $table->dropSoftDeletes();
        });
    }
};
