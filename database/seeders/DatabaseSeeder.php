<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\SizeSeeder;
use Database\Seeders\DressSeeder;
use Database\Seeders\SpecificationSeeder;
use Database\Seeders\SpecificationOptionSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            
            SpecificationSeeder::class,
            SpecificationOptionSeeder::class,
            DressSeeder::class,
        ]);
    }
}
