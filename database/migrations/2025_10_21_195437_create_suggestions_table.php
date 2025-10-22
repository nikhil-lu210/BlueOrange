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
        Schema::create('suggestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable(); // if logged in
            $table->string('type');
            $table->string('module')->nullable();
            $table->string('title');
            $table->text('message');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['user_id']);
        });

        assign_permission('Suggestion');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suggestions');
        Schema::table("suggestions", function ($table) {
            $table->dropSoftDeletes();
        });
    }
};
