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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                    ->constrained('inventory_categories')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

            $table->foreignId('creator_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->string('name');
            $table->string('unique_number')->nullable();
            $table->float('price');
            $table->string('description')->nullable();
            $table->string('usage_for')->nullable();
            $table->enum('status', ['Available', 'In Use', 'Out of Service', 'Damaged'])->default('Available');

            $table->timestamps();
            $table->softDeletes();
        });

        assign_permission('Inventory');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
        Schema::table("inventories", function ($table) {
            $table->dropSoftDeletes();
        });
    }
};
