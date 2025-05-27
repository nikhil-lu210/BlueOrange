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
        Schema::table('employees', function (Blueprint $table) {
            $table->foreignId('institute_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null')->after('blood_group');
            $table->foreignId('education_level_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null')->after('institute_id');
            $table->year('passing_year')->nullable()->after('education_level_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['institute_id']);
            $table->dropForeign(['education_level_id']);
            $table->dropColumn(['institute_id', 'education_level_id', 'passing_year']);
        });
    }
};
