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
        Schema::create('chatting_group_user', function (Blueprint $table) {
            $table->id();

            $table->foreignId('chatting_group_id')
                ->constrained('chatting_groups')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('user_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->enum('role', ['Admin', 'Member'])->default('Member');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatting_group_user');
        Schema::table("chatting_group_user", function ($table) {
            $table->dropSoftDeletes();
        });
    }
};
