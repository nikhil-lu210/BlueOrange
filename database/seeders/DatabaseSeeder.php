<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\Role\RolesTableSeeder;
use Database\Seeders\User\UsersTableSeeder;
use Database\Seeders\Permission\PermissionsTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionsTableSeeder::class,
            RolesTableSeeder::class,
            UsersTableSeeder::class
        ]);
    }
}
