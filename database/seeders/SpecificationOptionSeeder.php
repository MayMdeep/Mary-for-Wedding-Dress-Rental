<?php

namespace Database\Seeders;

use App\Models\Specification;
use Illuminate\Database\Seeder;
use App\Models\SpecificationOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SpecificationOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $color = Specification::where('name', 'Color')->first();
        $size = Specification::where('name', 'Size')->first();

        SpecificationOption::create(['specification_id' => $color->id, 'name' => 'Red']);
        SpecificationOption::create(['specification_id' => $color->id, 'name' => 'Blue']);
        SpecificationOption::create(['specification_id' => $size->id, 'name' => 'Small']);
        SpecificationOption::create(['specification_id' => $size->id, 'name' => 'Medium']);
    }
}
