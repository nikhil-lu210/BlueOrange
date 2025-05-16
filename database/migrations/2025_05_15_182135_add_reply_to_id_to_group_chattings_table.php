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
        Schema::table('group_chattings', function (Blueprint $table) {
            $table->foreignId('reply_to_id')->nullable()
                ->constrained('group_chattings')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('group_chattings', function (Blueprint $table) {
            $table->dropForeign(['reply_to_id']);
            $table->dropColumn('reply_to_id');
        });
    }
};
