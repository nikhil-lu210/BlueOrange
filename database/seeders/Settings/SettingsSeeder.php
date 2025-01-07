<?php

namespace Database\Seeders\Settings;

use App\Models\Settings\Settings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Command: php artisan db:seed --class=Database\Seeders\Settings\SettingsSeeder
     */
    public function run(): void
    {
        Settings::create([
            'key' => 'mobile_restriction',
            'value' => false,
        ]);

        Settings::create([
            'key' => 'computer_restriction',
            'value' => false,
        ]);

        Settings::create([
            'key' => 'allowed_ip_ranges',
            'value' => '[]',
        ]);
    }
}
