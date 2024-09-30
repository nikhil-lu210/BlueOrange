<?php

namespace Database\Seeders\DailyBreak;

use App\Models\DailyBreak\DailyBreak;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DailyBreakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DailyBreak::factory()->count(300)->create();
    }
}
