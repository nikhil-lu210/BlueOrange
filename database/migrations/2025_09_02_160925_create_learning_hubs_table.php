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
        Schema::create('learning_hubs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('creator_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('title');
            $table->longText('description');

            $table->json('recipients')->nullable()->comment('JSON field to hold user IDs for recipients');

            $table->json('read_by_at')->nullable()->comment('JSON field to track read status by user');

            $table->timestamps();
            $table->softDeletes();
        });

        assign_permission('Learning Hub');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_hubs');
        Schema::table("learning_hubs", function ($table) {
            $table->dropSoftDeletes();
        });
    }
};
