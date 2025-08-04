<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hiring_stages', function (Blueprint $table) {
            $table->id();

            $table->string('name'); // Basic Interview, Workshop, Final Interview
            $table->text('description')->nullable();
            $table->tinyInteger('stage_order'); // 1, 2, 3
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });

        // Insert default stages
        DB::table('hiring_stages')->insert([
            [
                'name' => 'Basic Interview',
                'description' => 'Initial screening and basic interview with the candidate',
                'stage_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Workshop',
                'description' => 'Practical workshop to assess technical and practical skills',
                'stage_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Final Interview',
                'description' => 'Final interview with senior management for final decision',
                'stage_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hiring_stages');
    }
};
