<?php

namespace Database\Seeders;

use App\Models\Dress;
use App\Models\Specification;
use Illuminate\Database\Seeder;

class DressSeeder extends Seeder
{
    public function run()
    {

        $colors = Specification::where('name', 'Color')->first()->options;
        $sizes = Specification::where('name', 'Size')->first()->options;

        $dresses = [
            [
                'name' => 'Elegant White Dress',
                'image' => 'images/White-Dress.png',
                'description' => 'A beautiful elegant white dress perfect for weddings.',
                'quantity' => 10,
                'rental_price' => 150.00,
                'specifications' => [
                    'Color' => $colors->random()->id,
                    'Size' => $sizes->random()->id,
                ],
            ],
            [
                'name' => 'Classic Black Dress',
                'image' => 'images/black-dress.png',
                'description' => 'A classic black dress suitable for formal events.',
                'quantity' => 5,
                'rental_price' => 120.00,
                'specifications' => [
                    'Color' => $colors->random()->id,
                    'Size' => $sizes->random()->id,
                ],
            ],
            // Add more dresses as needed
        ];

        foreach ($dresses as $dressData) {
            $dress = Dress::create([
                'name' => $dressData['name'],
                'image' => $dressData['image'],
                'description' => $dressData['description'],
                'quantity' => $dressData['quantity'],
                'rental_price' => $dressData['rental_price'],
            ]);

            foreach ($dressData['specifications'] as $specName => $specOptionId) {
                $specification = Specification::where('name', $specName)->first();
                $dress->specifications()->attach($specification->id, ['option_id' => $specOptionId]);
            }
        }
    }
}
