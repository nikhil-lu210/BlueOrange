<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('education_levels', function (Blueprint $table) {
            $table->id();

            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        // Seed education levels from JSON data
        $this->seedEducationLevels();
    }

    /**
     * Seed education levels from JSON data
     */
    private function seedEducationLevels(): void
    {
        $filePath = public_path('assets/custom_data/bd-academic-data/eduLevels.json');

        if (file_exists($filePath)) {
            $educationLevels = json_decode(file_get_contents($filePath), true);

            if (is_array($educationLevels)) {
                $data = [];

                foreach ($educationLevels as $level) {
                    $title = trim($level);
                    if (!empty($title)) {
                        $data[] = [
                            'title' => $title,
                            'slug' => Str::slug($title),
                            'description' => null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }

                if (!empty($data)) {
                    DB::table('education_levels')->insert($data);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_levels');
        Schema::table("education_levels", function ($table) {
            $table->dropSoftDeletes();
        });
    }
};
