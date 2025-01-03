<?php

namespace Database\Seeders\Settings;

use App\Models\Settings\Settings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Settings::create(
            [
                'key' => 'mobile_restriction',
                'value' => 'enabled',
            ],
            [
                'key' => 'computer_restriction',
                'value' => 'disabled',
            ],
        );
    }
}
