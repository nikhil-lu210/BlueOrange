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
        Schema::create('it_tickets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('creator_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('title');
            $table->text('description')->nullable();
            $table->json('seen_by')->nullable();

            $table->foreignId('solved_by')
                ->nullable()
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->dateTime('solved_at')->nullable();

            $table->enum('status', ['Pending', 'Running', 'Solved', 'Canceled'])->nullable();
            $table->text('solver_note')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('it_tickets');
        Schema::table("it_tickets", function ($table) {
            $table->dropSoftDeletes();
        });
    }
};
