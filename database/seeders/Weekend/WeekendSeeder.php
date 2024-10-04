<?php

namespace Database\Seeders\Weekend;

use App\Models\Weekend\Weekend;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WeekendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Weekend::create([
            'day' => 'Saturday',
            'is_active' => true,
        ]);
        
        Weekend::create([
            'day' => 'Sunday',
            'is_active' => true,
        ]);
    }
}
