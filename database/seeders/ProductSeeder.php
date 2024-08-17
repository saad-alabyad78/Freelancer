<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::create([
            'name' => 'Product 1',
            'description' => 'This is the description for product 1.',
            'image_id' => 1,
            'freelancer_id' => 1,
            'price' => 1000,
        ]);

        Product::create([
            'name' => 'Product 2',
            'description' => 'This is the description for product 2.',
            'image_id' => 2,
            'freelancer_id' => 2,
            'price' => 1500,
        ]);
    }
}
