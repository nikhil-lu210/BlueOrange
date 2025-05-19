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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();

            // This will create 'commentable_id' and 'commentable_type'
            $table->morphs('commentable');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->text('comment');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("comments", function ($table) {
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('comments');
    }
};
