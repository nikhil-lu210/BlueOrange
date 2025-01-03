<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\Role\RolesTableSeeder;
use Database\Seeders\User\UsersTableSeeder;
use Database\Seeders\Weekend\WeekendSeeder;
use Database\Seeders\Settings\SettingsSeeder;
use Database\Seeders\Attendance\AttendanceSeeder;
use Database\Seeders\DailyBreak\DailyBreakSeeder;
use Database\Seeders\Accounts\IncomeExpenseSeeder;
use Database\Seeders\Permission\PermissionsTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            WeekendSeeder::class,
            SettingsSeeder::class,
            
            PermissionsTableSeeder::class,
            RolesTableSeeder::class,
            UsersTableSeeder::class,

            // AttendanceSeeder::class,
            // DailyBreakSeeder::class,

            // IncomeExpenseSeeder::class
        ]);
    }
}
