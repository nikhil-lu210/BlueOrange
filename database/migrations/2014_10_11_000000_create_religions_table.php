<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
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
        Schema::create('religions', function (Blueprint $table) {
            $table->id();

            $table->string('name')->unique();
            $table->string('slug')->unique();

            $table->softDeletes();
        });

        // Insert predefined religions with slug
        $religions = ['Hinduism', 'Islam', 'Christianity', 'Buddhism', 'Sikhism', 'Jainism', 'Judaism', 'Zoroastrianism', 'Other'];

        $data = array_map(fn($name) => ['name' => $name, 'slug' => Str::slug($name)], $religions);

        DB::table('religions')->insert($data);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('religions');
        Schema::table("religions", function ($table) {
            $table->dropSoftDeletes();
        });
    }
};
