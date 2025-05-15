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
        Schema::create('group_chat_file_media', function (Blueprint $table) {
            $table->id();

            $table->foreignId('group_chatting_id')
                ->constrained('group_chattings')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('uploader_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('file_name');
            $table->string('file_path');
            $table->string('mime_type');
            $table->string('file_extension');
            $table->integer('file_size');
            $table->string('original_name');
            $table->boolean('is_image')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_chat_file_media');
        Schema::table("group_chat_file_media", function ($table) {
            $table->dropSoftDeletes();
        });
    }
};
