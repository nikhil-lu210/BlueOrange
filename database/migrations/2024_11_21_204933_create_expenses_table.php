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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('creator_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('category_id')
                ->constrained('income_expense_categories')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('title');
            $table->date('date');
            $table->tinyInteger('quantity');
            $table->float('price', 8, 2);
            $table->float('total', 8, 2);
            $table->text('description')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
        Schema::table("expenses", function ($table) {
            $table->dropSoftDeletes();
        });
    }
};
