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
        $weekends = ['Saturday', 'Sunday'];  // Set your weekends here

        foreach ($weekends as $day) {
            Weekend::create([
                'day' => $day,
                'is_active' => true,
            ]);
        }
    }
}
