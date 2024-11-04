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
        Schema::create('leave_availables', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            
            $table->year('for_year');
                
            $table->string('earned_leave', 20)->nullable()->comment('Store as hh:mm:ss format');
            $table->string('casual_leave', 20)->nullable()->comment('Store as hh:mm:ss format');
            $table->string('sick_leave', 20)->nullable()->comment('Store as hh:mm:ss format');

            $table->unique(['user_id', 'for_year']);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_availables');
        Schema::table("leave_availables", function ($table) {
            $table->dropSoftDeletes();
        });
    }
};
