<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('daily_work_updates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                    ->constrained()
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreignId('team_leader_id')
                    ->constrained('users')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
                    
            $table->date('date')->default(date('Y-m-d'));
            $table->longText('work_update')->comment('Daily Work Update Here.');
            $table->tinyInteger('progress')->default(0);
            $table->text('note')->nullable()->comment('Client Respond / Any Issue Note Here.');
            
            $table->tinyInteger('rating')->nullable();
            $table->text('comment')->nullable()->comment('Team Leader Comment Here.');

            $table->unique(['user_id', 'date'], 'user_id_date_unique');
                  
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_work_updates');
        Schema::table("daily_work_updates", function ($table) {
            $table->dropSoftDeletes();
        });
    }
};
