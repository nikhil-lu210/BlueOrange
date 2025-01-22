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
        Schema::create('vaults', function (Blueprint $table) {
            $table->id();

            $table->foreignId('creator_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('name');
            $table->string('url')->nullable();
            $table->text('username');
            $table->text('password');
            $table->text('note')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaults');
        Schema::table("vaults", function ($table) {
            $table->dropSoftDeletes();
        });
    }
};
