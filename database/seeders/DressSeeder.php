<?php

namespace Database\Seeders;

use Illuminate\Database\Seeders;
use Illuminate\Database\Seeder;

use App\Models\Dress;
use App\Models\Size;

class DressSeeder extends Seeder
{
    public function run()
    {
        $sizes = Size::all();

        $dresses = [
            [
                'name' => 'Elegant White Dress',
                'image' => 'images/White-Dress.png',
                'description' => 'A beautiful elegant white dress perfect for weddings.',
                'size_id' => $sizes->random()->id,
                'quantity' => 10,
                'rental_price' => 150.00,
            ],
            [
                'name' => 'Classic Black Dress',
                'image' => 'images/black-dress.png',
                'description' => 'A classic black dress suitable for formal events.',
                'size_id' => $sizes->random()->id,
                'quantity' => 5,
                'rental_price' => 120.00,
            ],
        ];

        foreach ($dresses as $dress) {
            Dress::create($dress);
        }
    }
}
