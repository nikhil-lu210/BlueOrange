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
        // Set your weekends here
        $weekends = json_decode(json_encode([
            [
                "day" => "Saturday", 
                "active" => true
            ],
            [
                "day" => "Sunday", 
                "active" => true
            ],
            [
                "day" => "Monday", 
                "active" => false
            ],
            [
                "day" => "Tuesday", 
                "active" => false
            ],
            [
                "day" => "Wednesday", 
                "active" => false
            ],
            [
                "day" => "Thursday", 
                "active" => false
            ],
            [
                "day" => "Friday", 
                "active" => false
            ],
        ]));

        foreach ($weekends as $day) {
            Weekend::create([
                'day' => $day->day,
                'is_active' => $day->active,
            ]);
        }
    }
}
