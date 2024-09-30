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
        Schema::create('group_chattings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('chatting_group_id')
                ->constrained('chatting_groups')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('sender_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            
            $table->text('message')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_chattings');
        Schema::table("group_chattings", function ($table) {
            $table->dropSoftDeletes();
        });
    }
};
