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
        Schema::create('institutes', function (Blueprint $table) {
            $table->id();

            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        // Seed institutes from JSON data
        $this->seedInstitutes();
    }

    /**
     * Seed institutes from JSON data
     */
    private function seedInstitutes(): void
    {
        $jsonFiles = [
            'universities.json',
            'collages.json',
            'banglaMediumSchools.json',
            'englishMediumSchools.json'
        ];

        $institutes = [];

        foreach ($jsonFiles as $file) {
            $filePath = public_path("assets/custom_data/bd-academic-data/{$file}");

            if (file_exists($filePath)) {
                $data = json_decode(file_get_contents($filePath), true);

                if (is_array($data)) {
                    foreach ($data as $instituteName) {
                        $name = trim($instituteName);
                        if (!empty($name)) {
                            $institutes[] = [
                                'name' => $name,
                                'slug' => Str::slug($name),
                                'description' => null,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                    }
                }
            }
        }

        // Remove duplicates based on name and handle slug conflicts
        $uniqueInstitutes = [];
        $seenNames = [];
        $seenSlugs = [];

        foreach ($institutes as $institute) {
            $lowerName = strtolower($institute['name']);
            if (!in_array($lowerName, $seenNames)) {
                $seenNames[] = $lowerName;

                // Handle slug conflicts
                $baseSlug = $institute['slug'];
                $slug = $baseSlug;
                $counter = 1;

                while (in_array($slug, $seenSlugs)) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }

                $seenSlugs[] = $slug;
                $institute['slug'] = $slug;
                $uniqueInstitutes[] = $institute;
            }
        }

        // Insert in chunks to avoid memory issues
        $chunks = array_chunk($uniqueInstitutes, 100);
        foreach ($chunks as $chunk) {
            DB::table('institutes')->insert($chunk);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institutes');
        Schema::table("institutes", function ($table) {
            $table->dropSoftDeletes();
        });
    }
};
