<?php

namespace Database\Seeders;

use App\Models\Specification;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SpecificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Specification::create(['name' => 'Color']);
        Specification::create(['name' => 'Size']);
    }
}
