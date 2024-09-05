<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\AdminSeeder;
use Database\Seeders\DressSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\SpecificationSeeder;
use Database\Seeders\PermissionRoleSeeder;
use Database\Seeders\SpecificationOptionSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            AdminSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            PermissionRoleSeeder::class,
            SpecificationSeeder::class,
            SpecificationOptionSeeder::class,
            DressSeeder::class,
        ]);
    }
}
